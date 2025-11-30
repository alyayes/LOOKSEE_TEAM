<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EwalletTransferDetail extends Model
{
    //
    use HasFactory;

    protected $table = 'ewallet_transfer_details';
    protected $primaryKey = 'e_wallet_payment_id'; 

    protected $fillable = [
        'ewallet_provider_name',
        'method_id',
        'phone_number',
        'e_wallet_account_id'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }
}
