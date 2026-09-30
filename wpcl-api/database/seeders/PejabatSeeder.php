<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PejabatSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pejabat')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('pejabat')->insert([
            [
                'id_pejabat' => 1,
                'nama' => 'Pitra Argehermanu',
                'nipp' => '46002',
                'jabatan' => 'Manager IT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pejabat' => 2,
                'nama' => 'Sudirjo',
                'nipp' => '43494',
                'jabatan' => 'Assman IT Support 1',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pejabat' => 3,
                'nama' => 'Winaris Ahmad Darmawan',
                'nipp' => '55867',
                'jabatan' => 'Assman IT Support 2',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
