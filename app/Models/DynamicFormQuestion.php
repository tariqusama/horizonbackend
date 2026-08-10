<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicFormQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'dynamic_form_section_id',
        'question_text',
        'help_text',
        'field_type',
        'field_name',
        'is_required',
        'order',
        'validation_rules',
    ];

    protected $casts = [
        'validation_rules' => 'array',
        'is_required' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(DynamicFormSection::class, 'dynamic_form_section_id');
    }

    public function options()
    {
        return $this->hasMany(DynamicFormOption::class)->orderBy('order');
    }
}
