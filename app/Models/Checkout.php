<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'checkouts';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone_number',
        'delivery_address',
        'district',
        'city',
        'province',
        'postal_code',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_provider_id', 
        'total_price',         
        'grand_total',         
        'status',              
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CheckoutItem::class, 'checkout_id');
    }
}