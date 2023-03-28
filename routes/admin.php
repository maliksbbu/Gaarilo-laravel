<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Admin" middleware group. Enjoy building your Admin!
|
*/

#region WEBAPI


#endregion WEBAPI


Route::group(['middleware' => 'adminauth'], function () {
    // Admin routes after passing middleware
    Route::get('/', 'WebAdmin\AdminController@dashboard')->name('index');
    Route::get('dashboard', 'WebAdmin\AdminController@dashboard')->name('dashboard');


    Route::get('verified-showroom/{id}', 'WebAdmin\AdminController@VerfiedShowroom')->name('verified-showroom');
    Route::get('delete-showroom/{id}', 'WebAdmin\AdminController@ShowroomDelete')->name('delete-showroom');

    Route::get('settings', 'WebAdmin\AdminController@viewSettings')->name('viewSettings');
    Route::post('settings', 'WebAdmin\AdminController@updateSettings')->name('updateSettings');

    Route::get('target-tracker', 'WebAdmin\AdminController@TargetTracker')->name('target-tracker');

    Route::get('pending-ads', 'WebAdmin\AdminController@PendingAds')->name('pending-ads');
    Route::get('admin-showrooms', 'WebAdmin\AdminController@AdminShowrooms')->name('admin-showrooms');
    Route::get('admin-showrooms-detail/{id}', 'WebAdmin\AdminController@AdminShowroomDetail')->name('admin-showroom-detail');
    Route::get('admin-sold-cars', 'WebAdmin\AdminController@AdminSoldCars')->name('admin-sold-cars');
    Route::get('admin-web-users', 'WebAdmin\AdminController@AdminUsers')->name('admin-web-users');
    Route::get('admin-car-offers', 'WebAdmin\AdminController@AdminCarOffers')->name('admin-car-offers');
    Route::post('admin-showroom-verified', 'WebAdmin\AdminController@updateShowRoomMarkVerified')->name('admin-showroom-verified');
    Route::post('admin-update-pending-ads', 'WebAdmin\AdminController@updatePendingAdsStatus')->name('admin-update-pending-ads');

    Route::get('delete-user/{id}', 'WebAdmin\AdminController@DeleteUser')->name('delete-user');

    #region Resources

    Route::resource('admin-users', 'WebAdmin\Resources\AdminUserResource');
    Route::resource('roles', 'WebAdmin\Resources\RoleResource');
    Route::resource('vehicle-type', 'WebAdmin\Resources\VehicleTypeResource');
    Route::resource('vehicle-make', 'WebAdmin\Resources\CarBrandResource');
    Route::resource('vehicle-model', 'WebAdmin\Resources\CarModelResource');
    Route::resource('vehicle-version', 'WebAdmin\Resources\CarVersionResource');
    Route::resource('color', 'WebAdmin\Resources\ColorResource');
    Route::resource('feature', 'WebAdmin\Resources\FeatureResource');
    Route::resource('province', 'WebAdmin\Resources\ProvinceResource');
    Route::resource('city', 'WebAdmin\Resources\CityResource');
    Route::resource('video', 'WebAdmin\Resources\VideoResource');
    Route::resource('news', 'WebAdmin\Resources\NewsResource');
    #endregion Resources

});
