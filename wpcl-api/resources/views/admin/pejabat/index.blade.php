@extends('layouts.app')

@section('title', 'Master Data Pejabat Penandatangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--kai-blue);">Master Data Pejabat Penandatangan</h2>
        <p class="text-muted mb-0">Pejabat yang berwenang menandatangani ("Mengetahui") laporan hasil pemeriksaan WPCL</p>
    </div>
    <button type="button" class="btn btn-kai-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPejabat">
        <i class="bi bi-plus-circle me-1"></i> Tambah Pejabat
    </button>
</div>

<div class="card">
    <div class="card-header card-header-kai py-3">
        <span class="fs-6">Daftar Pejabat Aktif</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" width="50">No</th>
                        <th>Nama Pejabat</th>
                        <th>NIPP</th>
                        <th>Jabatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pejabatList as $idx => $p)
                        <tr>
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $p->nama }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->nipp }}</span></td>
                            <td class="fw-semibold">{{ $p->jabatan }}</td>
                            <td class="text-center">
                                @if($p->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditPejabat{{ $p->id_pejabat }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('web.pejabat.destroy', $p->id_pejabat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pejabat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit Pejabat -->
                        <div class="modal fade" id="modalEditPejabat{{ $p->id_pejabat }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('web.pejabat.update', $p->id_pejabat) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Pejabat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Pejabat</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">NIPP</label>
                                                <input type="text" name="nipp" class="form-control" value="{{ $p->nipp }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Jabatan</label>
                                                <input type="text" name="jabatan" class="form-control" value="{{ $p->jabatan }}" required>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $p->id_pejabat }}" {{ $p->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active{{ $p->id_pejabat }}">Status Aktif</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-kai-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data pejabat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Pejabat -->
<div class="modal fade" id="modalTambahPejabat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('web.pejabat.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Pejabat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pejabat</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pitra Argehermanu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIPP</label>
                        <input type="text" name="nipp" class="form-control" placeholder="Contoh: 46002" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Manager IT / Assman IT Support 1" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeNew" checked>
                        <label class="form-check-label" for="activeNew">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-kai-primary">Simpan Pejabat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
