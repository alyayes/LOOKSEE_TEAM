<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $primaryKey = 'id_like';

    protected $keyType = 'int';

    public $incrementing = true;
    
    public $timestamps = false;

    protected $fillable = [
        'id_post',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'id_post', 'id_post');
    }

    public function totalLikes()
    {
        return $this->likes()->count();
    }
}