import 'package:flutter/material.dart';

import '../../api/api_service.dart';
import '../../models/detail_pemeriksaan.dart';
import '../../models/pejabat.dart';
import '../home/home_screen.dart';
import '../../services/pdf_service.dart';

class LocotrackScreen extends StatefulWidget {
  final String namaPetugas;
  final String nipp;
  final int idKereta;
  final String namaKa;
  final String businessArea;
  final String noRef;
  final List<DetailPemeriksaan> detailList;

  const LocotrackScreen({
    super.key,
    required this.namaPetugas,
    required this.nipp,
    required this.idKereta,
    required this.detailList,
    required this.namaKa,
    required this.businessArea,
    required this.noRef,
  });

  @override
  State<LocotrackScreen> createState() => _LocotrackScreenState();
}

class _LocotrackScreenState extends State<LocotrackScreen> {
  final ApiService apiService = ApiService();

  final TextEditingController catatanController =
      TextEditingController();
  final TextEditingController catatanKeseluruhanController =
      TextEditingController();
  final TextEditingController locoIdController =
      TextEditingController();
  final TextEditingController nomorSaranaLocoController =
      TextEditingController();

  String locotrack = "B";
  List<Pejabat> daftarPejabat = [];
  Pejabat? pejabatTerpilih;

  bool isLoading = false;
  bool isLoadingPejabat = true;

  @override
  void initState() {
    super.initState();
    loadPejabat();
  }

  Future<void> loadPejabat() async {
    try {
      final list = await apiService.getPejabat();
      if (mounted) {
        setState(() {
          daftarPejabat = list;
          if (list.isNotEmpty) {
            pejabatTerpilih = list.first;
          }
          isLoadingPejabat = false;
        });
      }
    } catch (_) {
      if (mounted) {
        setState(() {
          isLoadingPejabat = false;
        });
      }
    }
  }

  //==========================================================
  // SIMPAN PEMERIKSAAN
  //==========================================================

