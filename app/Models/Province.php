<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = [];

    public function city()
    {
        return $this->hasMany(City::class, 'province_id', 'id');
    }

    public function RegisteredVCitiesCount()
    {
        return $this->city()->count();
    }
}
