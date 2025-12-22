<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments'; 
    protected $primaryKey = 'id_comment'; 
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_post',
        'user_id',
        'comment_text',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'id_post', 'id_post');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}