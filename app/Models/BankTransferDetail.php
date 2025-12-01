<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransferDetail extends Model
{
    use HasFactory;

    protected $table = 'bank_transfer_details';
    protected $primaryKey = 'bank_payment_id'; 
    protected $fillable = [
        'bank_name',
        'method_id',
        'account_number',
        'account_holder_name'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }
}