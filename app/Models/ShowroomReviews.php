<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowroomReviews extends Model
{
    use HasFactory;

    protected $appends = [
        'posted_on',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviewsratings()
    {
        return $this->hasMany(ShowroomReviewRatings::class, 'showroom_review_id', 'id');
    }

    public function getPostedOnAttribute()
    {
        return date('F d. Y', strtotime($this->created_at));
    }
}
