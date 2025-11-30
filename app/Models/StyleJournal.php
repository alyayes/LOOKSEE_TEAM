<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StyleJournal extends Model
{
    use HasFactory;

    // Nama tabel database
    protected $table = 'stylejournal';

    // Primary key
    protected $primaryKey = 'id_journal';

    // Kolom-kolom yang dapat diisi (sesuai tabel Anda)
    protected $fillable = [
        'title', 
        'descr', 
        'content', 
        'publication_date', 
        'image'
    ];

    // Kolom-kolom yang harus dikonversi ke tipe data tertentu (casts)
    protected $casts = [
        'publication_date' => 'datetime',
    ];

    // Menonaktifkan timestamps bawaan Laravel (created_at, updated_at)
    public $timestamps = false; 
}