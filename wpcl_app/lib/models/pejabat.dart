class Pejabat {
  final int idPejabat;
  final String nama;
  final String nipp;
  final String jabatan;

  Pejabat({
    required this.idPejabat,
    required this.nama,
    required this.nipp,
    required this.jabatan,
  });

  factory Pejabat.fromJson(Map<String, dynamic> json) {
    return Pejabat(
      idPejabat: json['id_pejabat'] is int
          ? json['id_pejabat']
          : int.tryParse(json['id_pejabat']?.toString() ?? '0') ?? 0,
      nama: json['nama']?.toString() ?? '',
      nipp: json['nipp']?.toString() ?? '',
      jabatan: json['jabatan']?.toString() ?? '',
    );
  }

  @override
  String toString() => "$nama ($jabatan - $nipp)";
}
