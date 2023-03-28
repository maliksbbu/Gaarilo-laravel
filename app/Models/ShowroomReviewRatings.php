<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowroomReviewRatings extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function showroom()
    {
        return $this->belongsTo(Showroom::class, 'showroom_id', 'id');
    }

    public function reviews()
    {
        return $this->belongsTo(ShowroomReviews::class, 'showroom_review_id', 'id');
    }
}
