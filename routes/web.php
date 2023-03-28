<?php

use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', "FCMController@test");


Route::get('/', 'Web\FrontController@Landing')->name('landing');
//Route::get('/test', 'Web\FrontController@testing')->name('testing');
Route::get('login', 'Web\FrontController@ViewLogin')->name('login');
Route::get('signout', 'Web\FrontController@SignOut')->name('signout');
Route::get('signup', 'Web\FrontController@SignUp')->name('signup');
Route::post('signup', 'Web\FrontController@SignUpPost')->name('signup.post');
Route::get('post-ad', 'Web\FrontController@ViewPostAd')->name('post-ad');
Route::post('post-ad', 'Web\FrontController@SubmitPostAd')->name('post-ad');
Route::get('search-grid', 'Web\FrontController@SearchGrid')->name('search-grid');
Route::get('search-list', 'Web\FrontController@SearchList')->name('search-list');
Route::get('search-cars', 'Web\FrontController@SearchCars')->name('search-cars');

Route::get('news', 'Web\FrontController@NewsTips')->name('news_tips');
Route::get('video-reviews', 'Web\FrontController@VideosReview')->name('video_review');
Route::get('saved-ads', 'Web\FrontController@SavedAds')->name('saved_ads');


Route::get('/user-profile', 'Web\FrontController@UserProfile')->name('user-profile');
Route::post('/user-profile-store', 'Web\FrontController@UserProfileStore')->name('user-profile-store');


Route::get('edit-ad/{id}', 'Web\FrontController@ViewEditAd')->name('edit-ad');
Route::post('edit-ad/{id}', 'Web\FrontController@SubmitEditAd')->name('edit-ad');

Route::get('car-detail/{id}', 'Web\FrontController@CarDetail')->name('car-detail');

Route::post('ad-mark-sold', 'Web\FrontController@AdMarkSold')->name('car.sold');
Route::get('ad-delete/{id}', 'Web\FrontController@DeleteAd')->name('car.delete');

Route::get('support-contact', 'Web\FrontController@supportContact')->name('support-contact');
Route::post('save-support-contact', 'Web\FrontController@saveSupportContact')->name('save-support-contact');

Route::get('showroom-detail/{id}', 'Web\FrontController@ShowroomDetail')->name('showroom-detail');
Route::get('search-showrooms', 'Web\FrontController@SearchShowrooms')->name('search-showrooms');

//Route::get('search-showrooms/{new?}', 'Web\FrontController@SearchShowrooms')->name('search-showrooms');
Route::get('search-showrooms-have-old-car/{verfied?}', 'Web\FrontController@SearchShowroomsHaveOldCar')->name('search-showrooms-have-old-car');

//Route::resource('showroom-review', 'Web\ShowroomReviews', ['only' => ['index', 'create', 'edit', 'update']]);
Route::get('showroom-reviews-detail/{id}', 'Web\FrontController@ShowroomReviewsDetail')->name('showroom-reviews-detail');
Route::get('showroom-review/{id}', 'Web\ShowroomReviewsController@create')->name('showroom-review');
Route::post('showroom-review-store', 'Web\ShowroomReviewsController@store')->name('showroom-review-store');

Route::get('my-showroom/{id}', 'Web\ShowroomReviewsController@MyShowroom')->name('my-showroom');

Route::get('my-showroom-details/{id}', 'Web\ShowroomReviewsController@MyShowroomDetails')->name('my-showroom-details');
Route::post('my-showroom-edit_year', 'Web\ShowroomReviewsController@MyshowroomEditYear')->name('my-showroom-edit_year');

Route::post('car-offer', 'Web\FrontController@CarOfferPost')->name('car.offer');

Route::post('car-update-price', 'Web\FrontController@CarUpdatePrice')->name('car.update.price');

Route::get('car-review', 'Web\FrontController@CarReview')->name('car-review');
Route::get('car-add-review', 'Web\FrontController@CarAddReview')->name('car-add-review');
Route::post('car-add-review-store', 'Web\FrontController@CarAddReviewStore')->name('car-add-review-store');

