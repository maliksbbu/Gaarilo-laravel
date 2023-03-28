<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarInteriorImage extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
