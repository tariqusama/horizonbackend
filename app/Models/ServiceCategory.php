<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = ['title', 'subtitle', 'pill_text', 'order_index'];

    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('order_index');
    }
}
