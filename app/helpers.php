<?php

use Illuminate\Support\Facades\DB;

function remove_country_code($phone_number)
{
    return preg_replace('/^\+?92|\|1|\D/', ' ', ($phone_number));
}
function car_in_city($city_id)
{
    $count_car_in_city = DB::table('cars')->where('status', 'APPROVED')->where('city_id', $city_id)->count();
    return   $count_car_in_city;
}
function showroom_in_city($city_id)
{
    $count_showroom_in_city = DB::table('showrooms')->where('city_id', $city_id)->count();
    return   $count_showroom_in_city;
}
function showroom_in_province($province_id)
{

    $p_array = [];
    $cities = DB::table('cities')->where('province_id', $province_id)->get();
    foreach ($cities as $city) {
        $count_car_in_provience = DB::table('showrooms')->where('city_id', $city->id)->count();
        array_push($p_array, $count_car_in_provience);
    }
    return  array_sum($p_array);
}
function city_name($city_id)
{
    $city_name = DB::table('cities')->where('id', $city_id)->first();
    return   $city_name->name;
}
function brand_logo($brand_id)
{
    $brand = DB::table('brands')->where('id', $brand_id)->first();
    return   $brand->logo;
}
function car_name($car_id)
{
    $car = DB::table('cars')->where('id', $car_id)->first();
    $car_brand =  DB::table('brands')->where('id', $car->brand_id)->first();
    $car_model =  DB::table('models')->where('id', $car->model_id)->first();
    $car_version =  DB::table('versions')->where('id', $car->version_id)->first();
    if (!empty($car_version)) {
        $v_name = $car_version->name;
    } else {
        $v_name = '';
    }
    return   $car_brand->name . ' ' . $car_model->name . ' ' . $v_name;
}
function car_in_provience($provience_id)
{
    $p_array = [];
    $cities = DB::table('cities')->where('province_id', $provience_id)->get();
    foreach ($cities as $city) {
        $count_car_in_provience = DB::table('cars')->where('status', 'APPROVED')->where('city_id', $city->id)->count();
        array_push($p_array, $count_car_in_provience);
    }
    return  array_sum($p_array);
}
function car_registerd_provience($provience_id)
{
    $p_array = [];
    $cities = DB::table('cities')->where('province_id', $provience_id)->get();
    foreach ($cities as $city) {
        $count_car_in_provience = DB::table('cars')->where('status', 'APPROVED')->where('city_id', $city->id)->count();
        array_push($p_array, $count_car_in_provience);
    }
    return  array_sum($p_array);
}
function car_type_count($type_id)
{
    $p_array = [];
    $models = DB::table('models')->where('type_id', $type_id)->get();
    foreach ($models as $model) {
        $count_car_types = DB::table('cars')->where('status', 'APPROVED')->where('model_id', $model->id)->count();
        array_push($p_array, $count_car_types);
    }
    return  array_sum($p_array);
}
function car_make_count($brand_id)
{
    $count_car_in_make = DB::table('cars')->where('status', 'APPROVED')->where('brand_id', $brand_id)->count();
    return   $count_car_in_make;
}
function car_model_count($model_id)
{
    $count_car_in_model = DB::table('cars')->where('status', 'APPROVED')->where('model_id', $model_id)->count();
    return   $count_car_in_model;
}
function car_version_count($version_id)
{
    $count_car_in_version = DB::table('cars')->where('status', 'APPROVED')->where('version_id', $version_id)->count();
    return   $count_car_in_version;
}
function car_engine_type_count($type)
{
    $count_car_engine_type = DB::table('cars')->where('status', 'APPROVED')->where('engine_type', $type)->count();
    return   $count_car_engine_type;
}

function return_rating_html($stars_count)
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($stars_count >= $i) {
            $html .= '<span class="fa fa-star checked"></span>';
        } else {
            $html .= '<span class="fa fa-star"></span>';
        }
    }
    return $html;
}

function showroom_rating_name($type)
{
    $name_rating = [
        'DEALING' => 'Showroom Dealing',
        'SELECTION' => 'Vehicle Selection',
        'SERVICE' => 'Quality Of service'
    ];
    return $name_rating[$type];
}
function user_id_in_showroom($showroomid, $userid)
{
    $showroom_reviews = DB::table('showroom_reviews')->where('showroom_id', $showroomid)->where('user_id', $userid)->first();
    if ($showroom_reviews) {
        return true;
    } else {
        return false;
    }
}
//  function showroom_number($car_id){
//    $car =   DB::table('cars')->where('id',$car_id)->first();
//    $showroom = DB::table('showrooms')->where('id', $car->showroom_id)->first();
//    $user  = DB::table('users')->where('id', $showroom->user_id)->first();
//    return  $user->business_phone_number;

//  }
function user_offer($car_id, $user_id)
{

    $offer = DB::table('car_offers')->where('car_id', $car_id)->where('user_id', $user_id)->first();
    if ($offer) {
        return true;
    } else {
        return false;
    }
}
function user_showroom_offer($car_id, $user_id)
{
    $offer_status = "";
    $offer = DB::table('car_offers')->where('car_id', $car_id)->where('user_id', $user_id)->first();
    if (!empty($offer)) {
        if ($offer->amount != null && $offer->counter_amount == null && $offer->status == 'PENDING') {
            $offer_status = 'PENDING';
        } elseif ($offer->amount != null   && $offer->status == 'ACCEPTED') {
            $offer_status = 'ACCEPTED';
        } elseif ($offer->amount != null && $offer->counter_amount != null  && $offer->status == 'ACCEPTED') {
            $offer_status = 'ACCEPTED';
        } elseif ($offer->amount != null &&  $offer->counter_amount != null   && $offer->status == 'PENDING') {
            $offer_status = 'SHOWROOM-PENDING';
        } elseif ($offer->amount != null &&  $offer->counter_amount == null   && $offer->status == 'REJECTED') {
            $offer_status = 'REJECTED';
        } elseif ($offer->amount != null &&  $offer->counter_amount != null   && $offer->status == 'REJECTED') {
            $offer_status = 'REJECTED';
        } else {
            $offer_status = 'REJECTED';
        }
    }

    return $offer_status;
}
function car_have_offer($car_id)
{

    $car_offer = DB::table('car_offers')->where('car_id', $car_id)->first();
    if ($car_offer) {
        return true;
    } else {
        return false;
    }
}

function VerfiedCars()
{
    $verfied_showroom = DB::table('showrooms')->where('verified', 'YES')->get();
    $ids = array();
    foreach ($verfied_showroom as $showroom) {
        array_push($ids, $showroom->id);
    }
    $cars = DB::table('cars')->whereIn('showroom_id', $ids)->count();
    return $cars;
}
function registertion_city($id)
{
    $city = DB::Table('cities')->where('id', $id)->first();
    if(!empty($city)){
        $city_name = $city->name;
        return  $city_name;
    }
    return $city_name = 'Un-registerd';
}
