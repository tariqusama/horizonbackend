<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignupQuestion extends Model
{
    protected $fillable = [
        'signup_goal_id', 
        'question_text', 
        'options', 
        'disqualifying_options', 
        'skip_to_end_options',
        'order_index'
    ];

    protected $casts = [
        'options' => 'array',
        'disqualifying_options' => 'array',
        'skip_to_end_options' => 'array',
    ];

    public function goal()
    {
        return $this->belongsTo(SignupGoal::class, 'signup_goal_id');
    }
}
