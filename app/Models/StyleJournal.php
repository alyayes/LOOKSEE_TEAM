<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StyleJournal extends Model
{
    use HasFactory;

    protected $table = 'stylejournal';

    protected $primaryKey = 'id_journal';

    protected $fillable = [
        'title', 
        'descr', 
        'content', 
        'publication_date', 
        'image'
    ];

    protected $casts = [
        'publication_date' => 'datetime',
    ];

    public $timestamps = false; 
}