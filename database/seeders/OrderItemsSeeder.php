<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'order_id' => 5,
                'id_produk' => 2,
                'quantity' => 1,
                'price_at_purchase' => 45000,
            ],
        ];

        DB::table('order_items')->insert($items);
    }
}
