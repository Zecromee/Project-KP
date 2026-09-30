@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--kai-blue);">Dashboard Monitoring WPCL</h2>
        <p class="text-muted mb-0">Sistem Workstation Passenger Check List - Depo Kereta Yogyakarta</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('web.stamformasi.import') }}" class="btn btn-kai-orange">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Stamformasi
        </a>
        <a href="{{ route('web.pemeriksaan.index') }}" class="btn btn-kai-primary">
            <i class="bi bi-clipboard-data me-1"></i> Lihat Pemeriksaan
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 h-100 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Pemeriksaan</span>
                    <h3 class="fw-bold my-1 text-primary">{{ $totalPemeriksaan }}</h3>
                    <small class="text-muted">Data tersimpan</small>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 rounded-circle text-primary">
                    <i class="bi bi-clipboard2-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Locotrack Baik</span>
                    <h3 class="fw-bold my-1 text-success">{{ $locotrackBaik }}</h3>
                    <small class="text-muted">Kondisi siap operasi</small>
                </div>
                <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success">
                    <i class="bi bi-check2-circle fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Rangkaian KA</span>
                    <h3 class="fw-bold my-1 text-warning">{{ $totalKereta }}</h3>
                    <small class="text-muted">{{ $totalSarana }} Gerbong terdaftar</small>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 rounded-circle text-warning">
                    <i class="bi bi-train-front fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Pejabat IT</span>
                    <h3 class="fw-bold my-1 text-info">{{ $totalPejabat }}</h3>
                    <small class="text-muted">Manager & Assman</small>
                </div>
                <div class="p-3 bg-info bg-opacity-10 rounded-circle text-info">
                    <i class="bi bi-person-badge fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Pemeriksaan Terbaru -->
<div class="card">
    <div class="card-header card-header-kai d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-5 text-primary"></i>
            <span class="fs-6">Pemeriksaan Terbaru</span>
        </div>
        <a href="{{ route('web.pemeriksaan.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No Ref</th>
                        <th>Tanggal & Waktu</th>
                        <th>Kereta Api</th>
                        <th>Petugas Lapangan</th>
                        <th>Mengetahui</th>
                        <th class="text-center">Locotrack</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemeriksaanTerbaru as $p)
                        <tr>
                            <td class="ps-3 fw-semibold text-primary">{{ $p->no_ref ?? 'WPCL-'.$p->id_pemeriksaan }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->kereta->no_ka ?? '-' }}</span>
                                <strong>{{ $p->kereta->nama_ka ?? '-' }}</strong>
                            </td>
                            <td>
                                <div>{{ $p->nama_petugas }}</div>
                                <small class="text-muted">NIPP: {{ $p->nipp }}</small>
                            </td>
                            <td>
                                <div>{{ $p->pejabat_nama ?? ($p->pejabat->nama ?? '-') }}</div>
                                <small class="text-muted">{{ $p->pejabat_jabatan ?? ($p->pejabat->jabatan ?? '-') }}</small>
                            </td>
                            <td class="text-center">
                                @if($p->locotrack == 'B')
                                    <span class="badge badge-status-baik px-3 py-2"><i class="bi bi-check-circle me-1"></i> Baik</span>
                                @elseif($p->locotrack == 'R')
                                    <span class="badge badge-status-rusak px-3 py-2"><i class="bi bi-x-circle me-1"></i> Rusak</span>
                                @else
                                    <span class="badge badge-status-tiada px-3 py-2"><i class="bi bi-dash-circle me-1"></i> Tiada</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('web.pemeriksaan.show', $p->id_pemeriksaan) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="{{ route('web.pemeriksaan.print', $p->id_pemeriksaan) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cetak / PDF">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data pemeriksaan tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
