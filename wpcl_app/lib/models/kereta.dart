import 'sarana.dart';
 
class Kereta {
  final int idKereta;
  final String noKa;
  final String namaKa;
  final String relasi;
  final String jamBerangkat;
  final String jamDatang;
  final List<Sarana> daftarSarana;

  Kereta({
    required this.idKereta,
    required this.noKa,
    required this.namaKa,
    required this.relasi,
    required this.jamBerangkat,
    required this.jamDatang,
    this.daftarSarana = const [],
  });

  factory Kereta.fromJson(Map<String, dynamic> json) {
    List<Sarana> saranaList = [];
    if (json['sarana'] is List) {
      saranaList = (json['sarana'] as List)
          .map((e) => Sarana.fromJson(e as Map<String, dynamic>))
          .toList();
    }

    return Kereta(
      idKereta: json['id_kereta'] is int ? json['id_kereta'] : int.tryParse(json['id_kereta']?.toString() ?? '0') ?? 0,
      noKa: json['no_ka']?.toString() ?? '',
      namaKa: json['nama_ka']?.toString() ?? '',
      relasi: json['relasi']?.toString() ?? '',
      jamBerangkat: json['jam_berangkat']?.toString() ?? '',
      jamDatang: json['jam_datang']?.toString() ?? '',
      daftarSarana: saranaList,
    );
  }

  @override
  String toString() => "$noKa - $namaKa";
}