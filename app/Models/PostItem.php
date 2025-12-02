<?php

// app/Models/PostItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostItem extends Model
{
    use HasFactory;

    // Nama tabel yang benar
    protected $table = 'post_items'; 
    
    // Primary Key yang benar
    protected $primaryKey = 'id_post_items';
    
    // Tipe data Primary Key harus INT
    protected $keyType = 'int'; 
    public $incrementing = true;
    
    // Kolom Foreign Key
    protected $fillable = [
        'id_post', 
        'id_produk', 
    ];

    public $timestamps = false; // Asumsi tabel pivot tidak menggunakan created_at/updated_at

    /**
     * Relasi ke Post (PostItem dimiliki oleh satu Post)
     */
    public function post(): BelongsTo
    {
        // Kedua kolom adalah INT: id_post di post_items merujuk id_post di posts
        return $this->belongsTo(Post::class, 'id_post', 'id_post');
    }

    /**
     * Relasi ke Produk (PostItem merujuk ke satu Produk)
     */
    public function produk(): BelongsTo
    {
        // Kedua kolom adalah INT: id_produk di post_items merujuk id_produk di produk_looksee
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}