import 'package:flutter/material.dart';

import '../../models/riwayat_pemeriksaan.dart';
import '../../api/api_service.dart';
import 'riwayat_detail_screen.dart';

enum SortOption {
  terbaru,
  terlama,
  keretaAZ,
  keretaZA,
  petugasAZ,
}

class RiwayatScreen extends StatefulWidget {
  const RiwayatScreen({super.key});

  @override
  State<RiwayatScreen> createState() => _RiwayatScreenState();
}

class _RiwayatScreenState extends State<RiwayatScreen> {
  final ApiService apiService = ApiService();

  late Future<List<RiwayatPemeriksaan>> futureRiwayat;
  List<RiwayatPemeriksaan> rawList = [];

  // Filter & Search States
  String searchQuery = '';
  String filterDateRange = 'SEMUA'; // 'SEMUA', 'TODAY', '7DAYS', 'CUSTOM'
  DateTime? customDate;
  SortOption currentSort = SortOption.terbaru;

  final TextEditingController searchController = TextEditingController();
  bool isSearching = false;

  @override
  void initState() {
    super.initState();
    loadData();
  }

  void loadData() {
    futureRiwayat = apiService.getRiwayatPemeriksaan().then((data) {
      rawList = data;
      return data;
    });
  }

  Future<void> refresh() async {
    setState(() {
      loadData();
    });
    await futureRiwayat;
  }

  @override
  void dispose() {
    searchController.dispose();
    super.dispose();
  }

  //==========================================================
  // FILTER & SORT LOGIC
  //==========================================================

  List<RiwayatPemeriksaan> get filteredAndSortedList {
    List<RiwayatPemeriksaan> list = List.from(rawList);

    // 1. Search Query
    if (searchQuery.trim().isNotEmpty) {
      final q = searchQuery.toLowerCase().trim();
      list = list.where((item) {
        final ka = item.namaKa.toLowerCase();
        final petugas = item.namaPetugas.toLowerCase();
        final nipp = item.nipp.toLowerCase();
        final ref = item.noRef.toLowerCase();
        return ka.contains(q) ||
            petugas.contains(q) ||
            nipp.contains(q) ||
            ref.contains(q);
      }).toList();
    }

    // 2. Filter Date Range
    final now = DateTime.now();
    if (filterDateRange == 'TODAY') {
      list = list.where((item) {
        try {
          final dt = DateTime.parse(item.tanggal);
          return dt.year == now.year &&
              dt.month == now.month &&
              dt.day == now.day;
        } catch (_) {
          return true;
        }
      }).toList();
    } else if (filterDateRange == '7DAYS') {
      final sevenDaysAgo = now.subtract(const Duration(days: 7));
      list = list.where((item) {
        try {
          final dt = DateTime.parse(item.tanggal);
          return dt.isAfter(sevenDaysAgo);
        } catch (_) {
          return true;
        }
      }).toList();
    } else if (filterDateRange == 'CUSTOM' && customDate != null) {
      list = list.where((item) {
        try {
          final dt = DateTime.parse(item.tanggal);
          return dt.year == customDate!.year &&
              dt.month == customDate!.month &&
              dt.day == customDate!.day;
        } catch (_) {
          return true;
        }
      }).toList();
    }

    // 4. Sort Option
    switch (currentSort) {
      case SortOption.terbaru:
        list.sort((a, b) {
          try {
            return DateTime.parse(b.tanggal).compareTo(DateTime.parse(a.tanggal));
          } catch (_) {
            return b.id.compareTo(a.id);
          }
        });
        break;
      case SortOption.terlama:
        list.sort((a, b) {
          try {
            return DateTime.parse(a.tanggal).compareTo(DateTime.parse(b.tanggal));
          } catch (_) {
            return a.id.compareTo(b.id);
          }
        });
        break;
      case SortOption.keretaAZ:
        list.sort((a, b) => a.namaKa.toLowerCase().compareTo(b.namaKa.toLowerCase()));
        break;
      case SortOption.keretaZA:
        list.sort((a, b) => b.namaKa.toLowerCase().compareTo(a.namaKa.toLowerCase()));
        break;
      case SortOption.petugasAZ:
        list.sort((a, b) => a.namaPetugas.toLowerCase().compareTo(b.namaPetugas.toLowerCase()));
        break;
    }

    return list;
  }

  //==========================================================
  // SORT MODAL BOTTOM SHEET
  //==========================================================

