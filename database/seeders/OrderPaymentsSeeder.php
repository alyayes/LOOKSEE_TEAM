<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderPaymentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_payment')->insert([
            'order_id' => 5,
            'method_id' => 1, 
            'amount' => 55000,
            'payment_date' => now(),
            'transaction_status' => 'success',
            'transaction_code' => 'TRX-ORD-5-' . time(),
        ]);
    }
}
