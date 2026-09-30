import 'package:flutter/material.dart';

import '../../api/api_service.dart';
import '../../models/kereta.dart';
import '../../models/sarana.dart';
import '../../theme/kai_colors.dart';
import '../checklist/checklist_screen.dart';

class IdentitasScreen extends StatefulWidget {
  const IdentitasScreen({super.key});

  @override
  State<IdentitasScreen> createState() => _IdentitasScreenState();
}

class _IdentitasScreenState extends State<IdentitasScreen> {
  final namaController = TextEditingController();
  final nippController = TextEditingController();
  final businessAreaController = TextEditingController();
  final noRefController = TextEditingController();

  final ApiService apiService = ApiService();

  List<Kereta> daftarKereta = [];
  Kereta? keretaTerpilih;
  List<Sarana> daftarSaranaOtomatis = [];
  Set<int> selectedSaranaIndices = {};

  bool isLoading = true;
  bool isLoadingSarana = false;

  @override
  void initState() {
    super.initState();

    businessAreaController.text = "DAOP 6";

    final now = DateTime.now();

    noRefController.text =
        "WPCL-${now.year}${now.month.toString().padLeft(2, '0')}${now.day.toString().padLeft(2, '0')}";

    loadKereta();
  }

  Future<void> loadKereta() async {
    try {
      daftarKereta = await apiService.getKereta();

      setState(() {
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("Gagal mengambil data kereta\n$e"),
        ),
      );
    }
  }

  Future<void> onKeretaChanged(Kereta? kereta) async {
    setState(() {
      keretaTerpilih = kereta;
      daftarSaranaOtomatis = [];
      selectedSaranaIndices.clear();
    });

    if (kereta == null) return;

    if (kereta.daftarSarana.isNotEmpty) {
      setState(() {
        daftarSaranaOtomatis = List.from(kereta.daftarSarana);
        selectedSaranaIndices =
            List.generate(daftarSaranaOtomatis.length, (i) => i).toSet();
      });
      return;
    }

    setState(() {
      isLoadingSarana = true;
    });

    try {
      final saranaList = await apiService.getSaranaByKereta(kereta.idKereta);
      if (mounted) {
        setState(() {
          daftarSaranaOtomatis = saranaList;
          selectedSaranaIndices =
              List.generate(saranaList.length, (i) => i).toSet();
          isLoadingSarana = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          isLoadingSarana = false;
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Gagal memuat sarana kereta:\n$e"),
          ),
        );
      }
    }
  }

  void toggleSarana(int index) {
    setState(() {
      if (selectedSaranaIndices.contains(index)) {
        selectedSaranaIndices.remove(index);
      } else {
        selectedSaranaIndices.add(index);
      }
    });
  }

  void selectAllSarana() {
    setState(() {
      selectedSaranaIndices =
          List.generate(daftarSaranaOtomatis.length, (i) => i).toSet();
    });
  }

  void deselectAllSarana() {
    setState(() {
      selectedSaranaIndices.clear();
    });
  }

