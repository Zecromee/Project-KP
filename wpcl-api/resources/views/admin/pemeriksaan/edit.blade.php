@extends('layouts.app')

@section('title', 'Edit Pemeriksaan ' . ($pemeriksaan->no_ref ?? ''))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('web.pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
        </a>
        <h2 class="fw-bold mb-0" style="color: var(--kai-blue);">Edit Data Hasil Pemeriksaan WPCL</h2>
    </div>
</div>

<form action="{{ route('web.pemeriksaan.update', $pemeriksaan->id_pemeriksaan) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Header / Identitas Form -->
    <div class="card mb-4">
        <div class="card-header card-header-kai py-3">
            <i class="bi bi-pencil-square me-2"></i> Identitas Pemeriksaan & Petugas
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Kereta Api</label>
                    <input type="text" class="form-control bg-light" value="{{ $pemeriksaan->kereta->no_ka }} - {{ $pemeriksaan->kereta->nama_ka }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">No Referensi</label>
                    <input type="text" name="no_ref" class="form-control" value="{{ old('no_ref', $pemeriksaan->no_ref) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Tanggal Pemeriksaan</label>
                    <input type="datetime-local" name="tanggal" class="form-control" value="{{ old('tanggal', \Carbon\Carbon::parse($pemeriksaan->tanggal)->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Business Area</label>
                    <input type="text" name="business_area" class="form-control" value="{{ old('business_area', $pemeriksaan->business_area ?? 'DAOP 6') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Nama Petugas Pemeriksa</label>
                    <input type="text" name="nama_petugas" class="form-control" value="{{ old('nama_petugas', $pemeriksaan->nama_petugas) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">NIPP Petugas</label>
                    <input type="text" name="nipp" class="form-control" value="{{ old('nipp', $pemeriksaan->nipp) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Mengetahui (Pejabat Penandatangan)</label>
                    <select name="id_pejabat" class="form-select">
                        <option value="">-- Pilih Pejabat --</option>
                        @foreach($daftarPejabat as $pj)
                            <option value="{{ $pj->id_pejabat }}" {{ (old('id_pejabat', $pemeriksaan->id_pejabat) == $pj->id_pejabat) ? 'selected' : '' }}>
                                {{ $pj->nama }} ({{ $pj->jabatan }} - {{ $pj->nipp }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Locotrack -->
    <div class="card mb-4">
        <div class="card-header card-header-kai py-3">
            <i class="bi bi-cpu me-2"></i> Pemeriksaan Locotrack
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Status Locotrack</label>
                    <select name="locotrack" class="form-select fw-bold">
                        <option value="B" {{ old('locotrack', $pemeriksaan->locotrack) == 'B' ? 'selected' : '' }}>Baik (B)</option>
                        <option value="R" {{ old('locotrack', $pemeriksaan->locotrack) == 'R' ? 'selected' : '' }}>Rusak (R)</option>
                        <option value="T" {{ old('locotrack', $pemeriksaan->locotrack) == 'T' ? 'selected' : '' }}>Tiada (T)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Locotrack ID</label>
                    <input type="text" name="loco_id" class="form-control" value="{{ old('loco_id', $pemeriksaan->loco_id) }}" placeholder="Contoh: Loco ACB">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">No. Sarana Locotrack</label>
                    <input type="text" name="nomor_sarana_loco" class="form-control" value="{{ old('nomor_sarana_loco', $pemeriksaan->nomor_sarana_loco) }}" placeholder="Contoh: 02">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Keterangan Locotrack</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan khusus untuk locotrack...">{{ old('catatan', $pemeriksaan->catatan) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Catatan Keseluruhan</label>
                    <textarea name="catatan_keseluruhan" class="form-control" rows="2" placeholder="Catatan umum keseluruhan pemeriksaan...">{{ old('catatan_keseluruhan', $pemeriksaan->catatan_keseluruhan) }}</textarea>
                </div>

                <div class="col-12">
                    <div class="accordion" id="accordionDokumen">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDokumen" aria-expanded="false">
                                    <i class="bi bi-file-earmark-text me-2"></i> Info Dokumen (Opsional)
                                </button>
                            </h2>
                            <div id="collapseDokumen" class="accordion-collapse collapse" data-bs-parent="#accordionDokumen">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">No. Dokumen</label>
                                            <input type="text" name="no_dokumen" class="form-control" value="{{ old('no_dokumen', $pemeriksaan->no_dokumen) }}" placeholder="Masukkan nomor dokumen...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Versi Dokumen</label>
                                            <input type="text" name="versi_dokumen" class="form-control" value="{{ old('versi_dokumen', $pemeriksaan->versi_dokumen) }}" placeholder="Contoh: 1.0, Rev.2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Sarana Checklist Table -->
    <div class="card mb-4">
        <div class="card-header card-header-kai py-3 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-check me-2"></i> Edit Hasil Checklist Sarana ({{ $pemeriksaan->detailPemeriksaan->count() }} Gerbong)</span>
            <small class="text-muted">B = Baik | R = Rusak | T = Tiada</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-center w-100" style="table-layout: auto;">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="align-middle" style="width: 40px;">No</th>
                            <th rowspan="2" class="align-middle text-start" style="width: 140px;">No. Sarana</th>
                            <th colspan="2">CCTV</th>
                            <th colspan="2">PIDS Luar</th>
                            <th colspan="2">PIDS Dalam</th>
                            <th rowspan="2" class="align-middle" style="width: 90px;">WiFi</th>
                            <th rowspan="2" class="align-middle text-start">Catatan / Keterangan</th>
                        </tr>
                        <tr>
                            <th style="width: 90px;">Berfungsi</th>
                            <th style="width: 90px;">Backup</th>
                            <th style="width: 90px;">Sisi A</th>
                            <th style="width: 90px;">Sisi E</th>
                            <th style="width: 90px;">TD Kecil</th>
                            <th style="width: 90px;">TD Besar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemeriksaan->detailPemeriksaan as $idx => $d)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="text-start fw-bold">
                                    {{ $d->sarana->kode_sarana ?? '' }} {{ $d->sarana->nomor_sarana ?? '-' }}
                                    @if(isset($d->sarana->depo_induk))
                                        <span class="badge bg-light text-secondary border d-block mt-1" style="font-size: 10px;">{{ $d->sarana->depo_induk }}</span>
                                    @endif
                                </td>

                                <!-- CCTV Berfungsi -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][cctv]" class="form-select form-select-sm text-center fw-bold {{ $d->cctv == 'B' ? 'text-success' : ($d->cctv == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->cctv == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->cctv == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->cctv == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- CCTV Backup -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][backup]" class="form-select form-select-sm text-center fw-bold {{ $d->backup == 'B' ? 'text-success' : ($d->backup == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->backup == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->backup == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->backup == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- PIDS Sisi A -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][sisi_a]" class="form-select form-select-sm text-center fw-bold {{ $d->sisi_a == 'B' ? 'text-success' : ($d->sisi_a == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->sisi_a == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->sisi_a == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->sisi_a == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- PIDS Sisi E -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][sisi_e]" class="form-select form-select-sm text-center fw-bold {{ $d->sisi_e == 'B' ? 'text-success' : ($d->sisi_e == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->sisi_e == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->sisi_e == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->sisi_e == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- PIDS TD Kecil -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][td_kecil]" class="form-select form-select-sm text-center fw-bold {{ $d->td_kecil == 'B' ? 'text-success' : ($d->td_kecil == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->td_kecil == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->td_kecil == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->td_kecil == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- PIDS TD Besar -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][td_besar]" class="form-select form-select-sm text-center fw-bold {{ $d->td_besar == 'B' ? 'text-success' : ($d->td_besar == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->td_besar == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->td_besar == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->td_besar == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- WiFi -->
                                <td>
                                    <select name="detail[{{ $d->id_detail }}][wifi]" class="form-select form-select-sm text-center fw-bold {{ $d->wifi == 'B' ? 'text-success' : ($d->wifi == 'R' ? 'text-danger' : 'text-warning') }}">
                                        <option value="B" {{ $d->wifi == 'B' ? 'selected' : '' }}>Baik</option>
                                        <option value="R" {{ $d->wifi == 'R' ? 'selected' : '' }}>Rusak</option>
                                        <option value="T" {{ $d->wifi == 'T' ? 'selected' : '' }}>Tiada</option>
                                    </select>
                                </td>

                                <!-- Keterangan -->
                                <td class="text-start">
                                    <input type="text" name="detail[{{ $d->id_detail }}][keterangan]" class="form-control form-control-sm" value="{{ $d->keterangan }}" placeholder="Catatan gerbong...">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end gap-2 mb-5">
        <a href="{{ route('web.pemeriksaan.show', $pemeriksaan->id_pemeriksaan) }}" class="btn btn-secondary px-4">
            Batal
        </a>
        <button type="submit" class="btn btn-kai-primary px-4 fw-bold">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>
    </div>
</form>
@endsection
