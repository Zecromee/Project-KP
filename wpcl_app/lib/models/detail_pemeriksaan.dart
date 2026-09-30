class DetailPemeriksaan {
  final int idSarana;
  final String kodeSarana;
  final String nomorSarana;

  String pidsLuar;
  String pidsDalam;
  String cctv;
  String wifi;
  String backup;
  String sisiA;
  String sisiE;
  String tdKecil;
  String tdBesar;
  String keterangan;

  DetailPemeriksaan({
    required this.idSarana,
    required this.kodeSarana,
    required this.nomorSarana,
    this.pidsLuar = "B",
    this.pidsDalam = "B",
    this.cctv = "B",
    this.wifi = "B",
    this.backup = "B",
    this.sisiA = "B",
    this.sisiE = "B",
    this.tdKecil = "B",
    this.tdBesar = "B",
    this.keterangan = "",
  });

  //============================================================
  // PARSE DARI RESPONSE API (dipakai untuk fitur riwayat)
  //
  // CATATAN: sesuaikan key "kode_sarana" / "nomor_sarana" kalau
  // backend mengirimnya lewat relasi, misal "sarana.kode".
  //============================================================

  factory DetailPemeriksaan.fromJson(Map<String, dynamic> json) {
    return DetailPemeriksaan(
      idSarana: json['id_sarana'] as int? ?? 0,
      kodeSarana: json['kode_sarana']?.toString() ?? '',
      nomorSarana: json['nomor_sarana']?.toString() ?? '-',
      pidsLuar: json['pids_luar']?.toString() ?? 'B',
      pidsDalam: json['pids_dalam']?.toString() ?? 'B',
      cctv: json['cctv']?.toString() ?? 'B',
      wifi: json['wifi']?.toString() ?? 'B',
      backup: json['backup']?.toString() ?? 'B',
      sisiA: json['sisi_a']?.toString() ?? 'B',
      sisiE: json['sisi_e']?.toString() ?? 'B',
      tdKecil: json['td_kecil']?.toString() ?? 'B',
      tdBesar: json['td_besar']?.toString() ?? 'B',
      keterangan: json['keterangan']?.toString() ?? '',
    );
  }
}