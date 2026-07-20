<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Service extends Model
{
    use Auditable;

    protected $fillable = ['name', 'description', 'price', 'tier'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
