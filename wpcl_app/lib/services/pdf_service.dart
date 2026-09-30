import 'package:flutter/services.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';

class PdfService {
  //==========================================================
  // COLUMN WIDTH
  //
  // SEMUA HEADER DAN DATA MENGGUNAKAN WIDTH YANG SAMA.
  // JANGAN UBAH TERPISAH ANTARA HEADER DAN DATA.
  //==========================================================

  static const double noWidth = 25;
  static const double saranaWidth = 65;

  static const double cctvBerfungsiWidth = 48;
  static const double cctvBackupWidth = 48;

  static const double pidsSisiAWidth = 48;
  static const double pidsSisiEWidth = 48;
  static const double pidsTdKecilWidth = 48;
  static const double pidsTdBesarWidth = 48;

  static const double wifiWidth = 48;

  // Fit 100% of A4 Landscape printable width (841.89 - 40 margin = 801.89 pt)
  // 801.89 - (25 + 65 + 7 * 48) = 801.89 - 426 = 375.89 pt
  static const double noteWidth = 375.89;

  // Total: 801.89 pt (Full Width Edge-to-Edge)
  static const double totalTableWidth =
      noWidth +
      saranaWidth +
      cctvBerfungsiWidth +
      cctvBackupWidth +
      pidsSisiAWidth +
      pidsSisiEWidth +
      pidsTdKecilWidth +
      pidsTdBesarWidth +
      wifiWidth +
      noteWidth;

  //==========================================================
  // HEADER HEIGHT
  //==========================================================

  static const double rowAHeight = 18;
  static const double rowBHeight = 16;
  static const double rowCHeight = 16;
  static const double rowDHeight = 16;

  static const double totalHeaderHeight =
      rowAHeight +
      rowBHeight +
      rowCHeight +
      rowDHeight;

  //==========================================================
  // GENERATE PDF
  //==========================================================

  Future<void> generatePdf({
    required String businessArea,
    required String noRef,
    required String namaKa,
    required String tanggal,
    required String catatan,
    required String namaPetugas,
    required String nipp,
    required List detailList,
    String? locotrack,
    String? locoId,
    String? nomorSaranaLoco,
    String? catatanKeseluruhan,
    String? pejabatNama,
    String? pejabatNipp,
    String? pejabatJabatan,
    String? namaMengetahui,
    String? nippMengetahui,
  }) async {
    final pdf = pw.Document();

    //========================================================
    // LOAD LOGO
    //========================================================

    final logoData = await rootBundle.load(
      'assets/images/logo_kai.png',
    );

    final logo = pw.MemoryImage(
      logoData.buffer.asUint8List(),
    );

    //========================================================
    // PAGE
    //========================================================

    pdf.addPage(
      pw.MultiPage(
        pageFormat: PdfPageFormat.a4.landscape,

        margin: const pw.EdgeInsets.fromLTRB(
          20,
          20,
          20,
          20,
        ),

        build: (context) {
          return [
            //================================================
            // HEADER
            //================================================

            buildHeader(
              logo: logo,
            ),

            pw.SizedBox(height: 10),

            //================================================
            // IDENTITAS
            //================================================

            buildIdentitySection(
              businessArea: businessArea,
              noRef: noRef,
              tanggal: tanggal,
            ),

            pw.SizedBox(height: 10),

            //================================================
            // CHECKLIST
            //================================================

            buildChecklistTable(
              namaKa,
              detailList,
            ),

            pw.SizedBox(height: 8),

            //================================================
            // CATATAN
            //================================================

            buildCatatanSection(
              locotrack: locotrack,
              locoId: locoId,
              nomorSaranaLoco: nomorSaranaLoco,
              catatan: catatan,
              catatanKeseluruhan: catatanKeseluruhan,
            ),

            pw.SizedBox(height: 12),

            //================================================
            // FOOTER
            //================================================

            buildFooter(
              namaMengetahui: pejabatNama ?? namaMengetahui ?? 'Pitra Argehermanu',
              nippMengetahui: pejabatNipp ?? nippMengetahui ?? '46002',
              jabatanMengetahui: pejabatJabatan ?? 'Manager IT',
              namaPetugas: namaPetugas,
              nipp: nipp,
            ),
          ];
        },
      ),
    );

    //========================================================
    // PRINT / PREVIEW
    //========================================================

    await Printing.layoutPdf(
      onLayout: (format) async {
        return pdf.save();
      },
    );
  }

