<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
                'id' => 1,
                'user_id' => 5,
                'receiver_name' => 'Citra Kirana',
                'phone_number' => '081234567890',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'Mojang Priangan',
                'postal_code' => '40000',
                'full_address' => 'Kos Citra Kirana, Jl. Panjang No. 12',
                'is_default' => 1,
            ],

        ];

        DB::table('user_address')->insert($addresses);
    }
}
