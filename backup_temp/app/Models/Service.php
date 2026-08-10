<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Service extends Model
{
    use Auditable;

    protected $fillable = [
        'service_category_id', 
        'title', 
        'subtitle', 
        'starting_price', 
        'processing_time', 
        'requirements', 
        'is_popular', 
        'order_index'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_popular' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function packages()
    {
        return $this->hasMany(ServicePackage::class)->orderBy('order_index');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
