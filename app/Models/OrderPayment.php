<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $table = 'order_payment';
    
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'method_id',
        'amount',
        'payment_date',
        'transaction_status',
        'transaction_code',
        'bank_payment_id_fk',
        'e_wallet_payment_id_fk',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id', 'method_id');
    }

    public function bankDetail()
    {
        return $this->belongsTo(BankTransferDetail::class, 'bank_payment_id_fk', 'bank_payment_id');
    }

    public function ewalletDetail()
    {
        return $this->belongsTo(EwalletTransferDetail::class, 'e_wallet_payment_id_fk', 'e_wallet_payment_id');
    }
}