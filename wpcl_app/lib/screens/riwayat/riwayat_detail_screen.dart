import 'package:flutter/material.dart';

import '../../models/pemeriksaan_detail.dart';
import '../../api/api_service.dart';
import '../../services/pdf_service.dart';

class RiwayatDetailScreen extends StatefulWidget {
  final int id;

  const RiwayatDetailScreen({
    super.key,
    required this.id,
  });

  @override
  State<RiwayatDetailScreen> createState() => _RiwayatDetailScreenState();
}

class _RiwayatDetailScreenState extends State<RiwayatDetailScreen> {
  final ApiService apiService = ApiService();

  late Future<PemeriksaanDetail> futureDetail;

  bool isPrinting = false;

  @override
  void initState() {
    super.initState();
    futureDetail = apiService.getDetailPemeriksaan(widget.id);
  }

  //==========================================================
  // CETAK ULANG PDF
  //==========================================================

  Future<void> cetakUlang(PemeriksaanDetail data) async {
    if (isPrinting) return;

    setState(() {
      isPrinting = true;
    });

    try {
      await PdfService().generatePdf(
        businessArea: data.businessArea,
        noRef: data.noRef,
        namaKa: data.namaKa,
        tanggal: data.tanggal,
        namaPetugas: data.namaPetugas,
        nipp: data.nipp,
        pejabatNama: data.pejabatNama,
        pejabatNipp: data.pejabatNipp,
        pejabatJabatan: data.pejabatJabatan,
        locotrack: data.locotrack,
        catatan: data.catatan,
        detailList: data.detail,
      );
    } catch (e) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal membuat PDF:\n$e'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          isPrinting = false;
        });
      }
    }
  }

  //==========================================================
  // BADGE BAIK / RUSAK / TIADA UNTUK TABEL DETAIL
  //==========================================================

  Widget statusBadge(String value) {
    Color color;
    String label;
    switch (value) {
      case 'B':
      case 'Baik':
        color = Colors.green;
        label = 'Baik';
        break;
      case 'R':
      case 'Rusak':
        color = Colors.red;
        label = 'Rusak';
        break;
      case 'T':
      case 'Tiada':
        color = Colors.amber.shade800;
        label = 'Tiada';
        break;
      default:
        color = Colors.grey;
        label = value.isEmpty ? '-' : value;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      alignment: Alignment.center,
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        border: Border.all(color: color),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.bold,
          fontSize: 11,
        ),
      ),
    );
  }

  //==========================================================
  // BARIS IDENTITAS (LABEL : VALUE)
  //==========================================================

  Widget identityRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 110,
            child: Text(
              label,
              style: const TextStyle(
                fontWeight: FontWeight.w600,
                color: Colors.black87,
              ),
            ),
          ),
          const Text(': '),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  //==========================================================
  // BUILD
  //==========================================================

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Riwayat'),
      ),
      body: FutureBuilder<PemeriksaanDetail>(
        future: futureDetail,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Text(
                  'Gagal memuat detail.\n${snapshot.error}',
                  textAlign: TextAlign.center,
                ),
              ),
            );
          }

          final data = snapshot.data!;

          return Column(
            children: [
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      //--------------------------------------
                      // IDENTITAS
                      //--------------------------------------

                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                data.namaKa,
                                style: const TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const Divider(height: 20),
                              identityRow('No Ref', data.noRef),
                              identityRow('Tanggal', data.tanggal),
                              identityRow('Business Area', data.businessArea),
                              identityRow('Petugas', data.namaPetugas),
                              identityRow('NIPP', data.nipp),
                              if (data.pejabatNama != null && data.pejabatNama!.isNotEmpty)
                                identityRow(
                                  'Mengetahui',
                                  '${data.pejabatNama} (${data.pejabatJabatan ?? '-'} - ${data.pejabatNipp ?? '-'})',
                                ),
                              if (data.catatan.isNotEmpty)
                                identityRow('Catatan', data.catatan),
                            ],
                          ),
                        ),
                      ),

                      const SizedBox(height: 16),

                      const Text(
                        'Detail Per Sarana',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),

                      const SizedBox(height: 8),

                      //--------------------------------------
                      // TABEL DETAIL SARANA
                      //--------------------------------------

                      SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: DataTable(
                          headingRowColor: WidgetStateProperty.all(
                            Colors.grey.shade200,
                          ),
                          columnSpacing: 18,
                          columns: const [
                            DataColumn(label: Text('No')),
                            DataColumn(label: Text('No. Sarana')),
                            DataColumn(label: Text('CCTV\nBerfungsi')),
                            DataColumn(label: Text('CCTV\nTerbackup')),
                            DataColumn(label: Text('PIDS Luar\nSisi A')),
                            DataColumn(label: Text('PIDS Luar\nSisi E')),
                            DataColumn(label: Text('PIDS Dalam\nTD Kecil')),
                            DataColumn(label: Text('PIDS Dalam\nTD Besar')),
                            DataColumn(label: Text('WIFI\nBerfungsi')),
                            DataColumn(label: Text('Note')),
                          ],
                          rows: List.generate(
                            data.detail.length,
                            (index) {
                              final d = data.detail[index];

                              return DataRow(
                                cells: [
                                  DataCell(Text('${index + 1}')),
                                  DataCell(Text(d.nomorSarana)),
                                  DataCell(statusBadge(d.cctv)),
                                  DataCell(statusBadge(d.backup)),
                                  DataCell(statusBadge(d.sisiA)),
                                  DataCell(statusBadge(d.sisiE)),
                                  DataCell(statusBadge(d.tdKecil)),
                                  DataCell(statusBadge(d.tdBesar)),
                                  DataCell(statusBadge(d.wifi)),
                                  DataCell(
                                    ConstrainedBox(
                                      constraints: const BoxConstraints(
                                        maxWidth: 180,
                                      ),
                                      child: Text(
                                        d.keterangan.isEmpty
                                            ? '-'
                                            : d.keterangan,
                                        softWrap: true,
                                      ),
                                    ),
                                  ),
                                ],
                              );
                            },
                          ),
                        ),
                      ),

                      const SizedBox(height: 80),
                    ],
                  ),
                ),
              ),

              //------------------------------------------------
              // TOMBOL CETAK ULANG
              //------------------------------------------------

              SafeArea(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton.icon(
                      onPressed: isPrinting ? null : () => cetakUlang(data),
                      icon: isPrinting
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          : const Icon(Icons.picture_as_pdf),
                      label: Text(
                        isPrinting ? 'Membuat PDF...' : 'Cetak Ulang PDF',
                      ),
                    ),
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}