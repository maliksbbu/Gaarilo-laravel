<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $guarded = array();

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'rating',
        'count_review',
        'list_feature',
        'car_name',
        'last_updated',
        'city_name',
        'images_count',
        'body_type',
        'pending_offer_count',
    ];

    public static $engineTypes = [
        "Petrol",
        "Diesel",
        "Hybrid",
        "Cng",
        "Lpg",
        "Electric",
    ];

    public static $driveTypes = [
        'AWD',
        '4WD',
        'FWD',
        'RWD',
    ];


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function offers()
    {
        return $this->belongsTo(CarOffer::class, 'id', 'car_id');
    }

    public function model()
    {
        return $this->belongsTo(Models::class, 'model_id', 'id');
    }

    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function showroom()
    {
        return $this->belongsTo(Showroom::class, 'showroom_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'exterior_color', 'id');
    }

    public function rating()
    {
        return $this->hasMany(CarRating::class, 'car_id', 'id');
    }

    public function exterior_images()
    {
        return $this->hasMany(CarExteriorImage::class, 'car_id', 'id');
    }

    public function interior_images()
    {
        return $this->hasMany(CarInteriorImage::class, 'car_id', 'id');
    }

    public function hotspot_images()
    {
        return $this->hasMany(CarHotspotImage::class, 'car_id', 'id');
    }

    public function car_offers()
    {
        return $this->hasMany(CarOffer::class, 'car_id', 'id');
    }

    public function getRatingAttribute()
    {
        return $this->rating()->avg('rating') == null ? strval(5) : strval(round($this->rating()->avg('rating'), 1));
    }

    public function getCountReviewAttribute()
    {
        return $this->rating()->count();
    }

    public function getListFeatureAttribute()
    {
        $list = Feature::whereIn('id', explode(',', $this->feature))->get();
        return $list;
    }

    public function getCarNameAttribute()
    {
        if ($this->brand()->first() != null && $this->model()->first() != null)
        {
            if($this->version()->first() != null)
            {
                return $this->brand()->first()->name . " " . $this->model()->first()->name . " " . $this->version()->first()->name;
            }
            return $this->brand()->first()->name . " " . $this->model()->first()->name;
        }

        return "";
    }

    public function getLastUpdatedAttribute()
    {
        if ($this->updated_at != null)
            return date('m/d/Y', strtotime($this->updated_at));
        return "";
    }

    public function CountCarOffer()
    {
        return $this->car_offers()->count();
    }
    public function CountPendingOffer()
    {
        return $this->car_offers()->where('status', 'PENDING')->where('counter_amount', NULL)->count();
    }

    public static function CountAd($type = 'USED')
    {
        $carCount = Car::where('condition', $type)->count();
        return $carCount;
    }

    public function getCityNameAttribute()
    {
        if($this->city()->first() != null)
            return $this->city()->first()->name;
        return "";
    }

    public function getImagesCountAttribute()
    {
        return $this->exterior_images()->count() + $this->interior_images()->count() + $this->hotspot_images()->count();
    }

    public function getBodyTypeAttribute()
    {
        if($this->model()->first() != null)
        {
            return $this->model()->first()->type->name;
        }
        return "";
    }

    public function getPendingOfferCountAttribute()
    {
        return $this->CountPendingOffer();
    }
}


