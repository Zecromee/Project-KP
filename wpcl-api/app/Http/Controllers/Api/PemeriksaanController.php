<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\DetailPemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    // ==========================================================
    // SIMPAN PEMERIKSAAN
    // POST /pemeriksaan
    // ==========================================================

    private function mapStatus($val)
    {
        $v = strtolower(trim($val ?? ''));
        if ($v === 'baik' || $v === 'b') return 'B';
        if ($v === 'rusak' || $v === 'r') return 'R';
        if ($v === 'tiada' || $v === 't') return 'T';
        return 'B';
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string',
            'nipp' => 'required|string',
            'id_kereta' => 'required|exists:kereta,id_kereta',
            'id_pejabat' => 'nullable|exists:pejabat,id_pejabat',
            'pejabat_nama' => 'nullable|string',
            'pejabat_nipp' => 'nullable|string',
            'pejabat_jabatan' => 'nullable|string',
            'tanggal' => 'required|date',
            'business_area' => 'nullable|string',
            'no_ref' => 'nullable|string',
            'locotrack' => 'required|in:B,R,T,Baik,Rusak,Tiada',
            'loco_id' => 'nullable|string|max:50',
            'nomor_sarana_loco' => 'nullable|string|max:50',
            'no_dokumen' => 'nullable|string|max:50',
            'versi_dokumen' => 'nullable|string|max:20',
            'catatan' => 'nullable|string',
            'catatan_keseluruhan' => 'nullable|string',
            'detail' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $pejabatNama = $request->pejabat_nama;
            $pejabatNipp = $request->pejabat_nipp;
            $pejabatJabatan = $request->pejabat_jabatan;

            if ($request->id_pejabat && (!$pejabatNama || !$pejabatNipp)) {
                $pejabat = \App\Models\Pejabat::find($request->id_pejabat);
                if ($pejabat) {
                    $pejabatNama = $pejabat->nama;
                    $pejabatNipp = $pejabat->nipp;
                    $pejabatJabatan = $pejabat->jabatan;
                }
            }

            // ==================================================
            // SIMPAN DATA PEMERIKSAAN UTAMA
            // ==================================================

            $pemeriksaan = Pemeriksaan::create([
                'nama_petugas' => $request->nama_petugas,
                'nipp' => $request->nipp,
                'id_kereta' => $request->id_kereta,
                'id_pejabat' => $request->id_pejabat,
                'pejabat_nama' => $pejabatNama,
                'pejabat_nipp' => $pejabatNipp,
                'pejabat_jabatan' => $pejabatJabatan,
                'tanggal' => $request->tanggal,
                'business_area' => $request->business_area,
                'no_ref' => $request->no_ref,
                'locotrack' => $this->mapStatus($request->locotrack),
                'loco_id' => $request->loco_id,
                'nomor_sarana_loco' => $request->nomor_sarana_loco,
                'no_dokumen' => $request->no_dokumen,
                'versi_dokumen' => $request->versi_dokumen,
                'catatan' => $request->catatan,
                'catatan_keseluruhan' => $request->catatan_keseluruhan,
            ]);

            // ==================================================
            // SIMPAN DETAIL PEMERIKSAAN
            // ==================================================

            foreach ($request->detail as $item) {
                $sisiA = $this->mapStatus($item['sisi_a'] ?? 'B');
                $sisiE = $this->mapStatus($item['sisi_e'] ?? 'B');
                $tdKecil = $this->mapStatus($item['td_kecil'] ?? 'B');
                $tdBesar = $this->mapStatus($item['td_besar'] ?? 'B');

                $idSarana = $item['id_sarana'] ?? 0;
                if (empty($idSarana) || $idSarana <= 0 || !\App\Models\Sarana::where('id_sarana', $idSarana)->exists()) {
                    $sarana = \App\Models\Sarana::firstOrCreate(
                        [
                            'id_kereta' => $pemeriksaan->id_kereta,
                            'kode_sarana' => $item['kode_sarana'] ?? 'K1',
                            'nomor_sarana' => $item['nomor_sarana'] ?? '00000',
                        ],
                        [
                            'seri_sarana' => $item['seri_sarana'] ?? 'SS NG',
                            'depo_induk' => $item['depo_induk'] ?? 'YK',
                        ]
                    );
                    $idSarana = $sarana->id_sarana;
                }

                DetailPemeriksaan::create([
                    'id_pemeriksaan' => $pemeriksaan->id_pemeriksaan,
                    'id_sarana' => $idSarana,
                    'urutan' => $item['urutan'],

                    'pids_luar' => ($sisiA === 'R' || $sisiE === 'R') ? 'R' : $this->mapStatus($item['pids_luar'] ?? 'B'),
                    'pids_dalam' => ($tdKecil === 'R' || $tdBesar === 'R') ? 'R' : $this->mapStatus($item['pids_dalam'] ?? 'B'),

                    'cctv' => $this->mapStatus($item['cctv'] ?? 'B'),
                    'backup' => $this->mapStatus($item['backup'] ?? 'B'),
                    'wifi' => $this->mapStatus($item['wifi'] ?? 'B'),

                    'sisi_a' => $sisiA,
                    'sisi_e' => $sisiE,
                    'td_kecil' => $tdKecil,
                    'td_besar' => $tdBesar,

                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan berhasil disimpan',
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // ==========================================================
    // GET /pemeriksaan
    //
    // Menampilkan daftar riwayat pemeriksaan.
    // Hanya data ringkas yang ditampilkan di halaman Riwayat.
    // ==========================================================

    public function index()
    {
        $data = Pemeriksaan::with('kereta')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($p) {

                return [
                    // Primary key pemeriksaan
                    'id' => $p->id_pemeriksaan,

                    // Identitas pemeriksaan
                    'no_ref' => $p->no_ref,

                    'tanggal' => $p->tanggal,

                    'business_area' => $p->business_area,

                    // Nama KA berasal dari relasi kereta
                    'nama_ka' => $p->kereta->nama_ka ?? '-',

                    // Petugas
                    'nama_petugas' => $p->nama_petugas,

                    'nipp' => $p->nipp,

                    // Pejabat Mengetahui
                    'id_pejabat' => $p->id_pejabat,
                    'pejabat_nama' => $p->pejabat_nama ?? $p->pejabat->nama ?? null,
                    'pejabat_nipp' => $p->pejabat_nipp ?? $p->pejabat->nipp ?? null,
                    'pejabat_jabatan' => $p->pejabat_jabatan ?? $p->pejabat->jabatan ?? null,

                    // Locotrack
                    'locotrack' => $p->locotrack,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    // ==========================================================
    // GET /pemeriksaan/{id}
    //
    // Menampilkan DETAIL LENGKAP satu pemeriksaan.
    //
    // Data yang dikembalikan:
    // - Identitas pemeriksaan
    // - Nama KA
    // - Petugas
    // - Locotrack
    // - Catatan
    // - Seluruh detail sarana
    //
    // Endpoint ini dipakai oleh:
    // RiwayatDetailScreen
    // ==========================================================

    public function show($id)
    {
        // ======================================================
        // AMBIL PEMERIKSAAN
        //
        // Relationship yang benar berdasarkan model:
        //
        // Pemeriksaan
        //      |
        //      └── detailPemeriksaan()
        //              |
        //              └── sarana()
        //
        // BUKAN:
        // details.sarana
        // ======================================================

        $p = Pemeriksaan::with([
            'kereta',
            'detailPemeriksaan.sarana',
        ])->findOrFail($id);


        // ======================================================
        // BENTUK DATA DETAIL
        // ======================================================

        $detail = $p->detailPemeriksaan
            ->sortBy('urutan')
            ->map(function ($d) {

                return [
                    // ------------------------------------------
                    // IDENTITAS SARANA
                    // ------------------------------------------

                    'id_sarana' => $d->id_sarana,

                    'kode_sarana' => $d->sarana->kode_sarana ?? '',

                    'nomor_sarana' => $d->sarana->nomor_sarana ?? '-',

                    // ------------------------------------------
                    // PIDS
                    // ------------------------------------------

                    'pids_luar' => $d->pids_luar,

                    'pids_dalam' => $d->pids_dalam,

                    // ------------------------------------------
                    // CCTV
                    // ------------------------------------------

                    'cctv' => $d->cctv,

                    'backup' => $d->backup,

                    // ------------------------------------------
                    // WIFI
                    // ------------------------------------------

                    'wifi' => $d->wifi,

                    // ------------------------------------------
                    // PIDS LUAR
                    // ------------------------------------------

                    'sisi_a' => $d->sisi_a,

                    'sisi_e' => $d->sisi_e,

                    // ------------------------------------------
                    // PIDS DALAM
                    // ------------------------------------------

                    'td_kecil' => $d->td_kecil,

                    'td_besar' => $d->td_besar,

                    // ------------------------------------------
                    // KETERANGAN
                    // ------------------------------------------

                    'keterangan' => $d->keterangan,
                ];
            })
            ->values();


        // ======================================================
        // RESPONSE
        // ======================================================

        return response()->json([
            'success' => true,

            'data' => [

                // =================================================
                // IDENTITAS PEMERIKSAAN
                // =================================================

                'id' => $p->id_pemeriksaan,

                'no_ref' => $p->no_ref,

                'tanggal' => $p->tanggal,

                'business_area' => $p->business_area,

                // Nama KA dari tabel kereta
                'nama_ka' => $p->kereta->nama_ka ?? '-',

                // Petugas
                'nama_petugas' => $p->nama_petugas,

                'nipp' => $p->nipp,

                // Pejabat Mengetahui
                'id_pejabat' => $p->id_pejabat,
                'pejabat_nama' => $p->pejabat_nama ?? $p->pejabat->nama ?? null,
                'pejabat_nipp' => $p->pejabat_nipp ?? $p->pejabat->nipp ?? null,
                'pejabat_jabatan' => $p->pejabat_jabatan ?? $p->pejabat->jabatan ?? null,

                // Locotrack
                'locotrack' => $p->locotrack,

                // Loco ID & Nomor Sarana
                'loco_id' => $p->loco_id,
                'nomor_sarana_loco' => $p->nomor_sarana_loco,

                // No Dokumen & Versi
                'no_dokumen' => $p->no_dokumen,
                'versi_dokumen' => $p->versi_dokumen,

                // Catatan
                'catatan' => $p->catatan,
                'catatan_keseluruhan' => $p->catatan_keseluruhan,

                // =================================================
                // DETAIL SARANA
                // =================================================

                'detail' => $detail,
            ],
        ]);
    }
}