class Sarana {
  final int idSarana;
  final int? idKereta;
  final String kodeSarana;
  final String nomorSarana;
  final String? seriSarana;
  final String? depoInduk;

  Sarana({
    required this.idSarana,
    this.idKereta,
    required this.kodeSarana,
    required this.nomorSarana,
    this.seriSarana,
    this.depoInduk,
  });

  factory Sarana.fromJson(Map<String, dynamic> json) {
    return Sarana(
      idSarana: json['id_sarana'] is int ? json['id_sarana'] : int.tryParse(json['id_sarana']?.toString() ?? '0') ?? 0,
      idKereta: json['id_kereta'] is int ? json['id_kereta'] : int.tryParse(json['id_kereta']?.toString() ?? ''),
      kodeSarana: json['kode_sarana']?.toString() ?? '',
      nomorSarana: json['nomor_sarana']?.toString() ?? '',
      seriSarana: json['seri_sarana']?.toString(),
      depoInduk: json['depo_induk']?.toString(),
    );
  }
}