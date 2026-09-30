@extends('layouts.app')

@section('title', 'Master Stamformasi Sarana Kereta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--kai-blue);">Master Stamformasi & Sarana Kereta</h2>
        <p class="text-muted mb-0">Kelola dan hapus rangkaian sarana (gerbong) aktif yang terdaftar di sistem</p>
    </div>
    <div class="d-flex gap-2">
        @if($daftarKereta->count() > 0)
            <form action="{{ route('web.stamformasi.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA data stamformasi dan sarana kereta? Seluruh formasi akan di-reset bersih!')">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash3 me-1"></i> Reset / Hapus Semua Formasi
                </button>
            </form>
        @endif
        <a href="{{ route('web.stamformasi.import') }}" class="btn btn-kai-orange">
            <i class="bi bi-file-earmark-arrow-up me-1"></i> Import / Update Stamformasi
        </a>
    </div>
</div>

<div class="row g-4">
    @forelse($daftarKereta as $k)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-top border-4 border-primary">
                <div class="card-header bg-white pb-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary fs-6">{{ $k->no_ka }}</span>
                            <h5 class="fw-bold text-dark mt-2 mb-1">{{ $k->nama_ka }}</h5>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $k->relasi ?? '-' }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-primary border d-block mb-2">{{ $k->sarana->count() }} Sarana</span>
                            <form action="{{ route('web.stamformasi.kereta.destroy', $k->id_kereta) }}" method="POST" onsubmit="return confirm('Hapus seluruh rangkaian {{ $k->no_ka }} - {{ $k->nama_ka }} beserta {{ $k->sarana->count() }} sarananya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" title="Hapus Kereta Ini">
                                    <i class="bi bi-trash me-1"></i> Hapus KA
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-sm table-striped mb-0 small align-middle">
                            <thead>
                                <tr>
                                    <th width="25">#</th>
                                    <th>Kode & No. Sarana</th>
                                    <th>Seri</th>
                                    <th>Depo</th>
                                    <th width="40" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($k->sarana as $idx => $s)
                                    <tr>
                                        <td class="text-muted">{{ $idx + 1 }}</td>
                                        <td class="fw-bold">{{ $s->kode_sarana }} {{ $s->nomor_sarana }}</td>
                                        <td>{{ $s->seri_sarana ?? '-' }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $s->depo_induk ?? 'YK' }}</span></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary py-0 px-1" style="font-size: 11px;" title="Edit Sarana"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditSarana{{ $s->id_sarana }}"
                                                    data-kode="{{ $s->kode_sarana }}" data-nomor="{{ $s->nomor_sarana }}" data-seri="{{ $s->seri_sarana }}" data-depo="{{ $s->depo_induk }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('web.stamformasi.sarana.destroy', $s->id_sarana) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sarana {{ $s->kode_sarana }} {{ $s->nomor_sarana }} dari rangkaian ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger py-0 px-1" style="font-size: 11px;" title="Hapus Sarana Ini">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-2">Belum ada sarana terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 text-muted small d-flex justify-content-between align-items-center">
                    <div>
                        <span><i class="bi bi-clock me-1"></i>{{ $k->jam_berangkat ?? '-' }}</span>
                        <span class="mx-1">&rarr;</span>
                        <span>{{ $k->jam_datang ?? '-' }}</span>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahSarana{{ $k->id_kereta }}">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Sarana
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Sarana Manual untuk Kereta Ini -->
        <div class="modal fade" id="modalTambahSarana{{ $k->id_kereta }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('web.stamformasi.sarana.store', $k->id_kereta) }}" method="POST">
                        @csrf
                        <div class="modal-header card-header-kai">
                            <h5 class="modal-title fs-6">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Sarana ke {{ $k->no_ka }} - {{ $k->nama_ka }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Kode Sarana</label>
                                    <input type="text" name="kode_sarana" class="form-control" placeholder="Contoh: K1, K1lux, M1, P, MP3" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nomor Sarana</label>
                                    <input type="text" name="nomor_sarana" class="form-control" placeholder="Contoh: 02326 atau 0 23 26" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Seri Sarana (Opsional)</label>
                                    <input type="text" name="seri_sarana" class="form-control" placeholder="Contoh: SS NG, SS, Eksekutif">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Depo Induk</label>
                                    <input type="text" name="depo_induk" class="form-control" value="YK" placeholder="Contoh: YK, SLO, JAK, BD">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-kai-primary btn-sm fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Sarana
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Belum ada rangkaian kereta terdaftar. Silakan lakukan import stamformasi.
        </div>
    @endforelse
</div>

<!-- Edit Sarana Modals -->
@foreach($daftarKereta as $k)
    @foreach($k->sarana as $s)
        <div class="modal fade" id="modalEditSarana{{ $s->id_sarana }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('web.stamformasi.sarana.update', $s->id_sarana) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header card-header-kai">
                            <h5 class="modal-title fs-6">
                                <i class="bi bi-pencil-square me-1"></i> Edit Sarana {{ $s->kode_sarana }} {{ $s->nomor_sarana }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Kode Sarana</label>
                                    <input type="text" name="kode_sarana" class="form-control" value="{{ $s->kode_sarana }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nomor Sarana</label>
                                    <input type="text" name="nomor_sarana" class="form-control" value="{{ $s->nomor_sarana }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Seri Sarana</label>
                                    <input type="text" name="seri_sarana" class="form-control" value="{{ $s->seri_sarana }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Depo Induk</label>
                                    <input type="text" name="depo_induk" class="form-control" value="{{ $s->depo_induk }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-kai-primary btn-sm fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
@endsection
