<?php

namespace App\Http\Controllers\MobileAppInterfaces;

use Exception;
use App\Models\Car;
use App\Models\User;
use App\Models\CarOffer;
use App\Models\Showroom;
use App\Models\Favourite;
use App\Models\CarReviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CommonController;

class UserController extends Controller
{
    private $common;
    private $emptyObject;
    private $user;
    private $fcm;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->emptyObject = (object)[];
        $this->user = Auth::guard('api')->user();
        $this->fcm = new FCMController();
    }

    public function GetProfile ()
    {
        try
        {
            $user = User::where("id", $this->user->id)->first();
            if($user->showroom()->exists())
            {
                $user->showroom->email = $user->business_email;
            }
            else
            {
                $user->showroom = $this->emptyObject;
            }
            return $this->common->JsonResponseHandler('success', 'User Profile', $user);
        }
        catch(Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function MyShowroomDetails ()
    {
        $showroom = Showroom::where('user_id', $this->user->id)->first();
        if(empty($showroom))
        {
            return $this->common->JsonResponseHandler('success', 'You are not business user to use this feature.', $this->emptyObject);
        }

        $showroom_cars = Car::where('showroom_id', $showroom->id)->pluck('id')->all();
        $showroom->offers_count = CarOffer::whereIn('car_id', $showroom_cars)->where('status', 'PENDING')->where('counter_amount', NULL)->count();

        $showroom->member_since = date('Y', strtotime($showroom->created_at));

        $showroom->pending_ads = Car::where('showroom_id', $showroom->id)->whereIn('status', ['PENDING', 'REJECTED'])->get();
        $showroom->active_ads = Car::where('showroom_id', $showroom->id)->where('status', 'APPROVED')->get();
        $showroom->sold = Car::where('showroom_id', $showroom->id)->where('status', 'SOLD')->get();

        $user = Auth::guard('api')->user();
        foreach ($showroom->pending_ads as $key => $car) {
            if ($user != null) {
                $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
            } else {
                $car->offer = $this->emptyObject;
            }
        }
        foreach ($showroom->active_ads as $key => $car) {
            if ($user != null) {
                $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
            } else {
                $car->offer = $this->emptyObject;
            }
        }
        foreach ($showroom->sold as $key => $car) {
            if ($user != null) {
                $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
            } else {
                $car->offer = $this->emptyObject;
            }
        }

        return $this->common->JsonResponseHandler('success', 'My Showroom Details', $showroom);
    }

    public function AdMarkSold (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'sold_price' => 'required|numeric',
            'sold_by_gaarilo' => 'required|in:YES,NO',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $car = Car::where('id', $request->car_id)->first();
            if($car->status != "APPROVED")
            {
                return $this->common->JsonResponseHandler('error', 'This ad is not approved yet');
            }
            $car->status = 'SOLD';
            $car->sold_price = $request->sold_price;
            $car->save();
            CarOffer::where('car_id', $request->car_id)->delete();
            Favourite::where('car_id', $request->car_id)->delete();
            return $this->common->JsonResponseHandler('success', 'Ad marked sold successfully');
        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function AdDelete(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $car = Car::where('id', $request->car_id)->first();
            if ($car->status == "SOLD") {
                return $this->common->JsonResponseHandler('error', 'This ad is sold already cannot delete it now');
            }
            $car->delete();

            return $this->common->JsonResponseHandler('success', 'Ad deleted successfully');
        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function AdUpdatePrice(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'price_range' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $car = Car::where('id', $request->car_id)->first();
            if ($car->status == "SOLD") {
                return $this->common->JsonResponseHandler('error', 'This ad is sold already cannot update price now');
            }
            $car->price_range = $request->price_range;
            $car->save();

            return $this->common->JsonResponseHandler('success', 'Ad price updated successfully');
        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function MakeOffer (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'amount' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try {
            $car = Car::where('id', $request->car_id)->first();
            if ($car->status != "APPROVED")
            {
                return $this->common->JsonResponseHandler('error', 'This ad is not approved yet');
            }
            if($car->showroom->user_id == $this->user->id)
            {
                return $this->common->JsonResponseHandler('error', 'You cannot make offer on your own ads');
            }

            $existingOffer = CarOffer::where('car_id', $request->car_id)->where('user_id', $this->user->id)->first();
            if(!empty($existingOffer))
            {
                return $this->common->JsonResponseHandler('error', 'You already made an offer');
            }

            $offer = CarOffer::create([
                'car_id' => $request->car_id,
                'user_id' => $this->user->id,
                'amount' => $request->amount,
            ]);

            $this->fcm->ShowroomOfferPush($request->car_id, $offer->id);

            return $this->common->JsonResponseHandler('success', 'Offer made successfully');
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function MyOffers ()
    {
        $response = array();

        if($this->user->showroom != null)
        {
            $received = Car::where('showroom_id', $this->user->showroom->id)->with('showroom')->has('car_offers')->get();

        }
        else
        {
            $received = [];
        }

        $sent = Car::whereIn('id', CarOffer::where('user_id', $this->user->id)->get(['car_id'])->pluck('car_id'))->with('showroom')->get();

        foreach($received as $value)
        {
            $value->offer = CarOffer::OfferOnCar($this->user->id, $value->id);
        }
        foreach($sent as $value)
        {
            $value->offer = CarOffer::OfferOnCar($this->user->id, $value->id);
        }


        $response['sent_offers'] = $sent;
        $response['received_offers'] = $received;

        return $this->common->JsonResponseHandler('success', 'My Offers', $response);
    }

    public function OfferDetails (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try {
            $offers = CarOffer::where('car_id', $request->car_id)->with('user')->get();
            return $this->common->JsonResponseHandler('success', 'Offer Details', $offers);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function WriteCarReview (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:models,id',
            'version_id' => 'required|exists:versions,id',
            'color_id' => 'required|exists:colors,id',
            'year' => 'required|numeric',
            'exterior' => 'required|numeric',
            'interior' => 'required|numeric',
            'comfort' => 'required|numeric',
            'fuel' => 'required|numeric',
            'performance' => 'required|numeric',
            'parts' => 'required|numeric',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $existingReview = CarReviews::where('user_id', $this->user->id)->where('make', $request->brand_id)->where('model', $request->model_id)
            ->where('color', $request->color_id)->where('version', $request->version_id)->first();
            if(!empty($existingReview))
            {
                return $this->common->JsonResponseHandler('error', 'You already reviewed');
            }

            $carReview = CarReviews::create([
                'user_id' => $this->user->id,
                'year' => $request->year,
                'make' => $request->brand_id,
                'model' => $request->model_id,
                'color' => $request->color_id,
                'exterior' => $request->exterior,
                'interior' => $request->interior,
                'comfort' => $request->comfort,
                'performance' => $request->performance,
                'fuel' => $request->fuel,
                'parts' => $request->parts,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            if($request->has('version_id') && $request->filled('version_id'))
            {
                $carReview->version = $request->version_id;
                $carReview->save();
            }

            return $this->common->JsonResponseHandler('success', 'Car Review Addded Successfully');


        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function MySavedAds()
    {
        $response = Favourite::where('user_id', $this->user->id)->with('car')->get();
        $user = Auth::guard('api')->user();
        foreach ($response as $key => $carObject) {
            if ($user != null) {
                $carObject->car->offer = CarOffer::OfferOnCar($user->id, $carObject->car->id);
            } else {
                $carObject->car->offer = $this->emptyObject;
            }
            $carObject->car->showroom;
        }
        return $this->common->JsonResponseHandler('success', 'My Saved Ads', $response);
    }

    public function FavouriteUnfavouriteAds (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'type' => 'required|in:FAVOURITE,UNFAVOURITE',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try
        {
            if($request->type == "FAVOURITE")
            {
                $existing = Favourite::CheckFavourite($this->user->id, $request->car_id);
                if ($existing == true)
                {
                    return $this->common->JsonResponseHandler('error', "You have already favourited", $this->emptyObject);
                }

                Favourite::create([
                    'user_id' => $this->user->id,
                    'car_id' => $request->car_id,
                ]);

                $response = array(
                    'car_id' => $request->car_id,
                    'favourite' => Favourite::CheckFavourite($this->user->id, $request->car_id),
                );

                return $this->common->JsonResponseHandler('success', "Ad Favourited", $response);
            }
            else
            {
                Favourite::where('user_id', $this->user->id)->where('car_id', $request->car_id)->delete();
                $response = array(
                    'car_id' => $request->car_id,
                    'favourite' => Favourite::CheckFavourite($this->user->id, $request->car_id),
                );
                return $this->common->JsonResponseHandler('success',"Ad Unfavourited", $response);
            }
        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function EditServiceYear (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'year' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $showroom = Showroom::where('user_id', $this->user->id)->first();
            if(empty($showroom))
            {
                return $this->common->JsonResponseHandler('error', "No such showroom exist");
            }

            $showroom->service_year = $request->year;
            $showroom->save();

            return $this->common->JsonResponseHandler('success', "Service year updated");
        }
        catch (Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }


    }

    public function EditProfile (Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'is_business' => 'required|in:YES,NO',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation

            $image = $this->user->image;
            if ($request->hasFile('image')) {
                $image = $request->file("image")->store('/user/profile');
                $image = $this->common->StorageFileURL($image);
            }

            if ($request->is_business == "NO") {
                $user = User::where('id', $this->user->id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $image
                ]);
            } else {
                #region Validation
                $validation = Validator::make($request->all(), [
                    'business_name' => 'required',
                    'business_email' => 'required|email',
                    'business_phone_number' => 'required',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'city_id' => 'required|exists:cities,id',
                ]);

                if ($validation->fails()) {
                    return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
                }
                #endregion Validation

                $business_image = $this->user->business_image;
                if ($request->hasFile('business_image')) {
                    $business_image = $request->file("business_image")->store('/user/profile');
                    $business_image = $this->common->StorageFileURL($business_image);
                }

                $user = User::where('id', $this->user->id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $image,
                    'is_business' => "YES",
                    'business_name' => $request->business_name,
                    'business_email' => $request->business_email,
                    'business_phone_number' => $request->business_phone_number,
                    'business_image' => $business_image,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $showroom = Showroom::where('user_id', $this->user->id)->update([
                    'name' => $request->business_name,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'logo' => $business_image,
                    'phone' => $request->business_phone_number
                ]);
            }

            return $this->common->JsonResponseHandler('success', "Profile updated", $this->emptyObject);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function OfferResponse (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'offer_id' => 'required|exists:car_offers,id',
            'type' => 'required|in:COUNTER,ACCEPTED,REJECTED',
            'price' => 'required_if:type,COUNTER|numeric',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try {

            $offer = CarOffer::where('id', $request->offer_id)->where('status','PENDING')->where('counter_amount', NULL)->first();
            if(empty($offer))
            {
                return $this->common->JsonResponseHandler('error', 'You cannot make offer at this time');
            }

            switch($request->type)
            {
                case "COUNTER":
                    $offer->counter_amount = $request->price;
                    $offer->save();
                    $this->fcm->CounterOfferPush($offer->car_id, $offer->id);
                    break;
                case "ACCEPTED":
                    $offer->status = $request->type;
                    $offer->save();
                    $this->fcm->AcceptedOfferPush($offer->car_id, $offer->id);
                    break;
                case "REJECTED":
                    $offer->status = $request->type;
                    $offer->save();
                    break;

            }

            return $this->common->JsonResponseHandler('success', 'Offer Response Submitted');
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }


}
