<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
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
=======

       UserAdress::factory()->count(10)->create();
>>>>>>> ac45bf6855e45eb26551de48b5ab39479d467c31
    }
}

