<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Kader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin Posyandu',
            'email'    => 'admin@posyandu.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $kaderUser = User::create([
            'name'     => 'Siti Kader',
            'email'    => 'kader@posyandu.com',
            'password' => Hash::make('password'),
            'role'     => 'kader',
        ]);

        Kader::create([
            'user_id'      => $kaderUser->id,
            'nama_lengkap' => 'Siti Aminah',
            'no_hp'        => '08123456789',
            'alamat'       => 'Jl. Melati No.10',
            'wilayah'      => 'RT 01/RW 02',
        ]);
    }
}