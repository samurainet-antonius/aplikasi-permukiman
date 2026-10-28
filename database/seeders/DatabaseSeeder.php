<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Petugas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding an admin user
        $user = User::create([
                'name' => 'Antonius A',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'slug' => 'admin'
        ]);

        $petugas = Petugas::create([
            'users_id' => $user->id,
            'province_code' => 12,
            'city_code' => 1207,
            'district_code' => '120701',
            'village_code' => '1207012002',
            'jabatan' => 'Superadmin',
            'nomer_hp' => '08999239159',
        ]);

        $this->call([
            PermissionsSeeder::class,
            ProvincesSeeder::class,
            CitiesSeeder::class,
            DistrictsSeeder::class,
            VillagesSeeder::class,
            KriteriaSeeder::class,
        ]);
    }
}
