<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts'; // sesuaikan kalau nama tabelmu berbeda

    protected $primaryKey = 'id_post';

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'image_post',
        'caption',
        'hashtags',
        'mood',
        'like_count',
        'comment_count',
        'share_count',
        'created_at'
    ];

    // Relasi ke User (many-to-one)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
