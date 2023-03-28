<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public static function CheckFavourite($user_id, $car_id)
    {
        $exists = Favourite::where('car_id', $car_id)->where('user_id', $user_id)->first();
        if(empty($exists))
        {
            return false;
        }
        return true;
    }

}