  Future<void> simpan() async {
    if (isLoading) return;

    setState(() {
      isLoading = true;
    });

    try {
      //------------------------------------------------------
      // SIMPAN KE LARAVEL
      //------------------------------------------------------

      final bool berhasil =
          await apiService.simpanPemeriksaan(
        namaPetugas: widget.namaPetugas,
        nipp: widget.nipp,
        idKereta: widget.idKereta,
        idPejabat: pejabatTerpilih?.idPejabat,
        pejabatNama: pejabatTerpilih?.nama,
        pejabatNipp: pejabatTerpilih?.nipp,
        pejabatJabatan: pejabatTerpilih?.jabatan,
        businessArea: widget.businessArea,
        noRef: widget.noRef,
        locotrack: locotrack,
        locoId: locoIdController.text.trim(),
        nomorSaranaLoco: nomorSaranaLocoController.text.trim(),
        catatan: catatanController.text.trim(),
        catatanKeseluruhan: catatanKeseluruhanController.text.trim(),
        detail: widget.detailList,
      );

      if (!mounted) return;

      //------------------------------------------------------
      // JIKA BERHASIL DISIMPAN
      //------------------------------------------------------

      if (berhasil) {
        //----------------------------------------------------
        // BUAT PDF
        //----------------------------------------------------

        await PdfService().generatePdf(
          businessArea: widget.businessArea,
          noRef: widget.noRef,
          namaKa: widget.namaKa,
          tanggal: DateTime.now().toIso8601String(),
          namaPetugas: widget.namaPetugas,
          nipp: widget.nipp,
          pejabatNama: pejabatTerpilih?.nama,
          pejabatNipp: pejabatTerpilih?.nipp,
          pejabatJabatan: pejabatTerpilih?.jabatan,
          locotrack: locotrack,
          locoId: locoIdController.text.trim(),
          nomorSaranaLoco: nomorSaranaLocoController.text.trim(),
          catatan: catatanController.text.trim(),
          catatanKeseluruhan: catatanKeseluruhanController.text.trim(),
          detailList: widget.detailList,
        );

        if (!mounted) return;

        //----------------------------------------------------
        // NOTIFIKASI
        //----------------------------------------------------

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text(
              "Pemeriksaan berhasil disimpan dan PDF berhasil dibuat.",
            ),
            backgroundColor: Colors.green,
          ),
        );

        await Future.delayed(const Duration(milliseconds: 300));
        if (!mounted) return;

        //----------------------------------------------------
        // KEMBALI KE HOME
        //----------------------------------------------------

        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(
            builder: (_) => const HomeScreen(),
          ),
          (route) => false,
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text(
              "Gagal menyimpan pemeriksaan ke Laravel.",
            ),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            "Terjadi kesalahan:\n$e",
          ),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          isLoading = false;
        });
      }
    }
  }



  //==========================================================
  // DISPOSE
  //==========================================================

  @override
  void dispose() {
    catatanController.dispose();
    catatanKeseluruhanController.dispose();
    locoIdController.dispose();
    nomorSaranaLocoController.dispose();
    super.dispose();
  }

  //==========================================================
  // BUILD
  //==========================================================

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "Pemeriksaan Locotrack",
        ),
      ),

      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),

        child: Column(
          children: [

            //------------------------------------------------
            // PEMERIKSAAN LOCOTRACK (TABEL)
            //------------------------------------------------

            Card(
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      "Pemeriksaan Locotrack",
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Kondisi Locotrack
                    const Text(
                      "Kondisi Locotrack",
                      style: TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        _buildMiniTile("B", "Baik", Colors.green),
                        const SizedBox(width: 8),
                        _buildMiniTile("R", "Rusak", Colors.red),
                        const SizedBox(width: 8),
                        _buildMiniTile("T", "Tiada", Colors.amber.shade700),
                      ],
                    ),

                    const SizedBox(height: 16),

                    // No. Sarana
                    TextField(
                      controller: nomorSaranaLocoController,
                      enabled: !isLoading,
                      decoration: const InputDecoration(
                        labelText: "Nomor Sarana",
                        hintText: "Contoh: 02",
                        border: OutlineInputBorder(),
                      ),
                    ),

                    const SizedBox(height: 16),

                    // ID Loco
                    TextField(
                      controller: locoIdController,
                      enabled: !isLoading,
                      decoration: const InputDecoration(
                        labelText: "ID Loco",
                        hintText: "Contoh: Loco ACB",
                        border: OutlineInputBorder(),
                      ),
                    ),

                    const SizedBox(height: 16),

                    // Keterangan
                    TextField(
                      controller: catatanController,
                      enabled: !isLoading,
                      maxLines: 3,
                      decoration: const InputDecoration(
                        labelText: "Keterangan",
                        hintText: "Catatan khusus locotrack...",
                        border: OutlineInputBorder(),
                        alignLabelWithHint: true,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 16),

            //------------------------------------------------
            // CATATAN KESELURUHAN
            //------------------------------------------------

            Card(
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      "Catatan Keseluruhan",
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: catatanKeseluruhanController,
                      enabled: !isLoading,
                      maxLines: 4,
                      decoration: const InputDecoration(
                        hintText: "Catatan umum keseluruhan pemeriksaan...",
                        border: OutlineInputBorder(),
                        alignLabelWithHint: true,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 16),

            //------------------------------------------------
            // PEJABAT PENANDATANGAN (MENGETAHUI)
            //------------------------------------------------

            Card(
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Row(
                      children: [
                        Icon(Icons.badge_outlined, color: Colors.blue),
                        SizedBox(width: 8),
                        Text(
                          "Mengetahui (Penandatangan)",
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    isLoadingPejabat
                        ? const Center(
                            child: Padding(
                              padding: EdgeInsets.all(12),
                              child: CircularProgressIndicator(),
                            ),
                          )
                        : DropdownButtonFormField<Pejabat>(
                            value: pejabatTerpilih,
                            isExpanded: true,
                            decoration: const InputDecoration(
                              labelText: "Pilih Pejabat",
                              border: OutlineInputBorder(),
                            ),
                            items: daftarPejabat.map((p) {
                              return DropdownMenuItem<Pejabat>(
                                value: p,
                                child: Text(
                                  "${p.nama} - ${p.jabatan}",
                                  overflow: TextOverflow.ellipsis,
                                ),
                              );
                            }).toList(),
                            onChanged: isLoading
                                ? null
                                : (val) {
                                    setState(() {
                                      pejabatTerpilih = val;
                                    });
                                  },
                          ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 30),

            //------------------------------------------------
            // TOMBOL SIMPAN
            //------------------------------------------------

            SizedBox(
              width: double.infinity,
              height: 55,

              child: ElevatedButton(
                onPressed: isLoading ? null : simpan,

                child: isLoading
                    ? const SizedBox(
                        width: 24,
                        height: 24,

                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                        ),
                      )
                    : const Text(
                        "Simpan Pemeriksaan",
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
              ),
            ),

            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  //==========================================================
  // MINI TILE (B/R/T)
  //==========================================================

  Widget _buildMiniTile(String value, String label, Color color) {
    final isSelected = locotrack == value;
    return Expanded(
      child: InkWell(
        onTap: isLoading ? null : () => setState(() => locotrack = value),
        borderRadius: BorderRadius.circular(4),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: isSelected ? color.withValues(alpha: 0.15) : Colors.grey.shade50,
            border: Border.all(
              color: isSelected ? color : Colors.grey.shade300,
              width: isSelected ? 2 : 1,
            ),
            borderRadius: BorderRadius.circular(6),
          ),
          child: Text(
            label,
            style: TextStyle(
              fontSize: 13,
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
              color: isSelected ? color : Colors.black54,
            ),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }
}