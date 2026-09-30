@extends('layouts.app')

@section('title', 'Import Stamformasi Kereta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('web.stamformasi.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Formasi
        </a>
        <h2 class="fw-bold mb-0" style="color: var(--kai-blue);">Import / Transpose Stamformasi Depo Yogyakarta</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header card-header-kai py-3">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Form Input Data Stamformasi
            </div>
            <div class="card-body">
                <form action="{{ route('web.stamformasi.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Option 1: File Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Opsi 1: Upload File (.CSV atau .TXT)</label>
                        <input type="file" name="file_csv" class="form-control" accept=".csv,.txt">
                        <small class="text-muted">Upload file CSV stamformasi hasil export Excel Depo Yogyakarta (Pemisah titik koma <code>;</code> atau koma <code>,</code>).</small>
                    </div>

                    <div class="d-flex align-items-center my-3">
                        <hr class="flex-grow-1">
                        <span class="px-3 text-muted fw-semibold small">ATAU TEMPEL TEKS LANGSUNG</span>
                        <hr class="flex-grow-1">
                    </div>

                    <!-- Option 2: Paste CSV Text -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Opsi 2: Copy-Paste Data Teks / CSV Stamformasi</label>
                        <textarea name="csv_text" class="form-control font-monospace" rows="12" placeholder="Tempelkan isi CSV / tabel stamformasi di sini...
Contoh format:
;;1;2;3...
No KA;;KA 43 / 44 / 45;KA 47 / 48 / 49;...
Nama Ka;;Taksaka;Taksaka;...
Relasi;;YK - GMR / GMR - YK;...
Jam;;08.45 - 15.08;...
1;EKS-1;K1 SS NG. 0 23 26;...
2;EKS-2;K1 SS NG. 0 23 27;..."></textarea>
                        <small class="text-muted">Sistem akan otomatis membersihkan nomor sarana (menghapus spasi), memisahkan trip multi-KA (misal KA 43/44/45 menjadi 3 KA terpisah), dan mengaitkan sarana secara otomatis.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-kai-orange py-2 fw-bold">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Proses & Simpan Stamformasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info / Instructions Card -->
    <div class="col-lg-4">
        <div class="card bg-light border-0">
            <div class="card-header bg-transparent fw-bold text-dark border-bottom">
                <i class="bi bi-info-circle-fill text-primary me-2"></i> Panduan Import
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Aturan Parser Otomatis:</h6>
                <ul class="small text-muted ps-3 mb-3">
                    <li><strong>Pemisahan Trip:</strong> KA dengan banyak nomor perjalanan (contoh: <code>KA 43 / 44 / 45</code>) otomatis dipecah menjadi 3 entri KA individual sehingga petugas mobile dapat memilih nomor KA yang tepat.</li>
                    <li><strong>Pembersihan Nomor Sarana:</strong> Teks seperti <code>K1 SS NG. 0 23 26 Yk</code> otomatis dipecah menjadi Kode <code>K1</code>, Nomor <code>02326</code>, Seri <code>SS NG</code>, dan Depo <code>YK</code>.</li>
                    <li><strong>Tipe Khusus:</strong> Kode <code>K1lux</code> (Kereta Luxury), <code>M1</code> (Kereta Makan), <code>P</code> (Pembangkit) dipertahankan sesuai standarisasi KAI.</li>
                </ul>

                <div class="alert alert-info py-2 px-3 small mb-0">
                    <i class="bi bi-lightbulb me-1"></i> <strong>Tips:</strong> Data yang diimpor akan langsung tersinkronisasi dan tampil di aplikasi mobile Flutter saat petugas memilih kereta api.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
