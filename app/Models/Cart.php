<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // 
    protected $table = 'cart';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    protected $filable = ['user_id', 'id_produk', 'quantity', 'added_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
