<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {

       UserAdress::factory()->count(10)->create();
    }
}

