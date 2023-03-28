<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function model()
    {
        return $this->hasMany(Models::class, 'brand_id', 'id');
    }

    public function RegisteredModelsCount()
    {
        return $this->model()->count();
    }
}
