import 'package:flutter/material.dart';

import '../../models/detail_pemeriksaan.dart';
import '../../models/sarana.dart';
import 'locotrack_screen.dart';

class ChecklistScreen extends StatefulWidget {
  final String namaPetugas;
  final String nipp;
  final int idKereta;

  final String namaKa;
  final String businessArea;
  final String noRef;

  final List<Sarana> daftarSarana;

  const ChecklistScreen({
    super.key,
    required this.namaPetugas,
    required this.nipp,
    required this.idKereta,
    required this.namaKa,
    required this.businessArea,
    required this.noRef,
    required this.daftarSarana,
  });

  @override
  State<ChecklistScreen> createState() => _ChecklistScreenState();
}

class _ChecklistScreenState extends State<ChecklistScreen> {
  int currentIndex = 0;

  late List<DetailPemeriksaan> detailList;

  final TextEditingController keteranganController =
      TextEditingController();

  @override
  void initState() {
    super.initState();

    detailList = widget.daftarSarana
        .map(
          (s) => DetailPemeriksaan(
            idSarana: s.idSarana,
            kodeSarana: s.kodeSarana,
            nomorSarana: s.nomorSarana,
          ),
        )
        .toList();

    keteranganController.text =
        detailList[currentIndex].keterangan;
  }

  DetailPemeriksaan get detail =>
      detailList[currentIndex];

  void prevGerbong() {
    detail.keterangan = keteranganController.text;
    if (currentIndex > 0) {
      setState(() {
        currentIndex--;
        keteranganController.text =
            detailList[currentIndex].keterangan;
      });
    }
  }

