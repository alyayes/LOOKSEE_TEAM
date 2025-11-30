<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'order_id' => 5,
                'user_id' => 5,
                'address_id' => 1, 
                'total_price' => 45000,
                'shipping_cost' => 10000,
                'grand_total' => 55000,
                'status' => 'paid',
                'order_date' => now(),
                'shipping_method' => 'JNE REG',
            ],
        ];

        DB::table('orders')->insert($orders);
    }
}
