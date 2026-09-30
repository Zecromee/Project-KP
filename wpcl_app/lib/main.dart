import 'package:flutter/material.dart';
import 'api/api_service.dart';
import 'screens/splash/splash_screen.dart';
import 'theme/kai_theme.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ApiService.init();
  runApp(
    const WPCLApp(),
  );
}

class WPCLApp extends StatelessWidget {
  const WPCLApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'WPCL',
      theme: kaiTheme(),
      home: const SplashScreen(),
    );
  }
}
