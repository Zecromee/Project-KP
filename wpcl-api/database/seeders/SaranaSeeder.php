<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sarana')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('sarana')->insert([
            // Kereta 1: Argo Sindoro (ID: 1)
            [
                'id_kereta' => 1,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02404',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'YK',
            ],
            [
                'id_kereta' => 1,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02403',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'YK',
            ],
            [
                'id_kereta' => 1,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02402',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'YK',
            ],
            [
                'id_kereta' => 1,
                'kode_sarana' => 'M1',
                'nomor_sarana' => '02412',
                'seri_sarana' => 'Kereta Makan',
                'depo_induk' => 'YK',
            ],
            [
                'id_kereta' => 1,
                'kode_sarana' => 'P',
                'nomor_sarana' => '02401',
                'seri_sarana' => 'Pembangkit',
                'depo_induk' => 'YK',
            ],

            // Kereta 2: Argo Muria (ID: 2)
            [
                'id_kereta' => 2,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02405',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'SMT',
            ],
            [
                'id_kereta' => 2,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02406',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'SMT',
            ],
            [
                'id_kereta' => 2,
                'kode_sarana' => 'M1',
                'nomor_sarana' => '02413',
                'seri_sarana' => 'Kereta Makan',
                'depo_induk' => 'SMT',
            ],
            [
                'id_kereta' => 2,
                'kode_sarana' => 'P',
                'nomor_sarana' => '02408',
                'seri_sarana' => 'Pembangkit',
                'depo_induk' => 'SMT',
            ],

            // Kereta 3: Lodaya (ID: 3)
            [
                'id_kereta' => 3,
                'kode_sarana' => 'K1',
                'nomor_sarana' => '02407',
                'seri_sarana' => 'Eksekutif',
                'depo_induk' => 'SLO',
            ],
            [
                'id_kereta' => 3,
                'kode_sarana' => 'K3',
                'nomor_sarana' => '02410',
                'seri_sarana' => 'Ekonomi Premium',
                'depo_induk' => 'SLO',
            ],
            [
                'id_kereta' => 3,
                'kode_sarana' => 'K3',
                'nomor_sarana' => '02411',
                'seri_sarana' => 'Ekonomi Premium',
                'depo_induk' => 'SLO',
            ],
            [
                'id_kereta' => 3,
                'kode_sarana' => 'MP3',
                'nomor_sarana' => '02409',
                'seri_sarana' => 'Kereta Makan & Pembangkit',
                'depo_induk' => 'SLO',
            ],
        ]);
    }
}