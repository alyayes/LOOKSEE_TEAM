<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use App\Models\EwalletTransferDetail;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $bank = PaymentMethod::create(['method_name' => 'Bank Transfer']); 
        $ewallet = PaymentMethod::create(['method_name' => 'E-Wallet']);
        $cod = PaymentMethod::create(['method_name' => 'COD']);

        $wallets = [
            [
                'ewallet_provider_name' => 'Dana',
                'phone_number' => '081234567890',
            ],
            [
                'ewallet_provider_name' => 'OVO',
                'phone_number' => '081122334455',
            ],
            [
                'ewallet_provider_name' => 'ShopeePay',
                'phone_number' => '087766554433',
            ],
            [
                'ewallet_provider_name' => 'Gopay',
                'phone_number' => '089988776655',
            ],
        ];

        foreach ($wallets as $wallet) {
            EwalletTransferDetail::create([
                'ewallet_provider_name' => $wallet['ewallet_provider_name'],
                'method_id' => $ewallet->method_id, // Harusnya 2
                'phone_number' => $wallet['phone_number'],
                'e_wallet_account_id' => 'LOOKSEE.ID'
            ]);
        }
    }
}
