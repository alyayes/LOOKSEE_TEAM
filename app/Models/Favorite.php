<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorite';
    protected $primaryKey = 'id_fav';
    protected $fillable = [
        'user_id',
        'id_produk'
    ];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
