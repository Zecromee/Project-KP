<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kereta;
use App\Models\Sarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StamformasiWebController extends Controller
{
    public function index()
    {
        $daftarKereta = Kereta::with('sarana')->orderBy('nama_ka')->orderBy('no_ka')->get();
        return view('admin.stamformasi.index', compact('daftarKereta'));
    }

    public function importForm()
    {
        return view('admin.stamformasi.import');
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'csv_text' => 'nullable|string',
            'file_csv' => 'nullable|file|mimes:csv,txt,xlsx',
        ]);

        $content = '';

        if ($request->hasFile('file_csv')) {
            $content = file_get_contents($request->file('file_csv')->getRealPath());
        } elseif ($request->filled('csv_text')) {
            $content = $request->csv_text;
        } else {
            return back()->with('error', 'Silakan unggah file atau tempelkan data CSV / teks stamformasi.');
        }

        try {
            $parsedCount = $this->parseAndStoreStamformasi($content);
            return redirect()->route('web.stamformasi.index')
                ->with('success', "Berhasil mengimpor stamformasi! Total {$parsedCount} rangkaian kereta dan gerbong diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal memproses import: " . $e->getMessage());
        }
    }

    public function destroySarana($id)
    {
        $sarana = Sarana::findOrFail($id);
        $kodeNo = "{$sarana->kode_sarana} {$sarana->nomor_sarana}";
        $sarana->delete();
        return back()->with('success', "Sarana {$kodeNo} berhasil dihapus.");
    }

    public function destroyKereta($id)
    {
        $kereta = Kereta::findOrFail($id);
        $nama = "{$kereta->no_ka} - {$kereta->nama_ka}";
        Sarana::where('id_kereta', $kereta->id_kereta)->delete();
        $kereta->delete();
        return back()->with('success', "Rangkaian {$nama} beserta seluruh sarananya berhasil dihapus.");
    }

    public function storeSarana(Request $request, $id)
    {
        $request->validate([
            'kode_sarana' => 'required|string|max:20',
            'nomor_sarana' => 'required|string|max:20',
            'seri_sarana' => 'nullable|string|max:50',
            'depo_induk' => 'nullable|string|max:20',
        ]);

        $kereta = Kereta::findOrFail($id);
        $nomorBersih = str_replace(' ', '', $request->nomor_sarana);

        Sarana::create([
            'id_kereta' => $kereta->id_kereta,
            'kode_sarana' => strtoupper(trim($request->kode_sarana)),
            'nomor_sarana' => $nomorBersih,
            'seri_sarana' => $request->seri_sarana ?: $request->kode_sarana,
            'depo_induk' => strtoupper(trim($request->depo_induk ?: 'YK')),
        ]);

        return back()->with('success', "Sarana {$request->kode_sarana} {$nomorBersih} berhasil ditambahkan ke {$kereta->nama_ka}.");
    }

    public function updateSarana(Request $request, $id)
    {
        $request->validate([
            'kode_sarana' => 'required|string|max:20',
            'nomor_sarana' => 'required|string|max:20',
            'seri_sarana' => 'nullable|string|max:50',
            'depo_induk' => 'nullable|string|max:20',
        ]);

        $sarana = Sarana::findOrFail($id);
        $nomorBersih = str_replace(' ', '', $request->nomor_sarana);

        $sarana->update([
            'kode_sarana' => strtoupper(trim($request->kode_sarana)),
            'nomor_sarana' => $nomorBersih,
            'seri_sarana' => $request->seri_sarana ?: $request->kode_sarana,
            'depo_induk' => strtoupper(trim($request->depo_induk ?: 'YK')),
        ]);

        $kereta = $sarana->kereta;
        return back()->with('success', "Sarana {$request->kode_sarana} {$nomorBersih} berhasil diperbarui di {$kereta->nama_ka}.");
    }

    public function resetAll()
    {
        DB::beginTransaction();
        try {
            // Non-aktifkan foreign key checks sementara untuk truncate bersih
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Sarana::truncate();
            Kereta::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            return back()->with('success', "Seluruh data stamformasi dan sarana kereta berhasil dibersihkan / di-reset.");
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', "Gagal me-reset stamformasi: " . $e->getMessage());
        }
    }

    private function parseAndStoreStamformasi($rawContent)
    {
        $rawContent = trim($rawContent);
        if (empty($rawContent)) {
            throw new \Exception("Data stamformasi kosong.");
        }

        $lines = preg_split('/\r\n|\r|\n/', $rawContent);
        $lines = array_filter(array_map('trim', $lines));

        if (empty($lines)) {
            throw new \Exception("Data stamformasi tidak valid.");
        }

        // Deteksi delimiter utama (\t, ;, ,)
        $firstLine = reset($lines);
        $tabCount = substr_count($firstLine, "\t");
        $semiCount = substr_count($firstLine, ";");
        $commaCount = substr_count($firstLine, ",");

        $delimiter = ";";
        if ($tabCount > $semiCount && $tabCount > $commaCount) {
            $delimiter = "\t";
        } elseif ($commaCount > $semiCount && $commaCount > $tabCount) {
            $delimiter = ",";
        }

        $splitLine = function($line) use ($delimiter) {
            return str_getcsv($line, $delimiter);
        };

        // Cek apakah data bertipe Vertikal Transpose (memiliki baris "No KA" & nomor 1, 2)
        $isVertical = false;
        foreach ($lines as $line) {
            $cells = $splitLine($line);
            if (!empty($cells) && (stripos($cells[0], 'No KA') !== false || stripos($cells[0], 'NoKA') !== false)) {
                $isVertical = true;
                break;
            }
        }

        $formations = [];

        if ($isVertical) {
            // ==========================================
            // FORMAT VERTIKAL TRANSPOSE
            // ==========================================
            $noKaRow = null;
            $namaKaRow = null;
            $relasiRow = null;
            $jamRow = null;
            $saranaStartRow = null;

            foreach ($lines as $idx => $line) {
                $cells = $splitLine($line);
                if (empty($cells) || count($cells) < 2) continue;

                $first = trim($cells[0]);
                if (stripos($first, 'No KA') !== false || stripos($first, 'NoKA') !== false) {
                    $noKaRow = $cells;
                } elseif (stripos($first, 'Nama Ka') !== false || stripos($first, 'NamaKA') !== false) {
                    $namaKaRow = $cells;
                } elseif (stripos($first, 'Relasi') !== false) {
                    $relasiRow = $cells;
                } elseif (stripos($first, 'Jam') !== false) {
                    $jamRow = $cells;
                } elseif ($first === '1' && $saranaStartRow === null) {
                    $saranaStartRow = $idx;
                }
            }

            if (!$noKaRow || !$namaKaRow || $saranaStartRow === null) {
                throw new \Exception("Header kolom (No KA, Nama KA, atau nomor urut gerbong 1) tidak ditemukan pada format vertikal.");
            }

            $colCount = count($noKaRow);
            for ($col = 1; $col < $colCount; $col++) {
                $rawNoKa = trim($noKaRow[$col] ?? '');
                $rawNamaKa = trim($namaKaRow[$col] ?? '');
                $rawRelasi = trim($relasiRow[$col] ?? '');
                $rawJam = trim($jamRow[$col] ?? '');

                if (empty($rawNoKa) || empty($rawNamaKa)) continue;

                $saranaList = [];
                for ($rowIdx = $saranaStartRow; $rowIdx < count($lines); $rowIdx++) {
                    $rowCells = $splitLine($lines[$rowIdx]);
                    $rowNum = trim($rowCells[0] ?? '');
                    if (!is_numeric($rowNum)) break;

                    $saranaStr = trim($rowCells[$col] ?? '');
                    if (!empty($saranaStr)) {
                        $parsedSarana = $this->cleanSaranaString($saranaStr);
                        if ($parsedSarana) {
                            $saranaList[] = $parsedSarana;
                        }
                    }
                }

                if (!empty($saranaList)) {
                    $formations[] = [
                        'no_ka_raw' => $rawNoKa,
                        'nama_ka' => $rawNamaKa,
                        'relasi_raw' => $rawRelasi,
                        'jam_raw' => $rawJam,
                        'sarana' => $saranaList,
                    ];
                }
            }
        } else {
            // ==========================================
            // FORMAT HORISONTAL (LANGSUNG DARI EXCEL)
            // 1 Baris = 1 Kereta (No KA | Nama KA | Relasi | Jam | [Tanggal] | Sarana 1 | Sarana 2 | ...)
            // ==========================================
            foreach ($lines as $line) {
                $cells = $splitLine($line);
                if (empty($cells) || count($cells) < 4) continue;

                $rawNoKa = trim($cells[0]);
                // Lewati baris header jika ada
                if (stripos($rawNoKa, 'No KA') !== false || stripos($rawNoKa, 'NoKA') !== false) continue;

                $rawNamaKa = trim($cells[1] ?? '');
                $rawRelasi = trim($cells[2] ?? '');
                $rawJam = trim($cells[3] ?? '');

                if (empty($rawNoKa) || empty($rawNamaKa)) continue;

                // Cek apakah kolom 4 adalah tanggal (misal: 08-Jul-26 atau 2026-07-08)
                $startIndex = 4;
                if (isset($cells[4])) {
                    $col4 = trim($cells[4]);
                    if (preg_match('/^\d{1,2}[-\/][a-zA-Z0-9]+[-\/]\d{2,4}$/', $col4) || strtotime($col4) !== false) {
                        $startIndex = 5;
                    }
                }

                $saranaList = [];
                for ($c = $startIndex; $c < count($cells); $c++) {
                    $saranaStr = trim($cells[$c]);
                    if (!empty($saranaStr)) {
                        $parsedSarana = $this->cleanSaranaString($saranaStr);
                        if ($parsedSarana) {
                            $saranaList[] = $parsedSarana;
                        }
                    }
                }

                if (!empty($saranaList)) {
                    $formations[] = [
                        'no_ka_raw' => $rawNoKa,
                        'nama_ka' => $rawNamaKa,
                        'relasi_raw' => $rawRelasi,
                        'jam_raw' => $rawJam,
                        'sarana' => $saranaList,
                    ];
                }
            }
        }

        if (empty($formations)) {
            throw new \Exception("Tidak ada data formasi sarana yang berhasil dibaca. Pastikan format kolom sesuai.");
        }

        DB::beginTransaction();

        $totalTrains = 0;

        foreach ($formations as $f) {
            $noKas = array_map('trim', explode('/', str_replace(['KA ', 'Renc ', 'PLB '], '', $f['no_ka_raw'])));
            $relasis = array_map('trim', explode('/', $f['relasi_raw']));
            $jams = array_map('trim', explode('/', $f['jam_raw']));

            $tripCount = max(count($noKas), 1);

            for ($i = 0; $i < $tripCount; $i++) {
                $noKa = isset($noKas[$i]) ? 'KA ' . $noKas[$i] : $f['no_ka_raw'];
                $relasi = $relasis[$i] ?? ($relasis[0] ?? '-');
                $jamStr = $jams[$i] ?? ($jams[0] ?? '-');

                $jamBerangkat = '00:00:00';
                $jamDatang = '00:00:00';
                if (strpos($jamStr, '-') !== false) {
                    $jamParts = explode('-', $jamStr);
                    $jamBerangkat = trim(str_replace('.', ':', $jamParts[0])) . ':00';
                    $jamDatang = trim(str_replace('.', ':', $jamParts[1] ?? '00:00')) . ':00';
                }

                $kereta = Kereta::updateOrCreate(
                    ['no_ka' => $noKa],
                    [
                        'nama_ka' => $f['nama_ka'],
                        'relasi' => $relasi,
                        'jam_berangkat' => $jamBerangkat,
                        'jam_datang' => $jamDatang,
                    ]
                );

                // Hapus sarana lama kereta ini jika ada
                Sarana::where('id_kereta', $kereta->id_kereta)->delete();

                foreach ($f['sarana'] as $s) {
                    Sarana::create([
                        'id_kereta' => $kereta->id_kereta,
                        'kode_sarana' => $s['kode'],
                        'nomor_sarana' => $s['nomor'],
                        'seri_sarana' => $s['seri'],
                        'depo_induk' => $s['depo'],
                    ]);
                }

                $totalTrains++;
            }
        }

        DB::commit();

        return $totalTrains;
    }

    private function cleanSaranaString($str)
    {
        // Contoh: "K1lux SS NG. 0 24 04", "P SS NG. 0 23 04 Yk", "K1 SS NG. 0 25 48 Bd", "K3SS. 0 19 31 Yk"
        $str = trim($str);
        if (empty($str)) return null;

        // Ambil depo di akhir jika ada (Yk, Jak, Slo, Bd, Smt, etc)
        $depo = 'YK';
        if (preg_match('/\b(Yk|Jak|Slo|Bd|Smt|Jng|Cn|Sgu)\b/i', $str, $depoMatch)) {
            $depo = strtoupper($depoMatch[1]);
            $str = preg_replace('/\b' . preg_quote($depoMatch[1], '/') . '\b/i', '', $str);
        }

        // Ekstrak nomor sarana (angka di belakang, misal: 0 23 04 atau 0 18 132 atau 02304)
        $nomor = '';
        if (preg_match('/(\d[\s\d]{2,9})$/', trim($str), $numMatch)) {
            $nomor = str_replace(' ', '', $numMatch[1]);
            $str = trim(substr(trim($str), 0, -strlen($numMatch[0])));
        }

        $str = rtrim($str, ' .');

        // Ekstrak kode sarana (K1lux, K1, K3, M1, MP3, P, dll)
        $kode = 'K1';
        if (preg_match('/^(K1lux|K1|K3|M1|MP3|P|KP3)/i', $str, $kodeMatch)) {
            $kode = $kodeMatch[1];
        }

        $seri = trim(str_replace(['SS NG', 'SSNG', 'SS'], ['SS NG', 'SS NG', 'SS'], $str));
        if (empty($seri)) {
            $seri = $kode;
        }

        return [
            'kode' => $kode,
            'nomor' => $nomor,
            'seri' => $seri,
            'depo' => $depo,
        ];
    }
}
