<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $table = 'models';
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'type_id', 'id');
    }

    public function version()
    {
        return $this->hasMany(Version::class, 'model_id', 'id');
    }

    public function RegisteredVersionsCount()
    {
        return $this->version()->count();
    }
}
