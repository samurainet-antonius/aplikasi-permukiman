<?php

namespace Database\Seeders;
use App\Models\Kriteria;

use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kriteria::insert([
            [
                'id' => '1',
                'nama' => 'Bangunan',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '2',
                'nama' => 'Legalitas dan Status Lahan',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '3',
                'nama' => 'Sosial Ekonomi',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '4',
                'nama' => 'Jalan Lingkungan',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '5',
                'nama' => 'Persampahan',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '6',
                'nama' => 'Drainase',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '7',
                'nama' => 'Air Limbah',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '8',
                'nama' => 'Air Minum',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ],
            [
                'id' => '9',
                'nama' => 'Proteksi Kebakaran',
                'flag_pakai' => '1',
                'created_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
