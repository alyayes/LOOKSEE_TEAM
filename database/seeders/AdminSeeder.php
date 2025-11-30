<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@looksee.com'], // cek kalau email sudah ada
            [
                'username' => 'admin',
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );
    }
}