  void showSortModal() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Row(
                        children: [
                          Icon(Icons.sort, color: Colors.blue),
                          SizedBox(width: 8),
                          Text(
                            "Urutkan Riwayat",
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                      IconButton(
                        icon: const Icon(Icons.close),
                        onPressed: () => Navigator.pop(ctx),
                      ),
                    ],
                  ),
                  const Divider(),
                  _buildSortTile("📅 Tanggal Terbaru (Default)", SortOption.terbaru, ctx),
                  _buildSortTile("📅 Tanggal Terlama", SortOption.terlama, ctx),
                  _buildSortTile("🔤 Nama Kereta (A - Z)", SortOption.keretaAZ, ctx),
                  _buildSortTile("🔤 Nama Kereta (Z - A)", SortOption.keretaZA, ctx),
                  _buildSortTile("👤 Nama Petugas (A - Z)", SortOption.petugasAZ, ctx),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildSortTile(String title, SortOption option, BuildContext modalCtx) {
    final isSelected = currentSort == option;
    return ListTile(
      title: Text(
        title,
        style: TextStyle(
          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          color: isSelected ? Colors.blue.shade800 : Colors.black87,
        ),
      ),
      trailing: isSelected ? const Icon(Icons.check_circle, color: Colors.blue) : null,
      onTap: () {
        setState(() {
          currentSort = option;
        });
        Navigator.pop(modalCtx);
      },
    );
  }

  //==========================================================
  // DATE PICKER
  //==========================================================

  Future<void> pickCustomDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: customDate ?? DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
    );
    if (picked != null) {
      setState(() {
        customDate = picked;
        filterDateRange = 'CUSTOM';
      });
    }
  }

  void resetFilters() {
    setState(() {
      searchQuery = '';
      searchController.clear();
      filterDateRange = 'SEMUA';
      customDate = null;
      currentSort = SortOption.terbaru;
    });
  }

  //==========================================================
  // LABEL & WARNA BADGE LOCOTRACK
  //==========================================================

  String formatTanggal(String raw) {
    try {
      final date = DateTime.parse(raw);
      final hh = date.hour.toString().padLeft(2, '0');
      final mm = date.minute.toString().padLeft(2, '0');
      return '${date.day.toString().padLeft(2, '0')}/'
          '${date.month.toString().padLeft(2, '0')}/'
          '${date.year} $hh:$mm';
    } catch (_) {
      return raw;
    }
  }

  //==========================================================
  // BUILD
  //==========================================================

  @override
  Widget build(BuildContext context) {
    final hasActiveFilter = searchQuery.isNotEmpty ||
        filterDateRange != 'SEMUA' ||
        currentSort != SortOption.terbaru;

    return Scaffold(
      appBar: AppBar(
        title: isSearching
            ? TextField(
                controller: searchController,
                autofocus: true,
                style: const TextStyle(color: Colors.black87),
                decoration: const InputDecoration(
                  hintText: "Cari KA, Petugas, NIPP, No Ref...",
                  border: InputBorder.none,
                ),
                onChanged: (val) {
                  setState(() {
                    searchQuery = val;
                  });
                },
              )
            : const Text('Riwayat Pengecekan'),
        actions: [
          IconButton(
            icon: Icon(isSearching ? Icons.close : Icons.search),
            tooltip: isSearching ? "Tutup Pencarian" : "Cari Riwayat",
            onPressed: () {
              setState(() {
                if (isSearching) {
                  isSearching = false;
                  searchQuery = '';
                  searchController.clear();
                } else {
                  isSearching = true;
                }
              });
            },
          ),
          IconButton(
            icon: const Icon(Icons.sort),
            tooltip: "Urutkan",
            onPressed: showSortModal,
          ),
        ],
      ),
      body: Column(
        children: [
          // Filter Chips Section
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            color: Colors.grey.shade50,
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  // Date Filter Chips
                  _buildFilterChip("Semua Waktu", filterDateRange == 'SEMUA', () {
                    setState(() => filterDateRange = 'SEMUA');
                  }),
                  const SizedBox(width: 6),
                  _buildFilterChip("Hari Ini", filterDateRange == 'TODAY', () {
                    setState(() => filterDateRange = 'TODAY');
                  }),
                  const SizedBox(width: 6),
                  _buildFilterChip("7 Hari Terakhir", filterDateRange == '7DAYS', () {
                    setState(() => filterDateRange = '7DAYS');
                  }),
                  const SizedBox(width: 6),
                  _buildFilterChip(
                    filterDateRange == 'CUSTOM' && customDate != null
                        ? "📅 ${customDate!.day}/${customDate!.month}/${customDate!.year}"
                        : "📅 Pilih Tanggal...",
                    filterDateRange == 'CUSTOM',
                    pickCustomDate,
                  ),

                  if (hasActiveFilter) ...[
                    const SizedBox(width: 8),
                    ActionChip(
                      avatar: const Icon(Icons.refresh, size: 16, color: Colors.red),
                      label: const Text("Reset", style: TextStyle(color: Colors.red, fontSize: 12)),
                      onPressed: resetFilters,
                    ),
                  ],
                ],
              ),
            ),
          ),

          // Main List View
          Expanded(
            child: RefreshIndicator(
              onRefresh: refresh,
              child: FutureBuilder<List<RiwayatPemeriksaan>>(
                future: futureRiwayat,
                builder: (context, snapshot) {
                  // LOADING
                  if (snapshot.connectionState == ConnectionState.waiting && rawList.isEmpty) {
                    return const Center(
                      child: CircularProgressIndicator(),
                    );
                  }

                  // ERROR
                  if (snapshot.hasError && rawList.isEmpty) {
                    return ListView(
                      children: [
                        const SizedBox(height: 100),
                        const Icon(
                          Icons.error_outline,
                          size: 60,
                          color: Colors.red,
                        ),
                        const SizedBox(height: 12),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 24),
                          child: Text(
                            'Gagal memuat riwayat.\n${snapshot.error}',
                            textAlign: TextAlign.center,
                          ),
                        ),
                        const SizedBox(height: 16),
                        Center(
                          child: OutlinedButton(
                            onPressed: refresh,
                            child: const Text('Coba Lagi'),
                          ),
                        ),
                      ],
                    );
                  }

                  final displayList = filteredAndSortedList;

                  // KOSONG
                  if (displayList.isEmpty) {
                    return ListView(
                      children: [
                        const SizedBox(height: 100),
                        Icon(
                          hasActiveFilter ? Icons.search_off : Icons.history,
                          size: 60,
                          color: Colors.grey,
                        ),
                        const SizedBox(height: 12),
                        Center(
                          child: Text(
                            hasActiveFilter
                                ? 'Tidak ada riwayat yang sesuai dengan filter.'
                                : 'Belum ada riwayat pengecekan.',
                            style: const TextStyle(color: Colors.grey),
                          ),
                        ),
                        if (hasActiveFilter) ...[
                          const SizedBox(height: 12),
                          Center(
                            child: OutlinedButton(
                              onPressed: resetFilters,
                              child: const Text("Reset Semua Filter"),
                            ),
                          ),
                        ],
                      ],
                    );
                  }

                  // LIST
                  return Column(
                    children: [
                      // Status count bar
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              "Menampilkan ${displayList.length} dari ${rawList.length} riwayat",
                              style: TextStyle(
                                fontSize: 12,
                                color: Colors.grey.shade600,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            Text(
                              _sortLabel(currentSort),
                              style: TextStyle(
                                fontSize: 11,
                                color: Colors.blue.shade700,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Expanded(
                        child: ListView.separated(
                          padding: const EdgeInsets.fromLTRB(12, 4, 12, 12),
                          itemCount: displayList.length,
                          separatorBuilder: (_, _) => const SizedBox(height: 8),
                          itemBuilder: (context, index) {
                            final item = displayList[index];

                            return Card(
                              elevation: 1.5,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10),
                              ),
                              child: InkWell(
                                borderRadius: BorderRadius.circular(10),
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => RiwayatDetailScreen(id: item.id),
                                    ),
                                  );
                                },
                                child: Padding(
                                  padding: const EdgeInsets.all(14),
                                  child: Row(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text(
                                              item.namaKa,
                                              style: const TextStyle(
                                                fontWeight: FontWeight.bold,
                                                fontSize: 15,
                                              ),
                                            ),
                                            const SizedBox(height: 4),
                                            Text(
                                              formatTanggal(item.tanggal),
                                              style: const TextStyle(fontSize: 13),
                                            ),
                                            const SizedBox(height: 2),
                                            Text(
                                              '${item.namaPetugas} • NIPP ${item.nipp}',
                                              style: TextStyle(
                                                fontSize: 12,
                                                color: Colors.grey.shade800,
                                              ),
                                            ),
                                            const SizedBox(height: 2),
                                            Text(
                                              '${item.noRef} • ${item.businessArea}',
                                              style: const TextStyle(
                                                color: Colors.black54,
                                                fontSize: 11,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                      const Icon(Icons.chevron_right, color: Colors.grey),
                                    ],
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                      ),
                    ],
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(String label, bool isSelected, VoidCallback onTap) {
    return FilterChip(
      label: Text(
        label,
        style: TextStyle(
          fontSize: 12,
          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          color: isSelected ? Colors.blue.shade900 : Colors.black87,
        ),
      ),
      selected: isSelected,
      selectedColor: Colors.blue.shade100,
      backgroundColor: Colors.white,
      showCheckmark: false,
      padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
      onSelected: (_) => onTap(),
    );
  }

  String _sortLabel(SortOption option) {
    switch (option) {
      case SortOption.terbaru:
        return "Terbaru";
      case SortOption.terlama:
        return "Terlama";
      case SortOption.keretaAZ:
        return "KA (A-Z)";
      case SortOption.keretaZA:
        return "KA (Z-A)";
      case SortOption.petugasAZ:
        return "Petugas (A-Z)";
    }
  }
}