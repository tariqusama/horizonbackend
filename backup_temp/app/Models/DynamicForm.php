<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'slug',
        'description',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function sections()
    {
        return $this->hasMany(DynamicFormSection::class)->orderBy('order');
    }
}