  //==========================================================
  // HEADER UTAMA
  //==========================================================

  pw.Widget buildHeader({
    required pw.MemoryImage logo,
  }) {
    return pw.Table(
      border: pw.TableBorder.all(
        width: 0.7,
        color: PdfColors.black,
      ),

      columnWidths: {
        0: const pw.FixedColumnWidth(160),
        1: const pw.FlexColumnWidth(1),
        2: const pw.FixedColumnWidth(230),
      },

      children: [
        //====================================================
        // BARIS 1
        //====================================================

        pw.TableRow(
          children: [
            pw.Container(
              height: 34,
              alignment: pw.Alignment.center,
              padding: const pw.EdgeInsets.symmetric(
                horizontal: 8,
                vertical: 2,
              ),
              child: pw.Row(
                mainAxisAlignment:
                    pw.MainAxisAlignment.start,
                children: [
                  pw.Container(
                    width: 26,
                    height: 26,
                    alignment: pw.Alignment.center,
                    child: pw.Image(
                      logo,
                      fit: pw.BoxFit.contain,
                    ),
                  ),
                ],
              ),
            ),

            pw.Container(
              height: 34,
              alignment: pw.Alignment.center,
              child: pw.Column(
                mainAxisAlignment:
                    pw.MainAxisAlignment.center,
                children: [
                  pw.Text(
                    'PT. KERETA API INDONESIA (PERSERO)',
                    textAlign: pw.TextAlign.center,
                    style: pw.TextStyle(
                      fontSize: 11,
                      fontWeight: pw.FontWeight.bold,
                    ),
                  ),

                  pw.SizedBox(height: 2),

                  pw.Text(
                    'Sistem Informasi',
                    textAlign: pw.TextAlign.center,
                    style: const pw.TextStyle(
                      fontSize: 9,
                    ),
                  ),
                ],
              ),
            ),

            //================================================
            // INFO DOKUMEN
            //================================================

            pw.Container(
              height: 68,
              child: buildDocInfoBox(),
            ),
          ],
        ),

        //====================================================
        // BARIS 2
        //====================================================

        pw.TableRow(
          children: [
            pw.Container(
              height: 22,
              alignment: pw.Alignment.center,
              padding: const pw.EdgeInsets.symmetric(
                horizontal: 6,
                vertical: 3,
              ),
              child: pw.Container(
                padding: const pw.EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 3,
                ),
                decoration: const pw.BoxDecoration(
                  color: PdfColors.yellow200,
                ),
                child: pw.Text(
                  'TERBATAS',
                  style: pw.TextStyle(
                    fontSize: 8,
                    fontWeight: pw.FontWeight.bold,
                  ),
                ),
              ),
            ),

            pw.Container(
              height: 22,
              alignment: pw.Alignment.center,
              child: pw.Text(
                'FORMULIR MONITORING',
                textAlign: pw.TextAlign.center,
                style: pw.TextStyle(
                  fontSize: 10,
                  fontWeight: pw.FontWeight.bold,
                ),
              ),
            ),

            pw.Container(
              height: 22,
            ),
          ],
        ),
      ],
    );
  }

  //==========================================================
  // DOCUMENT INFO
  //==========================================================

  pw.Widget buildDocInfoBox() {
    final labelStyle = pw.TextStyle(
      fontSize: 8,
      fontWeight: pw.FontWeight.bold,
    );

    const valueStyle = pw.TextStyle(
      fontSize: 8,
    );

    pw.TableRow docInfoRow(
      String label,
      pw.Widget value,
    ) {
      return pw.TableRow(
        children: [
          pw.Padding(
            padding: const pw.EdgeInsets.symmetric(
              horizontal: 4,
              vertical: 3,
            ),
            child: pw.Text(
              label,
              style: labelStyle,
            ),
          ),

          pw.Padding(
            padding: const pw.EdgeInsets.symmetric(
              horizontal: 4,
              vertical: 3,
            ),
            child: pw.Row(
              children: [
                pw.Text(
                  ': ',
                  style: valueStyle,
                ),
                value,
              ],
            ),
          ),
        ],
      );
    }

    return pw.Table(
      border: pw.TableBorder.all(
        width: 0.7,
        color: PdfColors.black,
      ),

      columnWidths: {
        0: const pw.FixedColumnWidth(80),
        1: const pw.FlexColumnWidth(1),
      },

      children: [
        docInfoRow(
          'No. Dokumen',
          pw.Text(
            '',
            style: valueStyle,
          ),
        ),

        docInfoRow(
          'Versi',
          pw.Text(
            '',
            style: valueStyle,
          ),
        ),

        docInfoRow(
          'Halaman',
          pw.Builder(
            builder: (context) {
              return pw.Text(
                '${context.pageNumber}',
                style: valueStyle,
              );
            },
          ),
        ),
      ],
    );
  }

  //==========================================================
  // IDENTITAS PEMERIKSAAN
  //==========================================================

  pw.Widget buildIdentitySection({
    required String businessArea,
    required String noRef,
    required String tanggal,
  }) {
    return pw.SizedBox(
      width: 220,

      child: pw.Table(
        columnWidths: {
          0: const pw.FixedColumnWidth(80),
          1: const pw.FixedColumnWidth(10),
          2: const pw.FlexColumnWidth(1),
        },

        children: [
          identityRow(
            'No Ref',
            noRef,
          ),

          identityRow(
            'Tanggal',
            tanggal,
          ),

          identityRow(
            'Business Area',
            businessArea,
          ),
        ],
      ),
    );
  }

  //==========================================================
  // IDENTITY ROW
  //==========================================================

  pw.TableRow identityRow(
    String label,
    String value,
  ) {
    return pw.TableRow(
      children: [
        pw.Padding(
          padding: const pw.EdgeInsets.symmetric(
            vertical: 1.5,
          ),
          child: pw.Text(
            label,
            style: const pw.TextStyle(
              fontSize: 8,
            ),
          ),
        ),

        pw.Text(
          ':',
          style: const pw.TextStyle(
            fontSize: 8,
          ),
        ),

        pw.Padding(
          padding: const pw.EdgeInsets.symmetric(
            vertical: 1.5,
          ),
          child: pw.Text(
            value,
            style: const pw.TextStyle(
              fontSize: 8,
            ),
          ),
        ),
      ],
    );
  }

  //==========================================================
  // CHECKLIST TABLE
  //
  // GRID:
  //
  // 0  NO
  // 1  NO SARANA
  // 2  CCTV BERFUNGSI
  // 3  CCTV TERBACKUP
  // 4  PIDS SISI A
  // 5  PIDS SISI E
  // 6  PIDS TD KECIL
  // 7  PIDS TD BESAR
  // 8  WIFI BERFUNGSI
  // 9  NOTE
  //
  // HEADER:
  //
  //                  NAMA KA
  // CCTV          PIDS             WIFI
  // BERFUNGSI     PIDS LUAR       BERFUNGSI
  // TERBACKUP     PIDS DALAM
  //               SISI A | SISI E | TD KECIL | TD BESAR
  //==========================================================

  pw.Widget buildChecklistTable(
    String namaKa,
    List detail,
  ) {
    //========================================================
    // BORDER
    //========================================================

    const borderSide = pw.BorderSide(
      width: 0.7,
      color: PdfColors.black,
    );

    //========================================================
    // HEADER STYLES
    //========================================================

    final mainHeaderStyle = pw.TextStyle(
      fontSize: 7,
      fontWeight: pw.FontWeight.bold,
    );

    final subHeaderStyle = pw.TextStyle(
      fontSize: 6.5,
      fontWeight: pw.FontWeight.bold,
    );

    final namaKaStyle = pw.TextStyle(
      fontSize: 8.5,
      fontWeight: pw.FontWeight.bold,
    );

    //========================================================
    // HEADER CELL
    //========================================================

    pw.Widget hCell({
      required String text,
      required double width,
      required double height,
      required pw.TextStyle style,
      PdfColor background = PdfColors.grey200,
      bool left = true,
      bool right = true,
      bool top = true,
      bool bottom = true,
    }) {
      return pw.Container(
        width: width,
        height: height,

        alignment: pw.Alignment.center,

        decoration: pw.BoxDecoration(
          color: background,

          border: pw.Border(
            left: left
                ? borderSide
                : pw.BorderSide.none,

            right: right
                ? borderSide
                : pw.BorderSide.none,

            top: top
                ? borderSide
                : pw.BorderSide.none,

            bottom: bottom
                ? borderSide
                : pw.BorderSide.none,
          ),
        ),

        child: pw.Text(
          text,
          textAlign: pw.TextAlign.center,
          style: style,
        ),
      );
    }

    //========================================================
    // HEADER
    //
    // IMPORTANT:
    // Header dibuat berdasarkan posisi X yang sama dengan
    // kolom data.
    //========================================================

    final header = pw.Container(
      width: totalTableWidth,
      height: totalHeaderHeight,

      decoration: const pw.BoxDecoration(
        border: pw.Border(
          left: borderSide,
          right: borderSide,
          top: borderSide,
          bottom: borderSide,
        ),
      ),

      child: pw.Row(
        crossAxisAlignment:
            pw.CrossAxisAlignment.start,

        children: [
          //==================================================
          // NO
          // ROWSPAN 4
          //==================================================

          hCell(
            text: 'No',
            width: noWidth,
            height: totalHeaderHeight,
            style: mainHeaderStyle,
            left: false,
            top: false,
            bottom: false,
          ),

          //==================================================
          // NO SARANA
          // ROWSPAN 4
          //==================================================

          hCell(
            text: 'No.\nSarana',
            width: saranaWidth,
            height: totalHeaderHeight,
            style: mainHeaderStyle,
            top: false,
            bottom: false,
          ),

          //==================================================
          // BAGIAN TENGAH
          //==================================================

          pw.Column(
            children: [
              //==============================================
              // ROW A
              // NAMA KA
              //==============================================

              hCell(
                text:
                    'NAMA KA : ${namaKa.toUpperCase()}',
                width:
                    cctvBerfungsiWidth +
                    cctvBackupWidth +
                    pidsSisiAWidth +
                    pidsSisiEWidth +
                    pidsTdKecilWidth +
                    pidsTdBesarWidth +
                    wifiWidth,
                height: rowAHeight,
                style: namaKaStyle,
                background: PdfColors.blue50,
                left: false,
                right: false,
                top: false,
                bottom: true,
              ),

              //==============================================
              // ROW B
              //
              // CCTV | PIDS | WIFI
              //==============================================

              pw.Row(
                children: [
                  hCell(
                    text: 'CCTV',
                    width:
                        cctvBerfungsiWidth +
                        cctvBackupWidth,
                    height: rowBHeight,
                    style: mainHeaderStyle,
                    left: false,
                    bottom: true,
                  ),

                  hCell(
                    text: 'PIDS',
                    width:
                        pidsSisiAWidth +
                        pidsSisiEWidth +
                        pidsTdKecilWidth +
                        pidsTdBesarWidth,
                    height: rowBHeight,
                    style: mainHeaderStyle,
                    left: false,
                    bottom: true,
                  ),

                  hCell(
                    text: 'WIFI',
                    width: wifiWidth,
                    height: rowBHeight,
                    style: mainHeaderStyle,
                    left: false,
                    right: false,
                    bottom: true,
                  ),
                ],
              ),

              //==============================================
              // ROW C + D
              //==============================================

              pw.Row(
                children: [
                  //============================================
                  // CCTV BERFUNGSI
                  // ROWSPAN C+D
                  //============================================

                  hCell(
                    text: 'BERFUNGSI',
                    width: cctvBerfungsiWidth,
                    height:
                        rowCHeight +
                        rowDHeight,
                    style: subHeaderStyle,
                    left: false,
                    bottom: false,
                  ),

                  //============================================
                  // CCTV TERBACKUP
                  // ROWSPAN C+D
                  //============================================

                  hCell(
                    text: 'TERBACKUP',
                    width: cctvBackupWidth,
                    height:
                        rowCHeight +
                        rowDHeight,
                    style: subHeaderStyle,
                    left: false,
                    bottom: false,
                  ),

                  //============================================
                  // PIDS
                  //============================================

                  pw.Column(
                    children: [
                      //========================================
                      // PIDS LUAR / PIDS DALAM
                      //========================================

                      pw.Row(
                        children: [
                          hCell(
                            text: 'PIDS LUAR',
                            width:
                                pidsSisiAWidth +
                                pidsSisiEWidth,
                            height: rowCHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: true,
                          ),

                          hCell(
                            text: 'PIDS DALAM',
                            width:
                                pidsTdKecilWidth +
                                pidsTdBesarWidth,
                            height: rowCHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: true,
                          ),
                        ],
                      ),

                      //========================================
                      // SISI A / SISI E / TD KECIL / TD BESAR
                      //========================================

                      pw.Row(
                        children: [
                          hCell(
                            text: 'SISI A',
                            width: pidsSisiAWidth,
                            height: rowDHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: false,
                          ),

                          hCell(
                            text: 'SISI E',
                            width: pidsSisiEWidth,
                            height: rowDHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: false,
                          ),

                          hCell(
                            text: 'TD KECIL',
                            width: pidsTdKecilWidth,
                            height: rowDHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: false,
                          ),

                          hCell(
                            text: 'TD BESAR',
                            width: pidsTdBesarWidth,
                            height: rowDHeight,
                            style: subHeaderStyle,
                            left: false,
                            bottom: false,
                          ),
                        ],
                      ),
                    ],
                  ),

                  //============================================
                  // WIFI BERFUNGSI
                  // ROWSPAN C+D
                  //============================================

                  hCell(
                    text: 'BERFUNGSI',
                    width: wifiWidth,
                    height:
                        rowCHeight +
                        rowDHeight,
                    style: subHeaderStyle,
                    left: false,
                    right: false,
                    bottom: false,
                  ),
                ],
              ),
            ],
          ),

          //==================================================
          // NOTE
          // ROWSPAN 4
          //==================================================

          hCell(
            text: 'NOTE',
            width: noteWidth,
            height: totalHeaderHeight,
            style: mainHeaderStyle,
            top: false,
            right: false,
            bottom: false,
          ),
        ],
      ),
    );

    //========================================================
    // DATA CELL
    //========================================================

    pw.Widget tableDataCell(
      String text, {
      bool alignLeft = false,
    }) {
      return pw.Container(
        width: double.infinity,

        constraints: const pw.BoxConstraints(
          minHeight: 20,
        ),

        alignment: alignLeft
            ? pw.Alignment.centerLeft
            : pw.Alignment.center,

        padding: const pw.EdgeInsets.symmetric(
          horizontal: 3,
          vertical: 3,
        ),

        child: pw.Text(
          text,
          textAlign: alignLeft
              ? pw.TextAlign.left
              : pw.TextAlign.center,

          maxLines: 3,

          style: const pw.TextStyle(
            fontSize: 7,
          ),
        ),
      );
    }

    //========================================================
    // DATA TABLE
    //
    // INI WAJIB MEMAKAI WIDTH YANG SAMA DENGAN HEADER.
    //========================================================

    String mapStatusText(String? val) {
      if (val == null || val.isEmpty) return '-';
      final v = val.toUpperCase().trim();
      if (v == 'B' || v == 'BAIK') return 'Baik';
      if (v == 'R' || v == 'RUSAK') return 'Rusak';
      if (v == 'T' || v == 'TIADA') return 'Tiada';
      return val;
    }

    final dataTable = pw.Table(
      border: const pw.TableBorder(
        left: borderSide,
        right: borderSide,
        bottom: borderSide,
        horizontalInside: borderSide,
        verticalInside: borderSide,
      ),

      columnWidths: {
        0: const pw.FixedColumnWidth(noWidth),
        1: const pw.FixedColumnWidth(saranaWidth),

        2: const pw.FixedColumnWidth(
          cctvBerfungsiWidth,
        ),

        3: const pw.FixedColumnWidth(
          cctvBackupWidth,
        ),

        4: const pw.FixedColumnWidth(
          pidsSisiAWidth,
        ),

        5: const pw.FixedColumnWidth(
          pidsSisiEWidth,
        ),

        6: const pw.FixedColumnWidth(
          pidsTdKecilWidth,
        ),

        7: const pw.FixedColumnWidth(
          pidsTdBesarWidth,
        ),

        8: const pw.FixedColumnWidth(
          wifiWidth,
        ),

        9: const pw.FixedColumnWidth(
          noteWidth,
        ),
      },

      defaultVerticalAlignment:
          pw.TableCellVerticalAlignment.middle,

      children: List.generate(
        detail.length,
        (index) {
          final d = detail[index];

          return pw.TableRow(
            children: [
              tableDataCell(
                '${index + 1}',
              ),

              tableDataCell(
                d.nomorSarana,
              ),

              tableDataCell(
                mapStatusText(d.cctv),
              ),

              tableDataCell(
                mapStatusText(d.backup),
              ),

              tableDataCell(
                mapStatusText(d.sisiA),
              ),

              tableDataCell(
                mapStatusText(d.sisiE),
              ),

              tableDataCell(
                mapStatusText(d.tdKecil),
              ),

              tableDataCell(
                mapStatusText(d.tdBesar),
              ),

              tableDataCell(
                mapStatusText(d.wifi),
              ),

              tableDataCell(
                d.keterangan.isEmpty
                    ? '-'
                    : d.keterangan,
                alignLeft: true,
              ),
            ],
          );
        },
      ),
    );

    //========================================================
    // RETURN
    //========================================================

    return pw.SizedBox(
      width: totalTableWidth,

      child: pw.Column(
        crossAxisAlignment:
            pw.CrossAxisAlignment.start,

        children: [
          header,
          dataTable,
        ],
      ),
    );
  }

  //==========================================================
  // CATATAN
  //==========================================================

  pw.Widget buildCatatanSection({
    String? locotrack,
    String? locoId,
    String? nomorSaranaLoco,
    required String catatan,
    String? catatanKeseluruhan,
  }) {
    final locotrackLabel = locotrack == 'B'
        ? 'BAIK'
        : locotrack == 'R'
            ? 'RUSAK'
            : locotrack == 'T'
                ? 'TIADA'
                : (locotrack?.toUpperCase() ?? '-');

    pw.Widget cell(String text, {
      bool header = false,
      int flex = 1,
      pw.Alignment align = pw.Alignment.center,
    }) {
      return pw.Expanded(
        flex: flex,
        child: pw.Container(
          padding: const pw.EdgeInsets.all(3),
          alignment: align,
          decoration: pw.BoxDecoration(
            border: pw.Border.all(width: 0.7, color: PdfColors.black),
            color: header ? PdfColors.grey300 : null,
          ),
          child: pw.Text(
            text,
            style: pw.TextStyle(
              fontSize: 7.5,
              fontWeight: header
                  ? pw.FontWeight.bold
                  : pw.FontWeight.normal,
            ),
          ),
        ),
      );
    }

    return pw.Column(
      crossAxisAlignment: pw.CrossAxisAlignment.stretch,
      children: [
        pw.Text(
          'Pemeriksaan Locotrack :',
          style: pw.TextStyle(
            fontSize: 8,
            fontWeight: pw.FontWeight.bold,
          ),
        ),

        pw.SizedBox(height: 3),

        // 4-Column Locotrack Table: No Sarana | Locotrack ID | Kondisi | Keterangan
        pw.Row(
          children: [
            cell('No. Sarana', header: true, flex: 23),
            cell('Locotrack ID', header: true, flex: 25),
            cell('Kondisi', header: true, flex: 22),
            cell('Keterangan', header: true, flex: 30),
          ],
        ),
        pw.Row(
          children: [
            cell(
              (nomorSaranaLoco != null && nomorSaranaLoco.isNotEmpty)
                  ? nomorSaranaLoco
                  : '-',
              flex: 23,
              align: pw.Alignment.centerLeft,
            ),
            cell(
              (locoId != null && locoId.isNotEmpty) ? locoId : '-',
              flex: 25,
            ),
            cell(locotrackLabel, flex: 22),
            cell(
              (catatan.isNotEmpty) ? catatan : '-',
              flex: 30,
              align: pw.Alignment.centerLeft,
            ),
          ],
        ),

        pw.SizedBox(height: 8),

        pw.Text(
          'Catatan :',
          style: pw.TextStyle(
            fontSize: 8,
            fontWeight: pw.FontWeight.bold,
          ),
        ),

        pw.SizedBox(height: 3),

        pw.Container(
          height: 30,

          alignment:
              pw.Alignment.topLeft,

          padding:
              const pw.EdgeInsets.all(4),

          decoration:
              pw.BoxDecoration(
            border:
                pw.Border.all(
              width: 0.7,
              color:
                  PdfColors.black,
            ),
          ),

          child: pw.Text(
            (catatanKeseluruhan != null && catatanKeseluruhan.isNotEmpty)
                ? catatanKeseluruhan
                : '-',
            style:
                const pw.TextStyle(
              fontSize: 7.5,
            ),
          ),
        ),
      ],
    );
  }

  //==========================================================
  // FOOTER
  //==========================================================

  pw.Widget buildFooter({
    required String namaMengetahui,
    required String nippMengetahui,
    String? jabatanMengetahui,
    required String namaPetugas,
    required String nipp,
  }) {
    return pw.Row(
      crossAxisAlignment: pw.CrossAxisAlignment.start,
      children: [
        //====================================================
        // MENGETAHUI
        //====================================================

        pw.Expanded(
          flex: 1,
          child: pw.Column(
            crossAxisAlignment: pw.CrossAxisAlignment.center,
            children: [
              pw.Text(
                'Mengetahui,',
                style: const pw.TextStyle(fontSize: 8.5),
              ),
              if (jabatanMengetahui != null && jabatanMengetahui.isNotEmpty)
                pw.Text(
                  jabatanMengetahui,
                  style: pw.TextStyle(
                    fontSize: 8,
                    fontWeight: pw.FontWeight.bold,
                  ),
                ),
              pw.SizedBox(height: 35),

              pw.Text(
                namaMengetahui,
                textAlign:
                    pw.TextAlign.center,

                style: pw.TextStyle(
                  fontSize: 8.5,
                  fontWeight:
                      pw.FontWeight.bold,
                  decoration:
                      pw.TextDecoration.underline,
                ),
              ),

              pw.SizedBox(
                height: 3,
              ),

              pw.Text(
                'NIPP. $nippMengetahui',
                style:
                    const pw.TextStyle(
                  fontSize: 8.5,
                ),
              ),
            ],
          ),
        ),

        //====================================================
        // PETUGAS
        //====================================================

        pw.Expanded(
          flex: 1,

          child: pw.Column(
            crossAxisAlignment:
                pw.CrossAxisAlignment.center,

            children: [
              pw.Text(
                'Petugas,',
                style:
                    const pw.TextStyle(
                  fontSize: 9,
                ),
              ),

              pw.SizedBox(
                height: 40,
              ),

              pw.Text(
                namaPetugas.isEmpty
                    ? '.................................'
                    : namaPetugas,

                textAlign:
                    pw.TextAlign.center,

                style: pw.TextStyle(
                  fontSize: 8.5,
                  fontWeight:
                      pw.FontWeight.bold,
                  decoration:
                      pw.TextDecoration.underline,
                ),
              ),

              pw.SizedBox(
                height: 3,
              ),

              pw.Text(
                'NIPP. ${
                  nipp.isEmpty
                      ? '................'
                      : nipp
                }',

                style:
                    const pw.TextStyle(
                  fontSize: 8.5,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}