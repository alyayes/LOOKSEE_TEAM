<?php

// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    // Pastikan nama tabel dan primary key sudah benar
    protected $table = 'comments'; 
    protected $primaryKey = 'id_comment'; 
    public $incrementing = true;

    protected $fillable = [
        'id_post',
        'user_id',
        'comment_text',
        // 'created_at' tidak perlu jika menggunakan timestamps
    ];

    // Nonaktifkan timestamps bawaan jika hanya menggunakan 'created_at' yang di-set manual
    // public $timestamps = false; 

    /**
     * Relasi Many-to-One: Comment dimiliki oleh satu Post.
     */
    public function post(): BelongsTo
    {
        // Berdasarkan skema, foreign key di tabel 'comments' adalah 'id_post'
        return $this->belongsTo(Post::class, 'id_post', 'id_post');
    }

    /**
     * Relasi Many-to-One: Comment dibuat oleh satu User.
     */
    public function user(): BelongsTo
    {
        // Berdasarkan skema, foreign key di tabel 'comments' adalah 'user_id'
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}