<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartsItems;
use App\Models\UserAddress;
use App\Models\PaymentMethod;
use App\Models\EwalletTransferDetail;
use App\Models\BankTransferDetail;
use App\Models\User;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = 1;
        
        $user = User::where('user_id', $userId)->first(); 
        
        $cartData = CartsItems::where('user_id', $userId)
                      ->with('produk')
                      ->get();

        $cart_items = [];
        $total_selected_price = 0;

        foreach ($cartData as $item) {
            if ($item->produk) {
                $subtotal = $item->produk->harga * $item->quantity;
                $total_selected_price += $subtotal;

                $cart_items[$item->id_produk] = [
                    'nama_produk'   => $item->produk->nama_produk,
                    'harga'         => $item->produk->harga,
                    'gambar_produk' => $item->produk->gambar_produk,
                    'quantity'      => $item->quantity,
                    'stock'         => $item->produk->stock,
                ];
            }
        }

        $shipping_cost = 20000;
        $grand_total = $total_selected_price + $shipping_cost;

        $all_addresses = UserAddress::where('user_id', $userId)
                        ->orderBy('is_default', 'desc')
                        ->get();

        $address = $all_addresses->first();

        $delivery_data = [
            'id'          => $address->id ?? null,
            'name'        => $address->receiver_name ?? ($user ? $user->name : 'Guest'),
            'phone'       => $address->phone_number ?? '-',
            'address'     => $address->full_address ?? 'Belum ada alamat',
            'city'        => $address->city ?? '-',
            'district'    => $address->district ?? '-',
            'province'    => $address->province ?? '-',
            'postal_code' => $address->postal_code ?? '-',
        ];

        $main_payment_methods = PaymentMethod::all();
        $e_wallet_options     = EwalletTransferDetail::all();
        $bank_options         = BankTransferDetail::all(); 

        return view('checkout.checkout', compact(
            'cart_items',
            'total_selected_price',
            'shipping_cost',
            'grand_total',
            'delivery_data',
            'all_addresses',
            'main_payment_methods',
            'e_wallet_options',
            'bank_options'
        ));
    }

    public function process(Request $request)
    {
        return redirect()->route('homepage')->with('success', 'Order berhasil dibuat!');
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required',
            'phone_number'  => 'required',
            'full_address'  => 'required',
            'province'      => 'required',
            'city'          => 'required',
            'postal_code'   => 'required',
        ]);

        $userId = 1;

        $isFirst = !UserAddress::where('user_id', $userId)->exists();

        UserAddress::create([
            'user_id'       => $userId,
            'receiver_name' => $request->receiver_name,
            'phone_number'  => $request->phone_number,
            'full_address'  => $request->full_address,
            'province'      => $request->province,
            'city'          => $request->city,
            'district'      => $request->district ?? '-',
            'postal_code'   => $request->postal_code,
            'is_default'    => $isFirst ? true : false,
        ]);

        return redirect()->back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);
        
        $address->update($request->all());

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function deleteAddress($id)
    {
        $address = UserAddress::findOrFail($id);
        
        $address->delete();

        return redirect()->back()->with('success', 'Alamat berhasil dihapus!');
    }
}