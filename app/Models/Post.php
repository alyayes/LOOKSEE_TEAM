<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts'; 

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class, 'id_post', 'id_post');
    }

    public function items() 
    {
        // Mengacu ke Model PostItem yang baru dibuat
        return $this->hasMany(PostItem::class, 'id_post', 'id_post'); 
    }

    public function likes(): HasMany
    {
        // Asumsi: Tabel 'likes' Anda memiliki foreign key 'id_post'
        return $this->hasMany(Like::class, 'id_post'); 
    }
}