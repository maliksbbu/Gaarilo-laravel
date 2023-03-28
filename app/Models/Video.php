<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = array();

    protected $appends = [
        'posted_on'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getPostedOnAttribute()
    {
        return date('F d. Y', strtotime($this->created_at));
    }
}
