<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'likes';

    // Kolom Primary Key sesuai dengan migration
    protected $primaryKey = 'id_like';

    // Model ini menggunakan tipe integer untuk Primary Key
    protected $keyType = 'int';

    // Primary Key diatur auto-increment
    public $incrementing = true;
    
    // Nonaktifkan kolom 'updated_at' karena hanya 'created_at' yang ada di migration
    public $timestamps = false; // Karena hanya ada 'created_at'

    // Tentukan kolom mana yang dapat diisi massal (Mass Assignable)
    protected $fillable = [
        'id_post',
        'user_id',
    ];

    // --- Definisi Relasi (Relationships) ---

    /**
     * Relasi ke Post: Sebuah Like dimiliki oleh satu Post (Belongs To)
     */
    public function post()
    {
        // Hubungan ke Model Post, menggunakan foreign key 'id_post'
        return $this->belongsTo(Post::class, 'id_post');
    }

    /**
     * Relasi ke User: Sebuah Like dimiliki oleh satu User (Belongs To)
     */
    public function user()
    {
        // Hubungan ke Model User, menggunakan foreign key 'user_id'
        return $this->belongsTo(User::class, 'user_id');
    }

    // app/Models/Post.php
    public function likes()
    {
        // Sebuah Post memiliki banyak Likes (Has Many)
        return $this->hasMany(Like::class, 'id_post', 'id_post');
    }

    // Relasi untuk menghitung total likes (opsional)
    public function totalLikes()
    {
        return $this->likes()->count();
    }
}