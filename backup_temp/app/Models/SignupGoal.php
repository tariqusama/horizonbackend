<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignupGoal extends Model
{
    protected $fillable = ['title', 'image_url', 'order_index'];

    public function questions()
    {
        return $this->hasMany(SignupQuestion::class)->orderBy('order_index');
    }
}
