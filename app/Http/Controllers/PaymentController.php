<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('ewallet_transfer_details')->truncate();
        DB::table('bank_transfer_details')->truncate();

        Schema::enableForeignKeyConstraints();
        

        $ewallets = [
            ['ewallet_provider_name' => 'GoPay', 'account_number' => '082127222144', 'account_name' => 'LOOKSEE STORE'],
            ['ewallet_provider_name' => 'OVO', 'account_number' => '082127222144', 'account_name' => 'LOOKSEE STORE'],
            ['ewallet_provider_name' => 'Dana', 'account_number' => '082127222144', 'account_name' => 'LOOKSEE STORE'],
            ['ewallet_provider_name' => 'ShopeePay', 'account_number' => '082127222144', 'account_name' => 'LOOKSEE STORE'],
        ];

        foreach ($ewallets as $ew) {
            DB::table('ewallet_transfer_details')->insert([
                'ewallet_provider_name' => $ew['ewallet_provider_name'],
                'account_number' => $ew['account_number'],
                'account_name' => $ew['account_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $banks = [
            [
                'bank_name' => 'MANDIRI', 
                'account_number' => '1320028954056', 
                'account_holder_name' => 'LOOKSEE STORE',
                'bank_logo' => 'mandiri.png'
            ],
            [
                'bank_name' => 'SEABANK', 
                'account_number' => '901566333248', 
                'account_holder_name' => 'LOOKSEE STORE',
                'bank_logo' => 'seabank.png'
            ],
        ];

        foreach ($banks as $bank) {
            DB::table('bank_transfer_details')->insert([
                'bank_name' => $bank['bank_name'],
                'account_number' => $bank['account_number'],
                'account_holder_name' => $bank['account_holder_name'],
                'bank_logo' => $bank['bank_logo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}