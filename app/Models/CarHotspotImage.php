<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarHotspotImage extends Model
{
    protected $guarded = array();


    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
