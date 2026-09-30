import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../models/detail_pemeriksaan.dart';
import '../models/kereta.dart';
import '../models/sarana.dart';
import '../models/pejabat.dart';
import '../models/riwayat_pemeriksaan.dart';
import '../models/pemeriksaan_detail.dart';

class ApiService {
  // ==========================================================
  // BASE URL
  // Default: IP Wi-Fi Laptop (dapat diubah dinamis via tombol Pengaturan di HP)
  // ==========================================================

  static const String defaultBaseUrl = "http://192.168.100.57:8000/api";
  static String baseUrl = defaultBaseUrl;

  // Token API yang sama dengan API_TOKEN di .env Laravel
  // Wajib dikirim via header 'X-API-Token' ke semua endpoint API
  static const String apiToken = "wpcl_dev_c8636b9591580eb552713fad605c6d95e7530c2c4e07fba6";

  static Future<void> init() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final savedUrl = prefs.getString('api_base_url');
      if (savedUrl != null && savedUrl.isNotEmpty) {
        baseUrl = savedUrl;
      }
    } catch (_) {}
  }

  static Future<void> saveBaseUrl(String newUrl) async {
    String cleanUrl = newUrl.trim();
    if (cleanUrl.endsWith('/')) {
      cleanUrl = cleanUrl.substring(0, cleanUrl.length - 1);
    }
    if (!cleanUrl.endsWith('/api')) {
      cleanUrl = '$cleanUrl/api';
    }
    baseUrl = cleanUrl;
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('api_base_url', baseUrl);
    } catch (_) {}
  }

  final Dio dio = Dio()
    ..interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          options.headers['X-API-Token'] = apiToken;
          handler.next(options);
        },
      ),
    );

  // ==========================================================
  // GET TANGGAL & WAKTU SEKARANG
  //
  // Format:
  // YYYY-MM-DD HH:mm:ss
  //
  // Contoh:
  // 2026-08-18 14:35:27
  // ==========================================================

  String getTanggalSekarang() {
    final now = DateTime.now();

    final year = now.year.toString().padLeft(4, '0');
    final month = now.month.toString().padLeft(2, '0');
    final day = now.day.toString().padLeft(2, '0');

    final hour = now.hour.toString().padLeft(2, '0');
    final minute = now.minute.toString().padLeft(2, '0');
    final second = now.second.toString().padLeft(2, '0');

    return '$year-$month-$day $hour:$minute:$second';
  }

  // ==========================================================
  // GET KERETA
  // ==========================================================

  Future<List<Kereta>> getKereta() async {
    final response = await dio.get(
      "$baseUrl/kereta",
    );

    final List data = response.data["data"];

    return data
        .map((e) => Kereta.fromJson(e))
        .toList();
  }

  // ==========================================================
  // GET PEJABAT PENANDATANGAN
  // ==========================================================

  Future<List<Pejabat>> getPejabat() async {
    final response = await dio.get(
      "$baseUrl/pejabat",
    );

    final List data = response.data["data"];

    return data
        .map((e) => Pejabat.fromJson(e))
        .toList();
  }

  // ==========================================================
  // GET SARANA BY KERETA
  // ==========================================================

  Future<List<Sarana>> getSaranaByKereta(int idKereta) async {
    final response = await dio.get(
      "$baseUrl/kereta/$idKereta/sarana",
    );

    final List data = response.data["data"];

    return data
        .map((e) => Sarana.fromJson(e))
        .toList();
  }

  // ==========================================================
  // SEARCH SARANA
  // ==========================================================

  Future<List<Sarana>> searchSarana(
    String keyword,
  ) async {
    final response = await dio.get(
      "$baseUrl/sarana/search",
      queryParameters: {
        "keyword": keyword,
      },
    );

    final List data = response.data["data"];

    return data
        .map((e) => Sarana.fromJson(e))
        .toList();
  }

  // ==========================================================
  // SIMPAN PEMERIKSAAN
  // ==========================================================

  Future<bool> simpanPemeriksaan({
    required String namaPetugas,
    required String nipp,
    required int idKereta,
    int? idPejabat,
    String? pejabatNama,
    String? pejabatNipp,
    String? pejabatJabatan,
    required String businessArea,
    required String noRef,
    required String locotrack,
    String? locoId,
    String? nomorSaranaLoco,
    required String catatan,
    String? catatanKeseluruhan,
    required List<DetailPemeriksaan> detail,
  }) async {
    // --------------------------------------------------------
    // Ambil tanggal + waktu saat tombol simpan ditekan
    // --------------------------------------------------------

    final tanggalSekarang = getTanggalSekarang();

    // --------------------------------------------------------
    final Map<String, dynamic> payload = {
      "nama_petugas": namaPetugas,
      "nipp": nipp,
      "id_kereta": idKereta,
      "tanggal": tanggalSekarang,
      "business_area": businessArea,
      "no_ref": noRef,
      "locotrack": locotrack,
      "catatan": catatan,
      "detail": detail.asMap().entries.map((entry) {
        final index = entry.key;
        final d = entry.value;

        return {
          "id_sarana": d.idSarana,
          "kode_sarana": d.kodeSarana,
          "nomor_sarana": d.nomorSarana,
          "urutan": index + 1,
          "pids_luar": d.pidsLuar,
          "pids_dalam": d.pidsDalam,
          "cctv": d.cctv,
          "wifi": d.wifi,
          "backup": d.backup,
          "sisi_a": d.sisiA,
          "sisi_e": d.sisiE,
          "td_kecil": d.tdKecil,
          "td_besar": d.tdBesar,
          "keterangan": d.keterangan,
        };
      }).toList(),
    };

    if (idPejabat != null) payload["id_pejabat"] = idPejabat;
    if (pejabatNama != null) payload["pejabat_nama"] = pejabatNama;
    if (pejabatNipp != null) payload["pejabat_nipp"] = pejabatNipp;
    if (pejabatJabatan != null) payload["pejabat_jabatan"] = pejabatJabatan;
    if (locoId != null && locoId.isNotEmpty) payload["loco_id"] = locoId;
    if (nomorSaranaLoco != null && nomorSaranaLoco.isNotEmpty) payload["nomor_sarana_loco"] = nomorSaranaLoco;
    if (catatanKeseluruhan != null && catatanKeseluruhan.isNotEmpty) payload["catatan_keseluruhan"] = catatanKeseluruhan;

    final response = await dio.post(
      "$baseUrl/pemeriksaan",
      data: payload,
    );

    return response.statusCode == 200 ||
        response.statusCode == 201;
  }



  // ==========================================================
  // RIWAYAT PEMERIKSAAN
  //
  // GET /pemeriksaan
  //
  // Mengambil daftar semua pemeriksaan yang sudah tersimpan.
  // ==========================================================

  Future<List<RiwayatPemeriksaan>>
      getRiwayatPemeriksaan() async {
    final response = await dio.get(
      "$baseUrl/pemeriksaan",
    );

    final List data = response.data["data"];

    return data
        .map((e) => RiwayatPemeriksaan.fromJson(e))
        .toList();
  }

  // ==========================================================
  // DETAIL PEMERIKSAAN
  //
  // GET /pemeriksaan/{id}
  //
  // Mengambil:
  //
  // - Identitas pemeriksaan
  // - Nama KA
  // - Tanggal
  // - Business Area
  // - Petugas
  // - NIPP
  // - Locotrack
  // - Catatan
  // - Detail setiap sarana
  // ==========================================================

  Future<PemeriksaanDetail>
      getDetailPemeriksaan(int id) async {
    final response = await dio.get(
      "$baseUrl/pemeriksaan/$id",
    );

    return PemeriksaanDetail.fromJson(
      response.data["data"]
          as Map<String, dynamic>,
    );
  }
}