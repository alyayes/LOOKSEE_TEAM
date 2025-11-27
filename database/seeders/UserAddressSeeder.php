<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserAddress; // <--- INI YANG TADINYA HILANG (PENTING!)

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {
            
            $fullAddress = $order->alamat_lengkap ?? $order->full_address ?? '';
            $city        = $order->kota ?? $order->city ?? '';
            $userId      = $order->user_id;

            if (empty($fullAddress) || empty($userId)) {
                continue;
            }

            $exists = UserAddress::where('user_id', $userId)
                        ->where('full_address', $fullAddress)
                        ->where('city', $city)
                        ->exists();

            if (!$exists) {
                $hasAddress = UserAddress::where('user_id', $userId)->exists();

                UserAddress::create([
                    'user_id'       => $userId,
                    'receiver_name' => $order->nama_penerima ?? $order->receiver_name ?? 'No Name',
                    'phone_number'  => $order->no_telepon ?? $order->phone_number ?? '-',
                    'province'      => $order->provinsi ?? $order->province ?? '-',
                    'city'          => $city,
                    'district'      => $order->kecamatan ?? $order->district ?? '-',
                    'postal_code'   => $order->kode_pos ?? $order->postal_code ?? '-',
                    'full_address'  => $fullAddress,
                    'is_default'    => !$hasAddress,
                ]);
            }
        }
    }
}