<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeretaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kereta')->insert([

            [
                'no_ka' => '86',
                'nama_ka' => 'Argo Sindoro',
                'relasi' => 'Semarang Tawang - Gambir',
                'jam_berangkat' => '06:15:00',
                'jam_datang' => '12:30:00'
            ],

            [
                'no_ka' => '87',
                'nama_ka' => 'Argo Muria',
                'relasi' => 'Semarang Tawang - Gambir',
                'jam_berangkat' => '16:00:00',
                'jam_datang' => '22:10:00'
            ],

            [
                'no_ka' => '89',
                'nama_ka' => 'Lodaya',
                'relasi' => 'Solo Balapan - Bandung',
                'jam_berangkat' => '08:00:00',
                'jam_datang' => '16:20:00'
            ]

        ]);
    }
}