@extends('layouts.app')

@section('title', 'Monitoring Pemeriksaan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--kai-blue);">Monitoring Riwayat Pemeriksaan</h2>
        <p class="text-muted mb-0">Daftar seluruh pemeriksaan sarana penumpang KA</p>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('web.pemeriksaan.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Pilih Kereta Api</label>
                <select name="id_kereta" class="form-select">
                    <option value="">-- Semua Kereta --</option>
                    @foreach($daftarKereta as $k)
                        <option value="{{ $k->id_kereta }}" {{ request('id_kereta') == $k->id_kereta ? 'selected' : '' }}>
                            {{ $k->no_ka }} - {{ $k->nama_ka }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status Locotrack</label>
                <select name="locotrack" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="B" {{ request('locotrack') == 'B' ? 'selected' : '' }}>Baik</option>
                    <option value="R" {{ request('locotrack') == 'R' ? 'selected' : '' }}>Rusak</option>
                    <option value="T" {{ request('locotrack') == 'T' ? 'selected' : '' }}>Tiada</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-kai-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('web.pemeriksaan.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header card-header-kai d-flex justify-content-between align-items-center py-3">
        <span class="fs-6">Daftar Pemeriksaan ({{ $pemeriksaanList->total() }} Data Ditemukan)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>No Ref</th>
                        <th>Tanggal</th>
                        <th>Nama Kereta</th>
                        <th>Petugas</th>
                        <th>Mengetahui</th>
                        <th class="text-center">Jml Sarana</th>
                        <th class="text-center">Locotrack</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemeriksaanList as $idx => $p)
                        <tr>
                            <td class="ps-3 text-muted">{{ $pemeriksaanList->firstItem() + $idx }}</td>
                            <td class="fw-semibold text-primary">{{ $p->no_ref ?? 'WPCL-'.$p->id_pemeriksaan }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
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
                                <span class="badge bg-info text-dark">{{ $p->detailPemeriksaan->count() }} Gerbong</span>
                            </td>
                            <td class="text-center">
                                @if($p->locotrack == 'B')
                                    <span class="badge badge-status-baik px-3 py-2">Baik</span>
                                @elseif($p->locotrack == 'R')
                                    <span class="badge badge-status-rusak px-3 py-2">Rusak</span>
                                @else
                                    <span class="badge badge-status-tiada px-3 py-2">Tiada</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('web.pemeriksaan.show', $p->id_pemeriksaan) }}" class="btn btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('web.pemeriksaan.edit', $p->id_pemeriksaan) }}" class="btn btn-outline-warning" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-info" title="Isi No. & Versi Dokumen"
                                            data-bs-toggle="modal" data-bs-target="#dokumenModal{{ $p->id_pemeriksaan }}">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </button>
                                    <a href="{{ route('web.pemeriksaan.print', $p->id_pemeriksaan) }}" target="_blank" class="btn btn-outline-secondary" title="Cetak PDF / Print">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <form action="{{ route('web.pemeriksaan.destroy', $p->id_pemeriksaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-2 d-block mb-2"></i>
                                Tidak ada data pemeriksaan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pemeriksaanList->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $pemeriksaanList->links() }}
        </div>
    @endif
</div>

<!-- Modal Isi No. & Versi Dokumen -->
@foreach($pemeriksaanList as $p)
<div class="modal fade" id="dokumenModal{{ $p->id_pemeriksaan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('web.pemeriksaan.dokumen.update', $p->id_pemeriksaan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-file-earmark-text me-2"></i>Isi No. & Versi Dokumen
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1 small text-muted">
                        {{ $p->no_ref ?? 'WPCL-'.$p->id_pemeriksaan }} — {{ $p->kereta->nama_ka ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No. Dokumen</label>
                        <input type="text" name="no_dokumen" class="form-control" value="{{ old('no_dokumen', $p->no_dokumen) }}" placeholder="Masukkan nomor dokumen...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Versi Dokumen</label>
                        <input type="text" name="versi_dokumen" class="form-control" value="{{ old('versi_dokumen', $p->versi_dokumen) }}" placeholder="Contoh: 1.0, Rev.2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-kai-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
