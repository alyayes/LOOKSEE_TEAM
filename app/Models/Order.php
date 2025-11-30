<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'address_id',
        'total_price',
        'shipping_cost',
        'grand_total',
        'status',
        'order_date',
        'shipping_method'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id'); // FK sesuai tabel orders
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }
    
}