<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'description',
        'forms',
        'total_documents',
        'sections'
    ];

    protected $casts = [
        'forms' => 'array',
        'sections' => 'array'
    ];
}
