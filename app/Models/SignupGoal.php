<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignupGoal extends Model
{
    protected $fillable = ['title', 'image_url', 'order_index', 'default_service_id'];

    public function questions()
    {
        return $this->hasMany(SignupQuestion::class)->orderBy('order_index');
    }

    public function defaultService()
    {
        return $this->belongsTo(Service::class, 'default_service_id');
    }
}
