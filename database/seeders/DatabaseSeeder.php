<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'slug' => 'admin'
            ]);

        User::create([
            'name' => 'Admin Provinsi',
            'slug' => 'admin-provinsi',
            'email' => 'adminProvinsi@mail.com',
            'region_code' => '12',
            'password' => Hash::make('admin'),
        ]);

        User::create([
            'name' => 'Admin Kabupaten',
            'slug' => 'admin-kabupaten',
            'email' => 'adminKabupaten@mail.com',
            'region_code' => '1207',
            'password' => Hash::make('admin'),
        ]);

        User::create([
            'name' => 'Admin Kecamatan',
            'slug' => 'admin-kecamatan',
            'email' => 'adminKecamatan@mail.com',
            'region_code' => '120701',
            'password' => Hash::make('admin'),
        ]);

        User::create([
            'name' => 'Admin Kelurahan',
            'slug' => 'admin-kelurahan',
            'email' => 'adminKelurahan@mail.com',
            'region_code' => '1207012001',
            'password' => Hash::make('admin'),
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
