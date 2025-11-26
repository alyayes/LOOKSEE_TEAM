<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {

            $exists = DB::table('user_address')
                ->where('user_id', $order->user_id)
                ->where('full_address', $order->alamat_lengkap ?? $order->full_address ?? '')
                ->where('city', $order->kota ?? $order->city ?? '')
                ->exists();

            if (!$exists) {

                $hasAddress = DB::table('user_address')
                    ->where('user_id', $order->user_id)
                    ->exists();

                DB::table('user_address')->insert([
                    'user_id'       => $order->user_id,
                    'receiver_name' => $order->nama_penerima ?? $order->receiver_name ?? '',
                    'phone_number'  => $order->no_telepon ?? $order->phone_number ?? '',
                    'province'      => $order->provinsi ?? $order->province ?? '',
                    'city'          => $order->kota ?? $order->city ?? '',
                    'district'      => $order->kecamatan ?? $order->district ?? '',
                    'postal_code'   => $order->kode_pos ?? $order->postal_code ?? '',
                    'full_address'  => $order->alamat_lengkap ?? $order->full_address ?? '',
                    'is_default'    => !$hasAddress, 
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
