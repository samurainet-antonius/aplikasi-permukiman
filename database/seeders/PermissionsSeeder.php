<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create user role and assign existing permissions
        $currentPermissions = Permission::all();
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo($currentPermissions);

        // Create admin exclusive permissions
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'list kriteria']);
        Permission::create(['name' => 'view kriteria']);
        Permission::create(['name' => 'create kriteria']);
        Permission::create(['name' => 'update kriteria']);
        Permission::create(['name' => 'delete kriteria']);

        Permission::create(['name' => 'list subkriteria']);
        Permission::create(['name' => 'view subkriteria']);
        Permission::create(['name' => 'create subkriteria']);
        Permission::create(['name' => 'update subkriteria']);
        Permission::create(['name' => 'delete subkriteria']);

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $adminProvinsiRole = Role::create(['name' => 'admin-provinsi']);
        $adminProvinsiRole->givePermissionTo('list kriteria', 'list subkriteria');

        $adminKabupatenRole = Role::create(['name' => 'admin-kabupaten']);
        $adminKabupatenRole->givePermissionTo('list kriteria', 'list subkriteria');

        $adminKecamatanRole = Role::create(['name' => 'admin-kecamatan']);
        $adminKecamatanRole->givePermissionTo('list kriteria', 'list subkriteria');

        $adminKelurahanRole = Role::create(['name' => 'admin-kelurahan']);
        $adminKelurahanRole->givePermissionTo('list kriteria', 'list subkriteria');

        $user = \App\Models\User::whereEmail('admin@admin.com')->first();
        $adminProvinsi = \App\Models\User::whereEmail('admin@admin.com')->first();
        $adminKabupaten = \App\Models\User::whereEmail('admin@admin.com')->first();
        $adminKecamatan = \App\Models\User::whereEmail('admin@admin.com')->first();
        $adminKelurahan = \App\Models\User::whereEmail('admin@admin.com')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }

        if ($adminProvinsi) {
            $adminProvinsi->assignRole($adminProvinsiRole);
        }

        if ($adminKabupaten) {
            $adminKabupaten->assignRole($adminKabupatenRole);
        }

        if ($adminKecamatan) {
            $adminKecamatan->assignRole($adminKecamatanRole);
        }

        if ($adminKelurahan) {
            $adminKelurahan->assignRole($adminKelurahanRole);
        }
    }
}
