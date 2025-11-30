<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    
    protected $table = 'produk_looksee';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'gambar_produk', 
        'nama_produk', 
        'deskripsi', 
        'harga', 
        'kategori',
        'preferensi', 
        'mood',
        'stock',
    ];

    // public $timestamps = false; 
}

