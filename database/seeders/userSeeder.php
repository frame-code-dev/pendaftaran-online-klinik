<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = ['admin','pasien','dokter','petugas-klinik','petugas-pendaftaran'];
        for ($i=0; $i < count($name) ; $i++) {
            $user = new User;
            $user->name = $name[$i];
            $user->email = $name[$i].'@mail.com';
            $user->password = Hash::make('password');
            $user->save();

            $user->assignRole($name[$i]);
        }
    }
}
