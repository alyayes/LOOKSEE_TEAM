<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'order_id',
        'id_produk',
        'quantity',
        'price_at_purchase',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id'); 
    }
}