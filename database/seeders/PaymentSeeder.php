<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert top-level payment methods: COD, E-WALLET, BANK TRANSFER
        DB::table('payment_methods')->updateOrInsert(['method_id' => 1], ['method_name' => 'COD']);
        DB::table('payment_methods')->updateOrInsert(['method_id' => 2], ['method_name' => 'E-WALLET']);
        DB::table('payment_methods')->updateOrInsert(['method_id' => 3], ['method_name' => 'BANK TRANSFER']);

        // Bank transfer providers (under BANK TRANSFER method_id = 3)
        DB::table('bank_transfer_details')->updateOrInsert(['bank_payment_id' => 1], [
            'method_id' => 3,
            'bank_name' => 'SEABANK',
            'account_number' => '901566333248',
            'account_holder_name' => 'LOOKSEE STORE'
        ]);

        DB::table('bank_transfer_details')->updateOrInsert(['bank_payment_id' => 2], [
            'method_id' => 3,
            'bank_name' => 'MANDIRI',
            'account_number' => '1320028954056',
            'account_holder_name' => 'LOOKSEE STORE'
        ]);

        // Ewallet providers (under E-WALLET method_id = 2) using the provided phone number
        DB::table('ewallet_transfer_details')->updateOrInsert(['e_wallet_payment_id' => 1], [
            'method_id' => 2,
            'ewallet_provider_name' => 'GOPAY',
            'phone_number' => '082127222144',
            'e_wallet_account_id' => '082127222144'
        ]);

        DB::table('ewallet_transfer_details')->updateOrInsert(['e_wallet_payment_id' => 2], [
            'method_id' => 2,
            'ewallet_provider_name' => 'OVO',
            'phone_number' => '082127222144',
            'e_wallet_account_id' => '082127222144'
        ]);

        DB::table('ewallet_transfer_details')->updateOrInsert(['e_wallet_payment_id' => 3], [
            'method_id' => 2,
            'ewallet_provider_name' => 'SHOPEEPAY',
            'phone_number' => '082127222144',
            'e_wallet_account_id' => '082127222144'
        ]);

        DB::table('ewallet_transfer_details')->updateOrInsert(['e_wallet_payment_id' => 4], [
            'method_id' => 2,
            'ewallet_provider_name' => 'DANA',
            'phone_number' => '082127222144',
            'e_wallet_account_id' => '082127222144'
        ]);
    }
}
