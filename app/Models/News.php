<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'posted_on',
    ];

    public function getPostedOnAttribute()
    {
        return date('F d. Y', strtotime($this->created_at));
    }

}
