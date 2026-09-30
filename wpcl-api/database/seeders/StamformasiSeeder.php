<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kereta;
use App\Models\Sarana;

class StamformasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sarana')->truncate();
        DB::table('kereta')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Formasi definitions
        $formations = [
            // 1. KA 43 / 44 / 45 - Taksaka YK
            [
                'trips' => [
                    ['no_ka' => 'KA 43', 'nama_ka' => 'Taksaka YK', 'relasi' => 'YK-GMR', 'jam_berangkat' => '07:30:00', 'jam_datang' => '13:35:00'],
                    ['no_ka' => 'KA 44', 'nama_ka' => 'Taksaka YK', 'relasi' => 'GMR-YK', 'jam_berangkat' => '14:00:00', 'jam_datang' => '20:10:00'],
                    ['no_ka' => 'KA 45', 'nama_ka' => 'Taksaka YK', 'relasi' => 'YK-GMR', 'jam_berangkat' => '21:05:00', 'jam_datang' => '03:15:00'],
                ],
                'sarana' => [
                    ['kode' => 'P', 'nomor' => '02304', 'seri' => 'Pembangkit SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1lux', 'nomor' => '02404', 'seri' => 'Eksekutif Luxury SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1lux', 'nomor' => '02403', 'seri' => 'Eksekutif Luxury SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02338', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02326', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02329', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02340', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02328', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '02412', 'seri' => 'Kereta Makan SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02420', 'seri' => 'Eksekutif SS NG', 'depo' => 'JAK'],
                    ['kode' => 'K1', 'nomor' => '02339', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02337', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                ],
            ],

            // 2. KA 46 / 47 / 48 - Taksaka YK
            [
                'trips' => [
                    ['no_ka' => 'KA 46', 'nama_ka' => 'Taksaka YK', 'relasi' => 'GMR-YK', 'jam_berangkat' => '07:45:00', 'jam_datang' => '13:50:00'],
                    ['no_ka' => 'KA 47', 'nama_ka' => 'Taksaka YK', 'relasi' => 'YK-GMR', 'jam_berangkat' => '14:45:00', 'jam_datang' => '20:54:00'],
                    ['no_ka' => 'KA 48', 'nama_ka' => 'Taksaka YK', 'relasi' => 'GMR-YK', 'jam_berangkat' => '21:20:00', 'jam_datang' => '03:30:00'],
                ],
                'sarana' => [
                    ['kode' => 'P', 'nomor' => '02303', 'seri' => 'Pembangkit SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1lux', 'nomor' => '02401', 'seri' => 'Eksekutif Luxury SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1lux', 'nomor' => '02405', 'seri' => 'Eksekutif Luxury SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02334', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02341', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02327', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02335', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '02409', 'seri' => 'Kereta Makan SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02332', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02331', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02330', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02548', 'seri' => 'Eksekutif SS NG', 'depo' => 'BD'],
                ],
            ],

            // 3. Renc PLB 257B / 258B - Progo
            [
                'trips' => [
                    ['no_ka' => 'PLB 257B', 'nama_ka' => 'Progo', 'relasi' => 'LPN-PSE', 'jam_berangkat' => '13:10:00', 'jam_datang' => '20:46:00'],
                    ['no_ka' => 'PLB 258B', 'nama_ka' => 'Progo', 'relasi' => 'PSE-LPN', 'jam_berangkat' => '23:20:00', 'jam_datang' => '06:40:00'],
                ],
                'sarana' => [
                    ['kode' => 'K3', 'nomor' => '02442', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02439', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02437', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02457', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '02304', 'seri' => 'Kereta Makan SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02435', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02453', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02459', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02454', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'P', 'nomor' => '02408', 'seri' => 'Pembangkit SS NG', 'depo' => 'YK'],
                ],
            ],

            // 4. Renc PLB 107B / 110B - Senja Utama / Fajar Utama
            [
                'trips' => [
                    ['no_ka' => 'PLB 107B', 'nama_ka' => 'Senja Utama YK', 'relasi' => 'YK-PSE', 'jam_berangkat' => '17:30:00', 'jam_datang' => '00:32:00'],
                    ['no_ka' => 'PLB 110B', 'nama_ka' => 'Fajar Utama YK', 'relasi' => 'PSE-YK', 'jam_berangkat' => '07:35:00', 'jam_datang' => '15:10:00'],
                ],
                'sarana' => [
                    ['kode' => 'K1', 'nomor' => '01875', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01932', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '01911', 'seri' => 'Kereta Makan SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01818', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01942', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01933', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01953', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01928', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01937', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'P', 'nomor' => '01811', 'seri' => 'Pembangkit SS', 'depo' => 'YK'],
                ],
            ],

            // 5. PLB 84B/85B/86B/87B - Sancaka Pagi
            [
                'trips' => [
                    ['no_ka' => 'PLB 84B', 'nama_ka' => 'Sancaka Pagi', 'relasi' => 'YK-SGU', 'jam_berangkat' => '06:45:00', 'jam_datang' => '10:47:00'],
                    ['no_ka' => 'PLB 85B', 'nama_ka' => 'Sancaka Pagi', 'relasi' => 'SGU-YK', 'jam_berangkat' => '11:15:00', 'jam_datang' => '15:17:00'],
                    ['no_ka' => 'PLB 86B', 'nama_ka' => 'Sancaka Pagi', 'relasi' => 'YK-SGU', 'jam_berangkat' => '17:00:00', 'jam_datang' => '21:02:00'],
                    ['no_ka' => 'PLB 87B', 'nama_ka' => 'Sancaka Pagi', 'relasi' => 'SGU-YK', 'jam_berangkat' => '22:00:00', 'jam_datang' => '02:04:00'],
                ],
                'sarana' => [
                    ['kode' => 'P', 'nomor' => '01910', 'seri' => 'Pembangkit SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01919', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01814', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '018132', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01918', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '01910', 'seri' => 'Kereta Makan SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01930', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01946', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01954', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01959', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01936', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                ],
            ],

            // 6. PLB 109B / 108B - Fajar Utama / Senja Utama
            [
                'trips' => [
                    ['no_ka' => 'PLB 109B', 'nama_ka' => 'Fajar Utama YK', 'relasi' => 'YK-PSE', 'jam_berangkat' => '07:00:00', 'jam_datang' => '14:26:00'],
                    ['no_ka' => 'PLB 108B', 'nama_ka' => 'Senja Utama YK', 'relasi' => 'PSE-YK', 'jam_berangkat' => '19:00:00', 'jam_datang' => '02:00:00'],
                ],
                'sarana' => [
                    ['kode' => 'K1', 'nomor' => '01920', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01876', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '01907', 'seri' => 'Kereta Makan SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01948', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01952', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01941', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01935', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01956', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01816', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'P', 'nomor' => '01913', 'seri' => 'Pembangkit SS', 'depo' => 'YK'],
                ],
            ],

            // 7. Renc PLB 103B / 104B - Bogowonto
            [
                'trips' => [
                    ['no_ka' => 'PLB 103B', 'nama_ka' => 'Bogowonto', 'relasi' => 'LPN-PSE', 'jam_berangkat' => '08:15:00', 'jam_datang' => '15:54:00'],
                    ['no_ka' => 'PLB 104B', 'nama_ka' => 'Bogowonto', 'relasi' => 'PSE-LPN', 'jam_berangkat' => '18:10:00', 'jam_datang' => '01:47:00'],
                ],
                'sarana' => [
                    ['kode' => 'K3', 'nomor' => '02460', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02441', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02455', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02438', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '02423', 'seri' => 'Ekonomi SS NG', 'depo' => 'SLO'],
                    ['kode' => 'K3', 'nomor' => '02440', 'seri' => 'Ekonomi SS NG', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '02303', 'seri' => 'Kereta Makan SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02336', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '02333', 'seri' => 'Eksekutif SS NG', 'depo' => 'YK'],
                    ['kode' => 'P', 'nomor' => '02411', 'seri' => 'Pembangkit SS NG', 'depo' => 'YK'],
                ],
            ],

            // 8. Renc PLB 105B / 106B - Gajahwong
            [
                'trips' => [
                    ['no_ka' => 'PLB 105B', 'nama_ka' => 'Gajahwong', 'relasi' => 'LPN-PSE', 'jam_berangkat' => '20:40:00', 'jam_datang' => '04:33:00'],
                    ['no_ka' => 'PLB 106B', 'nama_ka' => 'Gajahwong', 'relasi' => 'PSE-LPN', 'jam_berangkat' => '07:55:00', 'jam_datang' => '15:50:00'],
                ],
                'sarana' => [
                    ['kode' => 'K3', 'nomor' => '01931', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01958', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01938', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01872', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01939', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '01806', 'seri' => 'Kereta Makan SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01881', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01908', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01874', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'P', 'nomor' => '01823', 'seri' => 'Pembangkit SS', 'depo' => 'YK'],
                ],
            ],

            // 9. PLB 81B/82B/83B/88B - Sancaka Sore
            [
                'trips' => [
                    ['no_ka' => 'PLB 81B', 'nama_ka' => 'Sancaka Sore', 'relasi' => 'SGU-YK', 'jam_berangkat' => '07:00:00', 'jam_datang' => '11:02:00'],
                    ['no_ka' => 'PLB 82B', 'nama_ka' => 'Sancaka Sore', 'relasi' => 'YK-SGU', 'jam_berangkat' => '11:30:00', 'jam_datang' => '15:32:00'],
                    ['no_ka' => 'PLB 83B', 'nama_ka' => 'Sancaka Sore', 'relasi' => 'SGU-YK', 'jam_berangkat' => '16:35:00', 'jam_datang' => '20:38:00'],
                    ['no_ka' => 'PLB 88B', 'nama_ka' => 'Sancaka Sore', 'relasi' => 'YK-SGU', 'jam_berangkat' => '22:25:00', 'jam_datang' => '02:29:00'],
                ],
                'sarana' => [
                    ['kode' => 'P', 'nomor' => '01909', 'seri' => 'Pembangkit SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01813', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01907', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01943', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'K1', 'nomor' => '01880', 'seri' => 'Eksekutif SS', 'depo' => 'YK'],
                    ['kode' => 'M1', 'nomor' => '01809', 'seri' => 'Kereta Makan SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01873', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01944', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01955', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01943', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                    ['kode' => 'K3', 'nomor' => '01945', 'seri' => 'Ekonomi SS', 'depo' => 'YK'],
                ],
            ],
        ];

        foreach ($formations as $formation) {
            foreach ($formation['trips'] as $trip) {
                $kereta = Kereta::create([
                    'no_ka' => $trip['no_ka'],
                    'nama_ka' => $trip['nama_ka'],
                    'relasi' => $trip['relasi'],
                    'jam_berangkat' => $trip['jam_berangkat'],
                    'jam_datang' => $trip['jam_datang'],
                ]);

                foreach ($formation['sarana'] as $s) {
                    Sarana::create([
                        'id_kereta' => $kereta->id_kereta,
                        'kode_sarana' => $s['kode'],
                        'nomor_sarana' => $s['nomor'],
                        'seri_sarana' => $s['seri'],
                        'depo_induk' => $s['depo'],
                    ]);
                }
            }
        }
    }
}