Route::get('offer', 'Web\FrontController@ViewMyOffer')->name('offer');
Route::get('offer-details/{id}', 'Web\FrontController@OfferDetails')->name('offer.details');

Route::get('showroom-offers/{id}', 'Web\FrontController@ShowroomOffers')->name('showroom.offers');

Route::post('counter-offer/{id}', 'Web\FrontController@SubmitCounterOffer')->name('counter.offer');

Route::get('change-offer-status/{id}/{status}', 'Web\FrontController@ChangeOfferStatus')->name('change.offer.status');

Route::get('favourite-ad/{id}', 'Web\FrontController@FavouriteAd')->name('ad-favourite');
Route::get('unfavourite-ad/{id}', 'Web\FrontController@UnfavouriteAd')->name('ad-unfavourite');

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'WebAdmin\AuthorizationController@getLogin')->name('adminLogin');
    Route::post('login', 'WebAdmin\AuthorizationController@postLogin')->name('adminLoginPost');
    Route::get('logout', 'WebAdmin\AuthorizationController@logout')->name('adminLogout');
});

Route::get('terms', function () {
    $content = (new CommonController)->GetSetting('terms_and_conditions');
    $title = 'Terms & Conditions';
    return view('front.static', compact('content', 'title'));
})->name('terms');

Route::get('privacy', function () {
    $content = (new CommonController)->GetSetting('privacy');
    $title = 'Privacy Policy';
    return view('front.static', compact('content', 'title'));
})->name('privacy');

Route::get('about', function () {
    $content = (new CommonController)->GetSetting('about_us');
    $title = 'About Us';
    return view('front.static', compact('content', 'title'));
})->name('about');

Route::get('contact', function () {
    $content = (new CommonController)->GetSetting('contact_us');
    $title = 'Contact Us';
    return view('front.static', compact('content', 'title'));
})->name('contact');

Route::group(['prefix' => 'webapi', 'as' => 'webapi.'], function () {
    Route::post('send-otp', 'Web\FrontController@SendOTP')->name('sendotp');
    Route::post('verify-otp', 'Web\FrontController@VerifyOtp')->name('verifyotp');
    Route::post('models', 'Web\FrontController@GetModels')->name('models');
    Route::post('versions', 'Web\FrontController@GetVersions')->name('versions');
    Route::post('versions_all', 'Web\FrontController@GetAllVersions')->name('versions.all');

    Route::get('increment-phone-count', 'Web\FrontController@IncrementCarDetailPhoneCount')->name('increment.phonecount');
    Route::post('search-cars-by-view', 'Web\FrontController@SearchCarsByViewType')->name('search-cars-by-view');
    Route::post('search-showrooms-by-view', 'Web\FrontController@SearchShowroomsByViewType')->name('search-showrooms-by-view');
    Route::post('offer-switch', 'Web\FrontController@ViewOfferSwitch')->name('offer.switch');
    Route::post('dynamic-reviews', 'Web\FrontController@SearchCarReviewDynamicView')->name('dynamic.reviews');
    Route::post('search-video', 'Web\FrontController@SearchVideos')->name('search.videos');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix' => 'filterapi', 'as' => 'filterapi.'], function () {
    Route::post('get_cities', 'Web\FilterController@GetCities')->name('get_cities');
    Route::post('get_models', 'Web\FilterController@GetModels')->name('get_models');
    Route::post('get_versions', 'Web\FilterController@GetVersions')->name('get_versions');
    Route::post('search-cars-by-view', 'Web\FilterController@SearchCarsByViewType')->name('search-cars-by-view');
    Route::post('search-showrooms-by-view', 'Web\FilterController@SearchShowroomsByViewType')->name('search-showrooms-by-view');
    Route::post('showroom-detail-cars-by-view', 'Web\FilterController@ShowroomCarsByViewType')->name('showroom-detail-cars-by-view');
    Route::post('recommended-showrooms', 'Web\FrontController@GetRecommendedShowrooms')->name('recommended-showrooms');
});
