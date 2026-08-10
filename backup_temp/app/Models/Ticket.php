<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Ticket extends Model
{
    use Auditable;

    protected $fillable = [
        'ticket_id',
        'application_id',
        'subject',
        'message',
        'status',
        'priority',
        'user_id',
        'assigned_to',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
