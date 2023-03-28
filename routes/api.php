<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("test", "MobileAppInterfaces\ServiceController@Test");

Route::post("send-otp", "MobileAppInterfaces\AuthorizationController@SendOneTimePassword");
Route::post("login", "MobileAppInterfaces\AuthorizationController@Login");
Route::post("register", "MobileAppInterfaces\AuthorizationController@Register");

Route::get('splash', "MobileAppInterfaces\ServiceController@Splash");
Route::post('home-screen', "MobileAppInterfaces\ServiceController@HomeScreen");
Route::post('car-info', 'MobileAppInterfaces\ServiceController@CarInfo');
Route::post('search-cars', 'MobileAppInterfaces\ServiceController@FilterCars');
Route::post('search-showrooms', 'MobileAppInterfaces\ServiceController@FilterShowrooms');
Route::post('car-detail', 'MobileAppInterfaces\ServiceController@CarDetails');
Route::post('showroom-detail', 'MobileAppInterfaces\ServiceController@ShowroomDetails');
Route::post('showroom-reviews', 'MobileAppInterfaces\ServiceController@ShowroomReviews');
Route::post('car-reviews', 'MobileAppInterfaces\ServiceController@CarReviews');
Route::post('video-reviews', 'MobileAppInterfaces\ServiceController@VideoReviews');
Route::get('news-tips', 'MobileAppInterfaces\ServiceController@NewsTips');

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('profile', "MobileAppInterfaces\UserController@GetProfile");
    Route::post('profile', "MobileAppInterfaces\UserController@EditProfile");

    Route::post('post-ad', "MobileAppInterfaces\ServiceController@PostAd");
    Route::post('edit-ad', "MobileAppInterfaces\ServiceController@EditAd");
    Route::post('showroom-write-review', 'MobileAppInterfaces\ServiceController@ShowroomWriteReview');
    Route::get('my-showroom-details', 'MobileAppInterfaces\UserController@MyShowroomDetails');
    Route::post('edit-service-year', 'MobileAppInterfaces\UserController@EditServiceYear');

    Route::post('ad-mark-sold', 'MobileAppInterfaces\UserController@AdMarkSold');
    Route::post('ad-delete', 'MobileAppInterfaces\UserController@AdDelete');
    Route::post('ad-update-price', 'MobileAppInterfaces\UserController@AdUpdatePrice');

    Route::post('ad-make-offer', 'MobileAppInterfaces\UserController@MakeOffer');
    Route::get('my-offers', 'MobileAppInterfaces\UserController@MyOffers');
    Route::post('offer-details', 'MobileAppInterfaces\UserController@OfferDetails');
    Route::post('offer-response', "MobileAppInterfaces\UserController@OfferResponse");

    Route::post('write-car-review', 'MobileAppInterfaces\UserController@WriteCarReview');

    Route::get('my-saved-ads', 'MobileAppInterfaces\UserController@MySavedAds');
    Route::post('favourite-unfavourite-ads', 'MobileAppInterfaces\UserController@FavouriteUnfavouriteAds');


});

