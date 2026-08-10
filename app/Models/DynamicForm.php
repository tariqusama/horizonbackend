<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'dynamic_form_service')
                    ->withPivot('is_required', 'condition_code');
    }

    public function sections()
    {
        return $this->hasMany(DynamicFormSection::class)->orderBy('order');
    }
}
