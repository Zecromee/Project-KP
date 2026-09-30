import 'detail_pemeriksaan.dart';

//==============================================================
// MODEL: DETAIL PEMERIKSAAN (LENGKAP)
//
// Dipakai di halaman detail riwayat. Berisi data identitas
// pemeriksaan + seluruh baris detail per-sarana, supaya bisa
// langsung dipakai ulang untuk generate PDF (PdfService).
//==============================================================

class PemeriksaanDetail {
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
  final String catatan;
  final List<DetailPemeriksaan> detail;

  PemeriksaanDetail({
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
    required this.catatan,
    required this.detail,
  });

  factory PemeriksaanDetail.fromJson(Map<String, dynamic> json) {
    final List rawDetail = json['detail'] as List? ?? [];

    return PemeriksaanDetail(
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
      catatan: json['catatan']?.toString() ?? '',
      detail: rawDetail
          .map((e) => DetailPemeriksaan.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }
}