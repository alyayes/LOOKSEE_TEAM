<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\BankTransferDetail;
use App\Models\EwalletTransferDetail;

class ApiPaymentController extends Controller
{
    public function showPaymentDetails(Request $request)
    {
        $orderId = $request->query('order_id');
        if (! $orderId) {
            return response()->json(['success' => false, 'message' => 'order_id is required'], 400);
        }

        $user = $request->user();
        $order = Order::where('order_id', $orderId)->where('user_id', $user->user_id)->with('payment')->first();
        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $methods = PaymentMethod::all();
        $banks = BankTransferDetail::all();
        $ewallets = EwalletTransferDetail::all();

        return response()->json([
            'success' => true,
            'order' => $order,
            'payment_methods' => $methods,
            'bank_details' => $banks,
            'ewallet_details' => $ewallets,
        ]);
    }
}
