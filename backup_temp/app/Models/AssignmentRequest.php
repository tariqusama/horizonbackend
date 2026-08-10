<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class AssignmentRequest extends Model
{
    use Auditable;

    protected $fillable = ['application_id', 'manager_id', 'status', 'notes'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
