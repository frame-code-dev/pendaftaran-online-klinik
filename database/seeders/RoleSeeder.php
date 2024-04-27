<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_name = ['admin','pasien','dokter','petugas-klinik','petugas-pendaftaran'];

        for ($i=0; $i < count($user_name) ; $i++) {
            $role = new Role;
            $role->name = $user_name[$i];
            $role->save();
            $permissions = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
        }
    }
}
