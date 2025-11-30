<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankTransferDetail;


class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $banks = [
            ['bank_name' => 'Mandiri',    'acc_num' => '1370012345678'],
            ['bank_name' => 'BCA',        'acc_num' => '0123456789'],
            ['bank_name' => 'SeaBank',    'acc_num' => '90123456789'],
            ['bank_name' => 'Permata',    'acc_num' => '701234567890'],
            ['bank_name' => 'BRI',        'acc_num' => '0012345678901'],
            ['bank_name' => 'BSI',        'acc_num' => '801234567890'],
            ['bank_name' => 'CIMB Niaga', 'acc_num' => '4012345678901'],
            ['bank_name' => 'Danamon',    'acc_num' => '301234567890'],
            ['bank_name' => 'BNI',        'acc_num' => '00987654321'],
        ];

        foreach ($banks as $bank) {
            BankTransferDetail::create([
                'bank_name'           => $bank['bank_name'],
                'method_id'           => 1,
                'account_number'      => $bank['acc_num'],
                'account_holder_name' => 'PT. LOOKSEE INDONESIA',
            ]);
        }
    }
}