  void nextGerbong() {
    detail.keterangan = keteranganController.text;

    if (currentIndex < detailList.length - 1) {
      setState(() {
        currentIndex++;
        keteranganController.text =
            detailList[currentIndex].keterangan;
      });
    } else {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => LocotrackScreen(
            namaPetugas: widget.namaPetugas,
            nipp: widget.nipp,
            idKereta: widget.idKereta,
            namaKa: widget.namaKa,
            businessArea: widget.businessArea,
            noRef: widget.noRef,
            detailList: detailList,
          ),
        ),
      );
    }
  }

  // =========================================================
  // COMPACT RADIO — inline B/R/T tiles
  // =========================================================

  Widget compactRadio({
    required String label,
    required String value,
    required Function(String) onChanged,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(
        children: [
          SizedBox(
            width: 110,
            child: Text(
              label,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
          _miniTile("BAIK", value, Colors.green, onChanged),
          const SizedBox(width: 4),
          _miniTile("RUSAK", value, Colors.red, onChanged),
          const SizedBox(width: 4),
          _miniTile("TIADA", value, Colors.amber.shade700, onChanged),
        ],
      ),
    );
  }

  Widget _miniTile(
    String val,
    String current,
    Color color,
    Function(String) onChanged,
  ) {
    final selected = current == val;
    return Expanded(
      child: InkWell(
        onTap: () {
          onChanged(val);
          setState(() {});
        },
        borderRadius: BorderRadius.circular(4),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 6),
          decoration: BoxDecoration(
            color: selected
                ? color.withValues(alpha: 0.15)
                : Colors.grey.shade50,
            border: Border.all(
              color: selected ? color : Colors.grey.shade300,
              width: selected ? 1.5 : 1,
            ),
            borderRadius: BorderRadius.circular(4),
          ),
          child: Text(
            val,
            style: TextStyle(
              fontSize: 12,
              fontWeight:
                  selected ? FontWeight.bold : FontWeight.normal,
              color: selected ? color : Colors.black54,
            ),
            textAlign: TextAlign.center,
          ),
        ),
      ),
    );
  }

  // =========================================================
  // CATEGORY CARD
  // =========================================================

  Widget categoryCard({
    required String title,
    required IconData icon,
    required List<Widget> children,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, size: 16, color: Colors.blue),
                const SizedBox(width: 6),
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                    color: Colors.blueAccent,
                  ),
                ),
              ],
            ),
            const Divider(height: 12),
            ...children,
          ],
        ),
      ),
    );
  }

  @override
  void dispose() {
    keteranganController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Checklist Pemeriksaan"),
      ),
      body: Column(
        children: [
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(12, 8, 12, 0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // HEADER GERBONG
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade50,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Row(
                      children: [
                        CircleAvatar(
                          radius: 16,
                          backgroundColor: Colors.blue,
                          child: Text(
                            "${currentIndex + 1}",
                            style: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 13,
                            ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                "Gerbong ${currentIndex + 1} / ${detailList.length}",
                                style: TextStyle(
                                  fontSize: 11,
                                  color: Colors.grey.shade600,
                                ),
                              ),
                              Text(
                                "${detail.kodeSarana} ${detail.nomorSarana}",
                                style: const TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 8),

                  // ==========================================
                  // 1. CCTV
                  // ==========================================
                  categoryCard(
                    title: "CCTV",
                    icon: Icons.videocam,
                    children: [
                      compactRadio(
                        label: "Berfungsi",
                        value: detail.cctv,
                        onChanged: (v) => detail.cctv = v,
                      ),
                      compactRadio(
                        label: "Terbackup",
                        value: detail.backup,
                        onChanged: (v) => detail.backup = v,
                      ),
                    ],
                  ),

                  // ==========================================
                  // 2. PIDS LUAR
                  // ==========================================
                  categoryCard(
                    title: "PIDS Luar",
                    icon: Icons.tv,
                    children: [
                      compactRadio(
                        label: "Sisi A",
                        value: detail.sisiA,
                        onChanged: (v) => detail.sisiA = v,
                      ),
                      compactRadio(
                        label: "Sisi E",
                        value: detail.sisiE,
                        onChanged: (v) => detail.sisiE = v,
                      ),
                    ],
                  ),

                  // ==========================================
                  // 3. PIDS DALAM
                  // ==========================================
                  categoryCard(
                    title: "PIDS Dalam",
                    icon: Icons.tv,
                    children: [
                      compactRadio(
                        label: "TD Kecil",
                        value: detail.tdKecil,
                        onChanged: (v) => detail.tdKecil = v,
                      ),
                      compactRadio(
                        label: "TD Besar",
                        value: detail.tdBesar,
                        onChanged: (v) => detail.tdBesar = v,
                      ),
                    ],
                  ),

                  // ==========================================
                  // 4. WIFI
                  // ==========================================
                  categoryCard(
                    title: "WiFi",
                    icon: Icons.wifi,
                    children: [
                      compactRadio(
                        label: "Berfungsi",
                        value: detail.wifi,
                        onChanged: (v) => detail.wifi = v,
                      ),
                    ],
                  ),

                  // ==========================================
                  // 5. CATATAN
                  // ==========================================
                  Card(
                    margin: const EdgeInsets.only(bottom: 8),
                    child: Padding(
                      padding: const EdgeInsets.all(10),
                      child: TextField(
                        controller: keteranganController,
                        maxLines: 2,
                        style: const TextStyle(fontSize: 13),
                        decoration: const InputDecoration(
                          labelText: "Catatan",
                          hintText: "Catatan khusus gerbong ini...",
                          border: OutlineInputBorder(),
                          isDense: true,
                          contentPadding: EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 8,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

          // ==========================================
          // NAVIGASI — fixed di bawah
          // ==========================================
          Container(
            padding: const EdgeInsets.fromLTRB(12, 8, 12, 12),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.05),
                  blurRadius: 4,
                  offset: const Offset(0, -2),
                ),
              ],
            ),
            child: Row(
              children: [
                if (currentIndex > 0) ...[
                  Expanded(
                    child: SizedBox(
                      height: 44,
                      child: OutlinedButton(
                        style: OutlinedButton.styleFrom(
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        onPressed: prevGerbong,
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.arrow_back, size: 16),
                            SizedBox(width: 4),
                            Text(
                              "Sebelumnya",
                              style: TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                ],
                Expanded(
                  child: SizedBox(
                    height: 44,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      onPressed: nextGerbong,
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Flexible(
                            child: Text(
                              currentIndex == detailList.length - 1
                                  ? "Lanjut ke Locotrack"
                                  : "Gerbong ${currentIndex + 2}/${detailList.length}",
                              style: const TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                          const SizedBox(width: 4),
                          Icon(
                            currentIndex == detailList.length - 1
                                ? Icons.check_circle
                                : Icons.arrow_forward,
                            size: 16,
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
