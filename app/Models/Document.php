<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'context', // resume, property, hotel, education, others
        'extracted_data', // JSON key-value pairs
        'suggestions', // optional extra fields
        'status', // draft, completed, failed
    ];

    protected $casts = [
        'extracted_data' => 'array',
        'suggestions' => 'array',
    ];
}

