<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowroomVerification extends Model
{
    protected $table = "showroom_verification";

    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
