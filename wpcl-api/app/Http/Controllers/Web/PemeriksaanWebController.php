<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Kereta;
use Illuminate\Http\Request;

class PemeriksaanWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeriksaan::with(['kereta', 'pejabat', 'detailPemeriksaan']);

        // Filter Kereta
        if ($request->filled('id_kereta')) {
            $query->where('id_kereta', $request->id_kereta);
        }

        // Filter Tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter Status Locotrack
        if ($request->filled('locotrack')) {
            $query->where('locotrack', $request->locotrack);
        }

        // Search keyword (Petugas / No Ref)
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function($q) use ($keyword) {
                $q->where('nama_petugas', 'like', "%{$keyword}%")
                  ->orWhere('nipp', 'like', "%{$keyword}%")
                  ->orWhere('no_ref', 'like', "%{$keyword}%");
            });
        }

        $pemeriksaanList = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $daftarKereta = Kereta::orderBy('nama_ka')->orderBy('no_ka')->get();

        return view('admin.pemeriksaan.index', compact('pemeriksaanList', 'daftarKereta'));
    }

    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with([
            'kereta',
            'pejabat',
            'detailPemeriksaan' => function($q) {
                $q->orderBy('urutan');
            },
            'detailPemeriksaan.sarana'
        ])->findOrFail($id);

        return view('admin.pemeriksaan.show', compact('pemeriksaan'));
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with([
            'kereta',
            'pejabat',
            'detailPemeriksaan' => function($q) {
                $q->orderBy('urutan');
            },
            'detailPemeriksaan.sarana'
        ])->findOrFail($id);

        $daftarPejabat = \App\Models\Pejabat::all();
        $daftarKereta = Kereta::orderBy('nama_ka')->orderBy('no_ka')->get();

        return view('admin.pemeriksaan.edit', compact('pemeriksaan', 'daftarPejabat', 'daftarKereta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'nipp' => 'required|string|max:30',
            'tanggal' => 'required|date',
            'id_pejabat' => 'nullable|exists:pejabat,id_pejabat',
            'business_area' => 'nullable|string|max:50',
            'no_ref' => 'nullable|string|max:50',
            'locotrack' => 'required|in:B,R,T,Baik,Rusak,Tiada',
            'loco_id' => 'nullable|string|max:50',
            'nomor_sarana_loco' => 'nullable|string|max:50',
            'no_dokumen' => 'nullable|string|max:50',
            'versi_dokumen' => 'nullable|string|max:20',
            'catatan' => 'nullable|string',
            'catatan_keseluruhan' => 'nullable|string',
            'detail' => 'required|array',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $pejabatNama = $pemeriksaan->pejabat_nama;
            $pejabatNipp = $pemeriksaan->pejabat_nipp;
            $pejabatJabatan = $pemeriksaan->pejabat_jabatan;

            if ($request->filled('id_pejabat')) {
                $pejabat = \App\Models\Pejabat::find($request->id_pejabat);
                if ($pejabat) {
                    $pejabatNama = $pejabat->nama;
                    $pejabatNipp = $pejabat->nipp;
                    $pejabatJabatan = $pejabat->jabatan;
                }
            }

            $mapStatus = function($val) {
                if ($val === 'Baik' || $val === 'B') return 'B';
                if ($val === 'Rusak' || $val === 'R') return 'R';
                if ($val === 'Tiada' || $val === 'T') return 'T';
                return 'B';
            };

            $pemeriksaan->update([
                'nama_petugas' => $request->nama_petugas,
                'nipp' => $request->nipp,
                'id_pejabat' => $request->id_pejabat,
                'pejabat_nama' => $pejabatNama,
                'pejabat_nipp' => $pejabatNipp,
                'pejabat_jabatan' => $pejabatJabatan,
                'tanggal' => $request->tanggal,
                'business_area' => $request->business_area,
                'no_ref' => $request->no_ref,
                'locotrack' => $mapStatus($request->locotrack),
                'loco_id' => $request->loco_id,
                'nomor_sarana_loco' => $request->nomor_sarana_loco,
                'no_dokumen' => $request->no_dokumen,
                'versi_dokumen' => $request->versi_dokumen,
                'catatan' => $request->catatan,
                'catatan_keseluruhan' => $request->catatan_keseluruhan,
            ]);

            // Update Detail Sarana
            foreach ($request->detail as $idDetail => $item) {
                $detailModel = \App\Models\DetailPemeriksaan::where('id_detail', $idDetail)
                    ->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                    ->first();

                if ($detailModel) {
                    $sisiA = $mapStatus($item['sisi_a'] ?? 'B');
                    $sisiE = $mapStatus($item['sisi_e'] ?? 'B');
                    $tdKecil = $mapStatus($item['td_kecil'] ?? 'B');
                    $tdBesar = $mapStatus($item['td_besar'] ?? 'B');

                    $detailModel->update([
                        'cctv' => $mapStatus($item['cctv'] ?? 'B'),
                        'backup' => $mapStatus($item['backup'] ?? 'B'),
                        'sisi_a' => $sisiA,
                        'sisi_e' => $sisiE,
                        'td_kecil' => $tdKecil,
                        'td_besar' => $tdBesar,
                        'wifi' => $mapStatus($item['wifi'] ?? 'B'),
                        'pids_luar' => ($sisiA === 'R' || $sisiE === 'R') ? 'R' : 'B',
                        'pids_dalam' => ($tdKecil === 'R' || $tdBesar === 'R') ? 'R' : 'B',
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('web.pemeriksaan.show', $pemeriksaan->id_pemeriksaan)
                ->with('success', 'Data hasil pemeriksaan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function updateDokumen(Request $request, $id)
    {
        $request->validate([
            'no_dokumen' => 'nullable|string|max:50',
            'versi_dokumen' => 'nullable|string|max:20',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update([
            'no_dokumen' => $request->no_dokumen,
            'versi_dokumen' => $request->versi_dokumen,
        ]);

        return back()->with('success', 'Nomor & Versi Dokumen berhasil diperbarui.');
    }

    public function print($id)
    {
        $pemeriksaan = Pemeriksaan::with([
            'kereta',
            'pejabat',
            'detailPemeriksaan' => function($q) {
                $q->orderBy('urutan');
            },
            'detailPemeriksaan.sarana'
        ])->findOrFail($id);

        return view('admin.pemeriksaan.print', compact('pemeriksaan'));
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->delete();

        return redirect()->route('web.pemeriksaan.index')->with('success', 'Data pemeriksaan berhasil dihapus.');
    }

    public function destroyDetail($id, $idDetail)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $detail = \App\Models\DetailPemeriksaan::where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
            ->where('id_detail', $idDetail)
            ->firstOrFail();

        $saranaName = ($detail->sarana->kode_sarana ?? '') . ' ' . ($detail->sarana->nomor_sarana ?? '');
        $detail->delete();

        return back()->with('success', "Baris sarana {$saranaName} berhasil dihapus dari pemeriksaan ini.");
    }
}
