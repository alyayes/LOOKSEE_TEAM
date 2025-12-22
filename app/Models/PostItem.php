<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostItem extends Model
{
    use HasFactory;

    protected $table = 'post_items'; 
    
    protected $primaryKey = 'id_post_items';
    
    protected $keyType = 'int'; 
    public $incrementing = true;
    
    protected $fillable = [
        'id_post', 
        'id_produk', 
    ];

    public $timestamps = false; 

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'id_post', 'id_post');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}