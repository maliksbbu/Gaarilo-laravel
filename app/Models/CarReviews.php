<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarReviews extends Model
{
    protected $table = 'car-reviews';
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'posted_on'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function models()
    {
        return $this->belongsTo(Models::class, 'model', 'id');
    }
    public function makes()
    {
        return $this->belongsTo(Brand::class, 'make', 'id');
    }
    public function getPostedOnAttribute()
    {
        return date('F d. Y', strtotime($this->created_at));
    }
}
