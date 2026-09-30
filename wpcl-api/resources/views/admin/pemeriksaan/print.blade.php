<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULIR MONITORING - {{ $pemeriksaan->no_ref ?? $pemeriksaan->id_pemeriksaan }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #e9ecef;
        }
        .page-container {
            width: 100%;
            max-width: 1120px;
            margin: 15px auto;
            background: #fff;
            padding: 20px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-radius: 4px;
        }
        .text-center { text-align: center; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ==========================================================
           1. HEADER UTAMA (Persis Mobile PDF)
           ========================================================== */
        .header-table {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 12px;
        }
        .header-table td {
            border: 1px solid #000;
            vertical-align: middle;
        }
        .header-logo-cell {
            width: 18%;
            text-align: center;
            padding: 4px 8px;
        }
        .kai-logo-text {
            font-size: 20pt;
            font-weight: 900;
            color: #002B66;
            letter-spacing: 1px;
            font-style: italic;
        }
        .kai-logo-sub {
            font-size: 8pt;
            font-weight: bold;
            color: #FF7300;
            margin-top: -4px;
        }
        .header-title-cell {
            width: 58%;
            text-align: center;
            padding: 6px 4px;
        }
        .header-title-main {
            font-size: 11pt;
            font-weight: bold;
        }
        .header-title-sub {
            font-size: 9pt;
            margin-top: 2px;
        }
        .header-meta-cell {
            width: 24%;
            padding: 0;
        }
        .meta-table {
            width: 100%;
            font-size: 8pt;
        }
        .meta-table td {
            border: none;
            border-bottom: 1px solid #000;
            padding: 2.5px 6px;
        }
        .meta-table tr:last-child td {
            border-bottom: none;
        }

        /* Header Baris 2 */
        .terbatas-cell {
            background-color: #fff;
            text-align: center;
            padding: 3px;
        }
        .terbatas-badge {
            display: inline-block;
            background-color: #ffeb3b;
            padding: 2px 14px;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .banner-cell {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 4px;
        }

        /* ==========================================================
           2. IDENTITAS PEMERIKSAAN
           ========================================================== */
        .identity-section {
            width: 320px;
            margin-bottom: 10px;
            font-size: 8.5pt;
        }
        .identity-section td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        /* ==========================================================
           3. CHECKLIST TABLE (100% Fit to Right Edge)
           ========================================================== */
        .table-data {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 4.5px 2px;
            font-size: 7.5pt;
            word-wrap: break-word;
        }
        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .th-nama-ka {
            background-color: #f2f2f2;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 5px;
        }

        /* ==========================================================
           4. CATATAN
           ========================================================== */
        .catatan-label {
            font-weight: bold;
            font-size: 8.5pt;
            margin-bottom: 2px;
        }
        .catatan-box {
            width: 100%;
            border: 1px solid #000;
            padding: 6px 10px;
            min-height: 30px;
            margin-bottom: 14px;
            font-size: 8pt;
            line-height: 1.4;
        }

        /* ==========================================================
           5. FOOTER / SIGNATURES
           ========================================================== */
        .footer-table {
            width: 100%;
            font-size: 8.5pt;
        }
        .footer-table td {
            vertical-align: top;
            padding: 0 8px;
        }
        .signature-line {
            text-decoration: underline;
            font-weight: bold;
        }
        .signature-space {
            height: 48px;
        }

        /* ==========================================================
           DESKTOP ACTION BAR & MEDIA PRINT
           ========================================================== */
        .no-print {
            max-width: 1120px;
            margin: 15px auto 0 auto;
            padding: 12px 20px;
            background-color: #002B66;
            color: #fff;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-print {
            background-color: #FF7300;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10pt;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn-print:hover {
            background-color: #e66700;
        }
        .btn-close-win {
            background-color: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 4px;
            cursor: pointer;
            font-size: 9pt;
        }
        .btn-close-win:hover {
            background-color: rgba(255,255,255,0.3);
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</head>
<body>
    <!-- Top Action Bar for Desktop Browser Preview -->
    <div class="no-print">
        <div>
            <strong>Preview Dokumen Hasil Pemeriksaan (DHP WPCL)</strong>
            <div style="font-size: 8pt; opacity: 0.85;">Dialog print akan otomatis muncul. Pilih "Simpan sebagai PDF" untuk export, atau gunakan tombol di bawah.</div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak Ulang</button>
            <button onclick="window.close()" class="btn-close-win">Tutup</button>
        </div>
    </div>

    @php
        $logoPath = public_path('images/logo_kai.png');
        $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : asset('images/logo_kai.png');
    @endphp

    <div class="page-container">
        <!-- 1. HEADER UTAMA -->
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ $logoSrc }}" alt="KAI Logo" style="height: 30px; max-height: 34px; width: auto; object-fit: contain; display: inline-block;">
                </td>
                <td class="header-title-cell">
                    <div class="header-title-main">PT. KERETA API INDONESIA (PERSERO)</div>
                    <div class="header-title-sub">Sistem Informasi</div>
                </td>
                <td class="header-meta-cell" rowspan="2">
                    <table class="meta-table">
                        <tr>
                            <td width="55%"><strong>No. Dokumen</strong></td>
                            <td width="5%">:</td>
                            <td width="40%">{{ $pemeriksaan->no_dokumen ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Versi</strong></td>
                            <td>:</td>
                            <td>{{ $pemeriksaan->versi_dokumen ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Halaman</strong></td>
                            <td>:</td>
                            <td>1</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="terbatas-cell">
                    <div class="terbatas-badge">TERBATAS</div>
                </td>
                <td class="banner-cell">
                    FORMULIR MONITORING
                </td>
            </tr>
        </table>

        <!-- 2. IDENTITAS PEMERIKSAAN -->
        <table class="identity-section">
            <tr>
                <td width="35%">No Ref</td>
                <td width="5%">:</td>
                <td width="60%">{{ $pemeriksaan->no_ref ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $pemeriksaan->tanggal }}</td>
            </tr>
            <tr>
                <td>Business Area</td>
                <td>:</td>
                <td>{{ $pemeriksaan->business_area ?? 'DAOP 6' }}</td>
            </tr>
        </table>

        <!-- 3. CHECKLIST DATA TABLE (100% Fit to Right Edge) -->
        <table class="table-data">
            <thead>
                <!-- Baris Header 1 -->
                <tr>
                    <th rowspan="4" style="width: 3.5%;">No</th>
                    <th rowspan="4" style="width: 9%;">No.<br>Sarana</th>
                    <th colspan="7" class="th-nama-ka">
                        NAMA KA : {{ strtoupper($pemeriksaan->kereta->nama_ka ?? ($pemeriksaan->kereta->no_ka ?? '-')) }}
                    </th>
                    <th rowspan="4" style="width: 42%;">NOTE</th>
                </tr>
                <!-- Baris Header 2 -->
                <tr>
                    <th colspan="2" style="width: 12%;">CCTV</th>
                    <th colspan="4" style="width: 27%;">PIDS</th>
                    <th style="width: 6.5%;">WIFI</th>
                </tr>
                <!-- Baris Header 3 -->
                <tr>
                    <th rowspan="2" style="width: 6%;">BERFUNGSI</th>
                    <th rowspan="2" style="width: 6%;">TERBACKUP</th>
                    <th colspan="2" style="width: 13.5%;">PIDS LUAR</th>
                    <th colspan="2" style="width: 13.5%;">PIDS DALAM</th>
                    <th rowspan="2" style="width: 6.5%;">BERFUNGSI</th>
                </tr>
                <!-- Baris Header 4 -->
                <tr>
                    <th style="width: 6.75%;">SISI A</th>
                    <th style="width: 6.75%;">SISI E</th>
                    <th style="width: 6.75%;">TD KECIL</th>
                    <th style="width: 6.75%;">TD BESAR</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $mapText = function($v) {
                        if ($v == 'B' || $v == 'Baik') return 'Baik';
                        if ($v == 'R' || $v == 'Rusak') return 'Rusak';
                        if ($v == 'T' || $v == 'Tiada') return 'Tiada';
                        return $v ?? '-';
                    };
                @endphp
                @foreach($pemeriksaan->detailPemeriksaan as $idx => $d)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">
                            {{ $d->sarana->kode_sarana ?? '' }} {{ $d->sarana->nomor_sarana ?? '-' }}
                        </td>
                        <td class="text-center">{{ $mapText($d->cctv) }}</td>
                        <td class="text-center">{{ $mapText($d->backup) }}</td>
                        <td class="text-center">{{ $mapText($d->sisi_a) }}</td>
                        <td class="text-center">{{ $mapText($d->sisi_e) }}</td>
                        <td class="text-center">{{ $mapText($d->td_kecil) }}</td>
                        <td class="text-center">{{ $mapText($d->td_besar) }}</td>
                        <td class="text-center">{{ $mapText($d->wifi) }}</td>
                        <td class="text-start" style="padding-left: 6px;">{{ $d->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 4. CATATAN & LOCOTRACK -->
        <div class="catatan-label">Pemeriksaan Locotrack :</div>
        @php
            $mapLocotrack = function($v) {
                if ($v == 'B') return 'BAIK';
                if ($v == 'R') return 'RUSAK';
                return 'TIADA';
            };
        @endphp
        <table class="table-data" style="margin-bottom: 8px;">
            <thead>
                <tr>
                    <th style="width: 23%; background-color: #f2f2f2;">No. Sarana</th>
                    <th style="width: 25%; background-color: #f2f2f2;">Locotrack ID</th>
                    <th style="width: 22%; background-color: #f2f2f2;">Kondisi</th>
                    <th style="width: 30%; background-color: #f2f2f2;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{ $pemeriksaan->nomor_sarana_loco ?? '-' }}</td>
                    <td class="text-center">{{ $pemeriksaan->loco_id ?? '-' }}</td>
                    <td class="text-center">{{ $mapLocotrack($pemeriksaan->locotrack) }}</td>
                    <td class="text-start" style="padding-left: 6px;">{{ $pemeriksaan->catatan ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="catatan-label">Catatan :</div>
        <div class="catatan-box">
            @if(!empty($pemeriksaan->catatan_keseluruhan))
                {{ $pemeriksaan->catatan_keseluruhan }}
            @else
                -
            @endif
        </div>

        <!-- 5. SIGNATURES -->
        <table class="footer-table">
            <tr>
                <td width="50%" class="text-center">
                    <div>Mengetahui,</div>
                    <div>{{ $pemeriksaan->pejabat_jabatan ?? ($pemeriksaan->pejabat->jabatan ?? 'Manager IT') }}</div>
                    <div class="signature-space"></div>
                    <div class="signature-line">{{ $pemeriksaan->pejabat_nama ?? ($pemeriksaan->pejabat->nama ?? 'Pitra Argehermanu') }}</div>
                    <div>NIPP. {{ $pemeriksaan->pejabat_nipp ?? ($pemeriksaan->pejabat->nipp ?? '46002') }}</div>
                </td>
                <td width="50%" class="text-center">
                    <div>Petugas,</div>
                    <div style="height: 14px;"></div>
                    <div class="signature-space"></div>
                    <div class="signature-line">{{ $pemeriksaan->nama_petugas }}</div>
                    <div>NIPP. {{ $pemeriksaan->nipp }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
