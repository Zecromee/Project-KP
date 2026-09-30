import 'package:flutter/material.dart';
import '../../api/api_service.dart';
import '../../theme/kai_colors.dart';
import '../../theme/kai_widgets.dart';
import '../pemeriksaan/identitas_screen.dart';
import '../riwayat/riwayat_screen.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  void _showServerSettingsDialog(BuildContext context) {
    final controller = TextEditingController(text: ApiService.baseUrl);

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          title: const Row(
            children: [
              Icon(Icons.settings, color: KaiColors.navy),
              SizedBox(width: 8),
              Text('Pengaturan Server', style: TextStyle(fontSize: 18)),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Masukkan URL Server Backend WPCL saat ini:',
                style: TextStyle(fontSize: 13, color: KaiColors.textMuted),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: controller,
                decoration: const InputDecoration(
                  labelText: 'Server Base URL',
                  hintText: 'http://192.168.100.57:8000/api',
                ),
              ),
              const SizedBox(height: 10),
              Text(
                'Contoh:\n- Wi-Fi Lokal: http://192.168.100.57:8000/api\n- Tunnel: https://xxxx.trycloudflare.com/api',
                style: TextStyle(fontSize: 11, color: Colors.grey.shade600),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () {
                controller.text = ApiService.defaultBaseUrl;
              },
              child: const Text('Reset Wi-Fi', style: TextStyle(fontSize: 12)),
            ),
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Batal', style: TextStyle(fontSize: 12)),
            ),
            ElevatedButton(
              onPressed: () async {
                if (controller.text.trim().isNotEmpty) {
                  await ApiService.saveBaseUrl(controller.text);
                  if (context.mounted) {
                    Navigator.pop(ctx);
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text('Server diatur ke: ${ApiService.baseUrl}'),
                        backgroundColor: KaiColors.baik,
                      ),
                    );
                  }
                }
              },
              child: const Text('Simpan'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('WPCL'),
        actions: [
          IconButton(
            icon: const Icon(Icons.settings),
            tooltip: 'Pengaturan Server',
            onPressed: () => _showServerSettingsDialog(context),
          ),
        ],
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.fromLTRB(24, 28, 24, 24),
          child: Column(
            children: [
              Expanded(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const KaiLogoMark(size: 88),
                      const SizedBox(height: 22),
                      const Text(
                        'WPCL',
                        style: TextStyle(
                          fontSize: 28,
                          fontWeight: FontWeight.w800,
                          color: KaiColors.navy,
                          letterSpacing: 0.6,
                        ),
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        'Workstation Passenger Check List',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                          color: KaiColors.text,
                        ),
                      ),
                      const SizedBox(height: 6),
                      const Text(
                        'Aplikasi Pemeriksaan Sarana Kereta\nDepo Kereta Yogyakarta',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 14,
                          height: 1.4,
                          color: KaiColors.textMuted,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const IdentitasScreen(),
                      ),
                    );
                  },
                  icon: const Icon(Icons.playlist_add_check),
                  label: const Text('Mulai Pemeriksaan'),
                ),
              ),
              const SizedBox(height: 12),
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const RiwayatScreen(),
                      ),
                    );
                  },
                  icon: const Icon(Icons.history),
                  label: const Text('Lihat Riwayat Pengecekan'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
