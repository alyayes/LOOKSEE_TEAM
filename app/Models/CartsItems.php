<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartsItems extends Model
{
    use HasFactory;
    
    protected $table = 'carts_items';    
    public $timestamps = true; 
    protected $fillable = ['user_id', 'product_id', 'quantity', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id', 'id_produk');
    }
}