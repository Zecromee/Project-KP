@extends('layouts.app')

@section('title', 'Detail Pemeriksaan ' . ($pemeriksaan->no_ref ?? ''))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('web.pemeriksaan.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h2 class="fw-bold mb-0" style="color: var(--kai-blue);">Detail Pemeriksaan WPCL</h2>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('web.pemeriksaan.edit', $pemeriksaan->id_pemeriksaan) }}" class="btn btn-kai-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
        <a href="{{ route('web.pemeriksaan.print', $pemeriksaan->id_pemeriksaan) }}" target="_blank" class="btn btn-kai-orange">
            <i class="bi bi-printer me-1"></i> Cetak / Export PDF
        </a>
    </div>
</div>

<!-- Header Info Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header card-header-kai">
                <i class="bi bi-train-front me-2"></i> Identitas Kereta & Pemeriksaan
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0 w-100">
                    <tr>
                        <td width="35%" class="text-muted fw-semibold">No Referensi</td>
                        <td width="5%">:</td>
                        <td class="fw-bold text-primary">{{ $pemeriksaan->no_ref ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Kereta Api</td>
                        <td>:</td>
                        <td class="fw-bold">{{ $pemeriksaan->kereta->no_ka ?? '-' }} - {{ $pemeriksaan->kereta->nama_ka ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Relasi / Jam</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->kereta->relasi ?? '-' }} ({{ $pemeriksaan->kereta->jam_berangkat ?? '-' }} - {{ $pemeriksaan->kereta->jam_datang ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal Periksa</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal)->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Business Area</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->business_area ?? 'DAOP 6' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header card-header-kai">
                <i class="bi bi-person-badge me-2"></i> Petugas & Status Locotrack
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0 w-100">
                    <tr>
                        <td width="35%" class="text-muted fw-semibold">Petugas Pemeriksa</td>
                        <td width="5%">:</td>
                        <td class="fw-bold">{{ $pemeriksaan->nama_petugas }} (NIPP: {{ $pemeriksaan->nipp }})</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Mengetahui</td>
                        <td>:</td>
                        <td class="fw-bold">{{ $pemeriksaan->pejabat_nama ?? ($pemeriksaan->pejabat->nama ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jabatan Pejabat</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->pejabat_jabatan ?? ($pemeriksaan->pejabat->jabatan ?? '-') }} (NIPP: {{ $pemeriksaan->pejabat_nipp ?? ($pemeriksaan->pejabat->nipp ?? '-') }})</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status Locotrack</td>
                        <td>:</td>
                        <td>
                            @if($pemeriksaan->locotrack == 'B')
                                <span class="badge badge-status-baik px-3 py-1">Baik (B)</span>
                            @elseif($pemeriksaan->locotrack == 'R')
                                <span class="badge badge-status-rusak px-3 py-1">Rusak (R)</span>
                            @else
                                <span class="badge badge-status-tiada px-3 py-1">Tiada (T)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Keterangan Locotrack</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Catatan Keseluruhan</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->catatan_keseluruhan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Locotrack ID</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->loco_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">No. Sarana Locotrack</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->nomor_sarana_loco ?? '-' }}</td>
                    </tr>
                    @if(!empty($pemeriksaan->no_dokumen) || !empty($pemeriksaan->versi_dokumen))
                    <tr>
                        <td class="text-muted fw-semibold">No. Dokumen</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->no_dokumen ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Versi Dokumen</td>
                        <td>:</td>
                        <td>{{ $pemeriksaan->versi_dokumen ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Checklist Sarana Table (Full width edge-to-edge) -->
<div class="card shadow-sm mb-4">
    <div class="card-header card-header-kai py-3 d-flex justify-content-between align-items-center">
        <span class="fs-6"><i class="bi bi-list-check me-2"></i> Rincian Checklist Sarana ({{ $pemeriksaan->detailPemeriksaan->count() }} Gerbong)</span>
        <a href="{{ route('web.pemeriksaan.edit', $pemeriksaan->id_pemeriksaan) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Edit Checklist
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive w-100">
            <table class="table table-bordered table-hover align-middle mb-0 text-center w-100" style="table-layout: auto;">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" class="align-middle" style="width: 45px;">No</th>
                        <th rowspan="2" class="align-middle text-start" style="width: 140px;">No. Sarana</th>
                        <th colspan="2">CCTV</th>
                        <th colspan="2">PIDS Luar</th>
                        <th colspan="2">PIDS Dalam</th>
                        <th rowspan="2" class="align-middle" style="width: 85px;">WiFi</th>
                        <th rowspan="2" class="align-middle text-start" style="min-width: 220px;">Note / Catatan</th>
                    </tr>
                    <tr>
                        <th style="width: 90px;">Berfungsi</th>
                        <th style="width: 90px;">Terbackup</th>
                        <th style="width: 90px;">Sisi A</th>
                        <th style="width: 90px;">Sisi E</th>
                        <th style="width: 90px;">TD Kecil</th>
                        <th style="width: 90px;">TD Besar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemeriksaan->detailPemeriksaan as $idx => $d)
                        @php
                            $renderBadge = function($val) {
                                if ($val == 'B') return '<span class="badge badge-status-baik px-2 py-1">Baik</span>';
                                if ($val == 'R') return '<span class="badge badge-status-rusak px-2 py-1">Rusak</span>';
                                return '<span class="badge badge-status-tiada px-2 py-1">Tiada</span>';
                            };
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="text-start fw-bold">
                                {{ $d->sarana->kode_sarana ?? '' }} {{ $d->sarana->nomor_sarana ?? '-' }}
                                @if(isset($d->sarana->depo_induk))
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 10px;">{{ $d->sarana->depo_induk }}</span>
                                @endif
                            </td>
                            <td>{!! $renderBadge($d->cctv) !!}</td>
                            <td>{!! $renderBadge($d->backup) !!}</td>
                            <td>{!! $renderBadge($d->sisi_a) !!}</td>
                            <td>{!! $renderBadge($d->sisi_e) !!}</td>
                            <td>{!! $renderBadge($d->td_kecil) !!}</td>
                            <td>{!! $renderBadge($d->td_besar) !!}</td>
                            <td>{!! $renderBadge($d->wifi) !!}</td>
                            <td class="text-start small">{{ $d->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
