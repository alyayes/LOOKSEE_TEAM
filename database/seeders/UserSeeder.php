<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user inaya dulu
        User::create([
            'username' => 'lucyMaudy_',
            'password' => Hash::make('password123'), 
        ]);

        // 2. Baru generate user dummy sisanya
        User::factory()->count(10)->create();
    }
}