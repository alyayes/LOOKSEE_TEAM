<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartsItems extends Model
{
    // 
    use HasFactory;
    
    protected $table = 'carts_items';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'id_produk', 'quantity', 'added_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