  void showDialogTambahSarana() {
    if (keretaTerpilih == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Pilih kereta terlebih dahulu sebelum menambah sarana."),
        ),
      );
      return;
    }

    String selectedKode = "K1";
    final nomorCtrl = TextEditingController();
    final seriCtrl = TextEditingController(text: "Eksekutif SS NG");
    final depoCtrl = TextEditingController(text: "YK");

    showDialog(
      context: context,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              title: const Row(
                children: [
                  Icon(Icons.add_circle, color: KaiColors.navy),
                  SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      "Tambah Sarana Luar Formasi",
                      style: TextStyle(fontSize: 18),
                    ),
                  ),
                ],
              ),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text(
                      "Masukkan nomor gerbong tambahan/cadangan yang dirangkaikan ke KA ini.",
                      style: TextStyle(fontSize: 13, color: Colors.black54),
                    ),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<String>(
                      initialValue: selectedKode,
                      isExpanded: true,
                      decoration: const InputDecoration(
                        labelText: "Kode Sarana",
                        border: OutlineInputBorder(),
                      ),
                      items: const [
                        DropdownMenuItem(value: "K1", child: Text("K1 (Eksekutif)")),
                        DropdownMenuItem(value: "K1lux", child: Text("K1lux (Luxury)")),
                        DropdownMenuItem(value: "K3", child: Text("K3 (Ekonomi)")),
                        DropdownMenuItem(value: "M1", child: Text("M1 (Kereta Makan)")),
                        DropdownMenuItem(value: "MP3", child: Text("MP3 (Makan & Pembangkit)")),
                        DropdownMenuItem(value: "P", child: Text("P (Pembangkit)")),
                        DropdownMenuItem(value: "KP3", child: Text("KP3")),
                      ],
                      onChanged: (val) {
                        if (val != null) {
                          setDialogState(() {
                            selectedKode = val;
                            if (val == "K1") seriCtrl.text = "Eksekutif SS NG";
                            if (val == "K1lux") seriCtrl.text = "Eksekutif Luxury SS NG";
                            if (val == "K3") seriCtrl.text = "Ekonomi SS NG";
                            if (val == "M1") seriCtrl.text = "Kereta Makan SS NG";
                            if (val == "P") seriCtrl.text = "Pembangkit SS NG";
                          });
                        }
                      },
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: nomorCtrl,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: "Nomor Sarana",
                        hintText: "Contoh: 02450 / 02326",
                        border: OutlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: seriCtrl,
                      decoration: const InputDecoration(
                        labelText: "Seri Sarana",
                        hintText: "Contoh: Eksekutif SS NG",
                        border: OutlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 12),
                    TextField(
                      controller: depoCtrl,
                      decoration: const InputDecoration(
                        labelText: "Depo Induk",
                        hintText: "Contoh: YK / BD / JAK",
                        border: OutlineInputBorder(),
                      ),
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(ctx),
                  child: const Text("Batal"),
                ),
                ElevatedButton(
                  onPressed: () {
                    final nomor = nomorCtrl.text.trim().replaceAll(' ', '');
                    if (nomor.isEmpty) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text("Nomor sarana tidak boleh kosong."),
                        ),
                      );
                      return;
                    }

                    final newSarana = Sarana(
                      idSarana: 0,
                      idKereta: keretaTerpilih!.idKereta,
                      kodeSarana: selectedKode,
                      nomorSarana: nomor,
                      seriSarana: seriCtrl.text.trim(),
                      depoInduk: depoCtrl.text.trim().toUpperCase(),
                    );

                    setState(() {
                      daftarSaranaOtomatis.add(newSarana);
                      selectedSaranaIndices.add(daftarSaranaOtomatis.length - 1);
                    });

                    Navigator.pop(ctx);

                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text("Sarana $selectedKode $nomor berhasil ditambahkan ke rangkaian."),
                        backgroundColor: KaiColors.baik,
                      ),
                    );
                  },
                  child: const Text("Tambahkan"),
                ),
              ],
            );
          },
        );
      },
    );
  }

  @override
  void dispose() {
    namaController.dispose();
    nippController.dispose();
    businessAreaController.dispose();
    noRefController.dispose();

    super.dispose();
  }

  void lanjutKePemeriksaan() {
    if (namaController.text.trim().isEmpty ||
        nippController.text.trim().isEmpty ||
        keretaTerpilih == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Lengkapi seluruh data identitas dan pilih kereta terlebih dahulu."),
        ),
      );
      return;
    }

    if (daftarSaranaOtomatis.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Kereta yang dipilih belum memiliki nomor sarana terdaftar."),
        ),
      );
      return;
    }

    if (selectedSaranaIndices.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Silakan pilih / centang minimal 1 sarana yang akan diperiksa."),
        ),
      );
      return;
    }

    // Ambil hanya sarana yang dicentang (aktif)
    final saranaYangDiperiksa = daftarSaranaOtomatis
        .asMap()
        .entries
        .where((entry) => selectedSaranaIndices.contains(entry.key))
        .map((entry) => entry.value)
        .toList();

    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => ChecklistScreen(
          namaPetugas: namaController.text.trim(),
          nipp: nippController.text.trim(),
          idKereta: keretaTerpilih!.idKereta,
          namaKa: keretaTerpilih!.namaKa,
          businessArea: businessAreaController.text,
          noRef: noRefController.text,
          daftarSarana: saranaYangDiperiksa,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Identitas Pemeriksaan"),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "Data Petugas",
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w700,
                color: KaiColors.navy,
              ),
            ),
            const SizedBox(height: 10),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    TextField(
                      controller: namaController,
                      decoration: const InputDecoration(
                        labelText: "Nama Petugas",
                        prefixIcon: Icon(Icons.person_outline, color: KaiColors.navy),
                      ),
                    ),
                    const SizedBox(height: 14),
                    TextField(
                      controller: nippController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: "NIPP",
                        prefixIcon: Icon(Icons.badge_outlined, color: KaiColors.navy),
                      ),
                    ),
                    const SizedBox(height: 14),
                    TextField(
                      controller: businessAreaController,
                      readOnly: true,
                      decoration: const InputDecoration(
                        labelText: "Business Area",
                        prefixIcon: Icon(Icons.apartment_outlined, color: KaiColors.navy),
                      ),
                    ),
                    const SizedBox(height: 14),
                    TextField(
                      controller: noRefController,
                      readOnly: true,
                      decoration: const InputDecoration(
                        labelText: "No Referensi",
                        prefixIcon: Icon(Icons.tag, color: KaiColors.navy),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 20),
            const Text(
              "Kereta & Formasi",
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w700,
                color: KaiColors.navy,
              ),
            ),
            const SizedBox(height: 10),

            isLoading
                ? const Center(
                    child: Padding(
                      padding: EdgeInsets.all(20),
                      child: CircularProgressIndicator(),
                    ),
                  )
                : DropdownButtonFormField<Kereta>(
                    initialValue: keretaTerpilih,
                    isExpanded: true,
                    decoration: const InputDecoration(
                      labelText: "Pilih Kereta",
                      prefixIcon: Icon(Icons.train, color: KaiColors.navy),
                    ),
                    items: daftarKereta.map((kereta) {
                      return DropdownMenuItem<Kereta>(
                        value: kereta,
                        child: Text(
                          "${kereta.noKa} - ${kereta.namaKa}",
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        ),
                      );
                    }).toList(),
                    onChanged: onKeretaChanged,
                  ),

            if (keretaTerpilih != null) ...[
              const SizedBox(height: 24),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          "Nomor Sarana (Otomatis):",
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: KaiColors.navy,
                          ),
                        ),
                        Text(
                          "Sentuh sarana untuk centang / silang",
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.grey.shade600,
                          ),
                        ),
                      ],
                    ),
                  ),
                  if (daftarSaranaOtomatis.isNotEmpty)
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: selectedSaranaIndices.isEmpty
                            ? Colors.red.shade100
                            : KaiColors.lightBlue,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        "${selectedSaranaIndices.length}/${daftarSaranaOtomatis.length} Ada",
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 12,
                          color: selectedSaranaIndices.isEmpty
                              ? Colors.red.shade900
                              : KaiColors.navy,
                        ),
                      ),
                    ),
                ],
              ),
              const SizedBox(height: 10),

              // Quick action buttons & Tambah Sarana button (Responsive Wrap)
              Wrap(
                spacing: 8,
                runSpacing: 6,
                alignment: WrapAlignment.spaceBetween,
                crossAxisAlignment: WrapCrossAlignment.center,
                children: [
                  OutlinedButton.icon(
                    onPressed: showDialogTambahSarana,
                    icon: const Icon(Icons.add_circle_outline, size: 16),
                    label: const Text(
                      "Tambah Luar Formasi",
                      style: TextStyle(fontSize: 12),
                    ),
                    style: OutlinedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    ),
                  ),
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      TextButton(
                        onPressed: selectAllSarana,
                        style: TextButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          minimumSize: Size.zero,
                          tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                        ),
                        child: const Text("Pilih Semua", style: TextStyle(fontSize: 12)),
                      ),
                      const SizedBox(width: 4),
                      TextButton(
                        onPressed: deselectAllSarana,
                        style: TextButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          minimumSize: Size.zero,
                          tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                        ),
                        child: const Text("Batal Semua", style: TextStyle(fontSize: 12, color: Colors.red)),
                      ),
                    ],
                  ),
                ],
              ),

              const SizedBox(height: 8),

              if (isLoadingSarana)
                const Center(
                  child: Padding(
                    padding: EdgeInsets.all(16),
                    child: CircularProgressIndicator(),
                  ),
                )
              else if (daftarSaranaOtomatis.isEmpty)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.amber.shade50,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: Colors.amber.shade300),
                  ),
                  child: const Row(
                    children: [
                      Icon(Icons.info_outline, color: Colors.amber),
                      SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          "Tidak ada nomor sarana yang terdaftar untuk kereta ini. Anda dapat menekan tombol Tambah Luar Formasi di atas.",
                        ),
                      ),
                    ],
                  ),
                )
              else
                Container(
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: const Color(0xFFE3E8F0)),
                  ),
                  child: ListView.separated(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: daftarSaranaOtomatis.length,
                    separatorBuilder: (_, _) => const Divider(height: 1),
                    itemBuilder: (context, index) {
                      final s = daftarSaranaOtomatis[index];
                      final isSelected = selectedSaranaIndices.contains(index);

                      return InkWell(
                        onTap: () => toggleSarana(index),
                        child: Container(
                          color: isSelected
                              ? KaiColors.lightBlue
                              : Colors.grey.shade100,
                          padding: const EdgeInsets.symmetric(
                            horizontal: 12,
                            vertical: 8,
                          ),
                          child: Row(
                            children: [
                              CircleAvatar(
                                radius: 14,
                                backgroundColor:
                                    isSelected ? KaiColors.navy : Colors.grey.shade400,
                                child: Text(
                                  "${index + 1}",
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      children: [
                                        Text(
                                          "${s.kodeSarana} ${s.nomorSarana}",
                                          style: TextStyle(
                                            fontWeight: FontWeight.bold,
                                            fontSize: 15,
                                            decoration: isSelected
                                                ? null
                                                : TextDecoration.lineThrough,
                                            color: isSelected
                                                ? Colors.black87
                                                : Colors.grey.shade500,
                                          ),
                                        ),
                                        if (s.idSarana == 0) ...[
                                          const SizedBox(width: 6),
                                          Container(
                                            padding: const EdgeInsets.symmetric(
                                              horizontal: 6,
                                              vertical: 1,
                                            ),
                                            decoration: BoxDecoration(
                                              color: const Color(0xFFFFE4CC),
                                              borderRadius: BorderRadius.circular(4),
                                              border: Border.all(
                                                color: KaiColors.orange,
                                                width: 0.5,
                                              ),
                                            ),
                                            child: Text(
                                              "Luar Formasi",
                                              style: TextStyle(
                                                fontSize: 10,
                                                fontWeight: FontWeight.bold,
                                                color: KaiColors.orangeDark,
                                              ),
                                            ),
                                          ),
                                        ],
                                      ],
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      isSelected
                                          ? "${s.seriSarana ?? '-'} • Depo: ${s.depoInduk ?? 'YK'}"
                                          : "Dilewati (Tidak Dirangkaikan / Tiada)",
                                      style: TextStyle(
                                        fontSize: 12,
                                        color: isSelected
                                            ? Colors.black54
                                            : Colors.red.shade700,
                                        fontStyle: isSelected
                                            ? FontStyle.normal
                                            : FontStyle.italic,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              IconButton(
                                icon: Icon(
                                  isSelected
                                      ? Icons.check_circle
                                      : Icons.cancel,
                                  color:
                                      isSelected ? Colors.green : Colors.red,
                                  size: 26,
                                ),
                                onPressed: () => toggleSarana(index),
                                tooltip: isSelected
                                    ? "Klik untuk membatalkan sarana ini"
                                    : "Klik untuk memilih sarana ini",
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                ),
            ],

            const SizedBox(height: 30),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: selectedSaranaIndices.isEmpty
                    ? null
                    : lanjutKePemeriksaan,
                child: Text(
                  selectedSaranaIndices.isNotEmpty
                      ? "Mulai Pemeriksaan (${selectedSaranaIndices.length} Sarana)"
                      : "Pilih Minimal 1 Sarana",
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}