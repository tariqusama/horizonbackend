<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Application extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'manager_id',
        'title',
        'subtitle',
        'status',
        'progress',
        'next_step',
        'receipt_number',
        'timeline'
    ];

    protected $casts = [
        'timeline' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
