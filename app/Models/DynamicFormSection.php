<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicFormSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'dynamic_form_id',
        'title',
        'description',
        'order',
    ];

    public function form()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function questions()
    {
        return $this->hasMany(DynamicFormQuestion::class)->orderBy('order');
    }
}
