<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username', 'name', 'email', 'password', 'role'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    // app/Models/User.php
    public function likedPosts()
    {
        // Seorang User memiliki banyak Likes (Has Many)
        return $this->hasMany(Like::class, 'user_id', 'user_id');
    }
}