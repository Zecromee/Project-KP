import 'package:flutter/material.dart';

import 'kai_colors.dart';

class KaiLogoMark extends StatelessWidget {
  final double size;
  final bool onDark;

  const KaiLogoMark({
    super.key,
    this.size = 72,
    this.onDark = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        color: onDark ? Colors.white.withValues(alpha: 0.12) : KaiColors.lightBlue,
        borderRadius: BorderRadius.circular(size * 0.22),
        border: Border.all(
          color: onDark ? KaiColors.orange : KaiColors.navy.withValues(alpha: 0.15),
          width: 2,
        ),
      ),
      child: Stack(
        alignment: Alignment.center,
        children: [
          Icon(
            Icons.train,
            size: size * 0.48,
            color: onDark ? Colors.white : KaiColors.navy,
          ),
          Positioned(
            bottom: size * 0.16,
            child: Container(
              width: size * 0.42,
              height: 4,
              decoration: BoxDecoration(
                color: KaiColors.orange,
                borderRadius: BorderRadius.circular(4),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class KaiSectionHeader extends StatelessWidget {
  final String title;
  final IconData icon;

  const KaiSectionHeader({
    super.key,
    required this.title,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 16, bottom: 8),
      child: Row(
        children: [
          Icon(icon, color: KaiColors.navy, size: 20),
          const SizedBox(width: 8),
          Text(
            title,
            style: const TextStyle(
              fontWeight: FontWeight.w700,
              fontSize: 16,
              color: KaiColors.navy,
            ),
          ),
        ],
      ),
    );
  }
}

class KaiStatusChoice extends StatelessWidget {
  final String title;
  final String value;
  final ValueChanged<String> onChanged;
  final bool enabled;

  const KaiStatusChoice({
    super.key,
    required this.title,
    required this.value,
    required this.onChanged,
    this.enabled = true,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: const TextStyle(
                fontWeight: FontWeight.w700,
                fontSize: 15,
                color: KaiColors.text,
              ),
            ),
            const SizedBox(height: 10),
            Row(
              children: [
                Expanded(
                  child: _tile(
                    code: 'B',
                    label: 'Baik',
                    icon: Icons.check_circle_outline,
                    activeColor: KaiColors.baik,
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: _tile(
                    code: 'R',
                    label: 'Rusak',
                    icon: Icons.cancel_outlined,
                    activeColor: KaiColors.rusak,
                  ),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: _tile(
                    code: 'T',
                    label: 'Tiada',
                    icon: Icons.remove_circle_outline,
                    activeColor: KaiColors.tiada,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _tile({
    required String code,
    required String label,
    required IconData icon,
    required Color activeColor,
  }) {
    final selected = value == code;
    return InkWell(
      onTap: enabled ? () => onChanged(code) : null,
      borderRadius: BorderRadius.circular(10),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10),
        decoration: BoxDecoration(
          color: selected ? activeColor.withValues(alpha: 0.12) : KaiColors.background,
          border: Border.all(
            color: selected ? activeColor : const Color(0xFFD5DCE6),
            width: selected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          children: [
            Icon(
              icon,
              color: selected ? activeColor : KaiColors.textMuted,
              size: 22,
            ),
            const SizedBox(height: 4),
            Text(
              label,
              style: TextStyle(
                fontWeight: selected ? FontWeight.w700 : FontWeight.w500,
                color: selected ? activeColor : KaiColors.text,
                fontSize: 13,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
