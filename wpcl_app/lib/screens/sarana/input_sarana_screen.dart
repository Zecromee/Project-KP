import 'package:flutter/material.dart';

import '../../api/api_service.dart';
import '../../models/sarana.dart';
import '../checklist/checklist_screen.dart';

class InputSaranaScreen extends StatefulWidget {
  final String namaPetugas;
  final String nipp;
  final int idKereta;

  final String namaKa;
  final String businessArea;
  final String noRef;

  const InputSaranaScreen({
    super.key,
    required this.namaPetugas,
    required this.nipp,
    required this.idKereta,

    required this.namaKa,
    required this.businessArea,
    required this.noRef,
  });

  @override
  State<InputSaranaScreen> createState() => _InputSaranaScreenState();
}

class _InputSaranaScreenState extends State<InputSaranaScreen> {
  final ApiService apiService = ApiService();

  final List<TextEditingController> controllers = [];

  final List<Sarana?> hasilSarana = [];

  final List<bool> loading = [];

  @override
  void initState() {
    super.initState();
    tambahSarana();
  }

  void tambahSarana() {
    setState(() {
      controllers.add(TextEditingController());
      hasilSarana.add(null);
      loading.add(false);
    });
  }

  void hapusSarana(int index) {
    controllers[index].dispose();

    setState(() {
      controllers.removeAt(index);
      hasilSarana.removeAt(index);
      loading.removeAt(index);
    });
  }

  Future<void> cariSarana(int index) async {
    final keyword = controllers[index].text.trim();

    if (keyword.isEmpty) {
      setState(() {
        hasilSarana[index] = null;
      });
      return;
    }

    setState(() {
      loading[index] = true;
    });

    try {
      final result = await apiService.searchSarana(keyword);

      setState(() {
        loading[index] = false;

        if (result.isNotEmpty) {
          hasilSarana[index] = result.first;
        } else {
          hasilSarana[index] = null;
        }
      });
    } catch (e) {
      setState(() {
        loading[index] = false;
        hasilSarana[index] = null;
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Terjadi kesalahan\n$e"),
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    for (final controller in controllers) {
      controller.dispose();
    }

    super.dispose();
  }

  void lanjut() {
  if (hasilSarana.where((e) => e != null).isEmpty) {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text("Masukkan minimal satu sarana yang valid."),
      ),
    );
    return;
  }

  final daftarSarana = hasilSarana.whereType<Sarana>().toList();

  Navigator.push(
    context,
    MaterialPageRoute(
      builder: (_) => ChecklistScreen(
        namaPetugas: widget.namaPetugas,
        nipp: widget.nipp,
        idKereta: widget.idKereta,

        namaKa: widget.namaKa,
        businessArea: widget.businessArea,
        noRef: widget.noRef,

        daftarSarana: daftarSarana,
      ),
    ),
  );
}

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Input Nomor Sarana"),
      ),

      floatingActionButton: FloatingActionButton(
        onPressed: tambahSarana,
        child: const Icon(Icons.add),
      ),

      body: Padding(
        padding: const EdgeInsets.all(16),

        child: Column(
          children: [

            Card(
              child: ListTile(
                leading: const Icon(Icons.person),

                title: Text(widget.namaPetugas),

                subtitle: Text(
                  "NIPP : ${widget.nipp}",
                ),
              ),
            ),

            const SizedBox(height: 16),

            Expanded(
              child: ListView.builder(
                itemCount: controllers.length,

                itemBuilder: (context, index) {

                  return Card(
                    margin: const EdgeInsets.only(bottom: 16),

                    child: Padding(
                      padding: const EdgeInsets.all(12),

                      child: Column(
                        crossAxisAlignment:
                            CrossAxisAlignment.start,

                        children: [

                          Row(
                            children: [

                              Expanded(
                                child: TextField(
                                  controller:
                                      controllers[index],

                                  decoration:
                                      InputDecoration(
                                    labelText:
                                        "Nomor Sarana ${index + 1}",

                                    hintText: "02403",

                                    border:
                                        const OutlineInputBorder(),
                                  ),

                                  onSubmitted: (_) {
                                    cariSarana(index);
                                  },

                                  onEditingComplete: () {
                                    FocusScope.of(context)
                                        .unfocus();

                                    cariSarana(index);
                                  },
                                ),
                              ),

                              IconButton(
                                onPressed: () {
                                  hapusSarana(index);
                                },

                                icon: const Icon(
                                  Icons.delete,
                                  color: Colors.red,
                                ),
                              ),
                            ],
                          ),

                          const SizedBox(height: 12),
                                                    if (loading[index])
                            const Center(
                              child: Padding(
                                padding: EdgeInsets.symmetric(vertical: 8),
                                child: CircularProgressIndicator(),
                              ),
                            )
                          else if (hasilSarana[index] != null)
                            Container(
                              width: double.infinity,
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: Colors.green.shade50,
                                border: Border.all(
                                  color: Colors.green,
                                ),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Column(
                                crossAxisAlignment:
                                    CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: const [
                                      Icon(
                                        Icons.check_circle,
                                        color: Colors.green,
                                      ),
                                      SizedBox(width: 8),
                                      Text(
                                        "Sarana ditemukan",
                                        style: TextStyle(
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ],
                                  ),

                                  const SizedBox(height: 10),

                                  Text(
                                    "Kode : ${hasilSarana[index]!.kodeSarana}",
                                  ),

                                  Text(
                                    "Nomor : ${hasilSarana[index]!.nomorSarana}",
                                  ),

                                  Text(
                                    "Seri : ${hasilSarana[index]!.seriSarana ?? '-'}",
                                  ),

                                  Text(
                                    "Depo : ${hasilSarana[index]!.depoInduk ?? '-'}",
                                  ),
                                ],
                              ),
                            )
                          else if (controllers[index]
                              .text
                              .trim()
                              .isNotEmpty)
                            Container(
                              width: double.infinity,
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: Colors.red.shade50,
                                border: Border.all(
                                  color: Colors.red,
                                ),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: const Row(
                                children: [
                                  Icon(
                                    Icons.error,
                                    color: Colors.red,
                                  ),
                                  SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      "Nomor sarana tidak ditemukan.",
                                    ),
                                  ),
                                ],
                              ),
                            ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),

            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: lanjut,
                child: const Text(
                  "Lanjut Pemeriksaan",
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}