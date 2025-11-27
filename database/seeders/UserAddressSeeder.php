<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        UserAddress::factory()->count(15)->create();
    }
}