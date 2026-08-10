<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicFormOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'dynamic_form_question_id',
        'option_label',
        'option_value',
        'order',
    ];

    public function question()
    {
        return $this->belongsTo(DynamicFormQuestion::class, 'dynamic_form_question_id');
    }
}
