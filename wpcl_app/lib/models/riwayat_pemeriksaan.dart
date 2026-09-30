//==============================================================
// MODEL: RIWAYAT PEMERIKSAAN (RINGKAS)
//
// Dipakai untuk menampilkan daftar riwayat (ListView).
// Field-nya sengaja ringan, tidak termasuk detail per-sarana
// (detail baru diambil saat user membuka salah satu riwayat).
//==============================================================

class RiwayatPemeriksaan {
  final int id;
  final String noRef;
  final String tanggal;
  final String businessArea;
  final String namaKa;
  final String namaPetugas;
  final String nipp;
  final String? pejabatNama;
  final String? pejabatNipp;
  final String? pejabatJabatan;
  final String locotrack;

  RiwayatPemeriksaan({
    required this.id,
    required this.noRef,
    required this.tanggal,
    required this.businessArea,
    required this.namaKa,
    required this.namaPetugas,
    required this.nipp,
    this.pejabatNama,
    this.pejabatNipp,
    this.pejabatJabatan,
    required this.locotrack,
  });

  factory RiwayatPemeriksaan.fromJson(Map<String, dynamic> json) {
    return RiwayatPemeriksaan(
      id: json['id'] as int,
      noRef: json['no_ref']?.toString() ?? '-',
      tanggal: json['tanggal']?.toString() ?? '-',
      businessArea: json['business_area']?.toString() ?? '-',
      namaKa: json['nama_ka']?.toString() ?? '-',
      namaPetugas: json['nama_petugas']?.toString() ?? '-',
      nipp: json['nipp']?.toString() ?? '-',
      pejabatNama: json['pejabat_nama']?.toString(),
      pejabatNipp: json['pejabat_nipp']?.toString(),
      pejabatJabatan: json['pejabat_jabatan']?.toString(),
      locotrack: json['locotrack']?.toString() ?? '-',
    );
  }
}