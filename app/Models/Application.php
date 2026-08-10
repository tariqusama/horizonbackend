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
        'service_id',
        'title',
        'package_name',
        'amount',
        'paid_amount',
        'subtitle',
        'status',
        'progress',
        'next_step',
        'receipt_number',
        'timeline',
        'is_escalated',
        'internal_notes',
        'form_data',
        'form_slug',
        'questionnaire_answers'
    ];


    protected $casts = [
        'timeline' => 'array',
        'internal_notes' => 'array',
        'form_data' => 'array',
        'questionnaire_answers' => 'array',
        'is_escalated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function invites()
    {
        return $this->hasMany(ApplicationInvite::class);
    }

    public function participants()
    {
        return $this->hasMany(ApplicationParticipant::class);
    }
}
