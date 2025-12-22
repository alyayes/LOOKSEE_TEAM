<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_post'; 
    
    protected $fillable = [
        'user_id', 'caption', 'hashtags', 'mood', 'image_post',
        'like_count', 'comment_count', 'share_count'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_post');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            Produk::class, 
            'post_items', 
            'id_post', 
            'id_produk' 
        );
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'id_post');
    }
}