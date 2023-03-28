<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarOffer extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'offered_on',
    ];

    public function car ()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function GetUserOfferOnCar ($car_id, $user_id)
    {
        return CarOffer::where('user_id', $user_id)->where('car_id', $car_id)->first();
    }

    public static function OfferOnCar ($user_id, $car_id)
    {
        $offer = CarOffer::GetUserOfferOnCar($car_id, $user_id);
        if(empty($offer))
        {
            return (object)[];
        }
        else
        {
            if($offer->status == "PENDING")
            {
                if($offer->counter_amount == null)
                {
                    return array(
                        'message' => 'Your Offer',
                        'price' => $offer->amount,
                        'color' => 'blue',
                    );
                }
                else
                {
                    return array(
                        'message' => 'Showroom Offer',
                        'price' => $offer->counter_amount,
                        'color' => 'red',
                    );
                }
            }
            elseif($offer->status == "ACCEPTED")
            {
                return array(
                    'message' => 'Offer Accepted',
                    'price' => $offer->amount,
                    'color' => 'green',
                );
            }
        }
    }

    public function getOfferedOnAttribute ()
    {
        return date('F d. y, h:i a', strtotime($this->created_at));
    }
}
