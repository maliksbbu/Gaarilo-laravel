<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showroom extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'count_review',
        'count_sold',
        'city_name',
        'count_all_cars'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function showroomreviews()
    {
        return $this->hasMany(ShowroomReviews::class, 'showroom_id', 'id');
    }

    public function GetNumberSoldCars()
    {
        $numSold = Car::where('showroom_id', $this->id)->where('status','SOLD')->count();
        return $numSold;
    }

    public function GetNumberAllCars()
    {
        $numSold = Car::where('showroom_id', $this->id)->count();
        return $numSold;
    }

    public function getCountAllCarsAttribute()
    {
        return $this->GetNumberAllCars();
    }

    public function getCountReviewAttribute()
    {
        $numSold = ShowroomReviews::where('showroom_id', $this->id)->count();
        return $numSold;
    }

    public function getCountSoldAttribute()
    {
        return $this->GetNumberSoldCars();
    }

    public function getCityNameAttribute()
    {
        if($this->city()->first() != null)
        {
            return $this->city()->first()->name;
        }
        return "";
    }

}
