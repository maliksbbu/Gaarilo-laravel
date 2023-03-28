<?php

namespace App\Http\Controllers\MobileAppInterfaces;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\H3TechSmsController;
use App\Http\Controllers\Web\FrontController;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarExteriorImage;
use App\Models\CarHotspotImage;
use App\Models\CarInteriorImage;
use App\Models\CarOffer;
use App\Models\CarReviews;
use App\Models\City;
use App\Models\Color;
use App\Models\Favourite;
use App\Models\Feature;
use App\Models\Models;
use App\Models\News;
use App\Models\Showroom;
use App\Models\ShowroomReviewRatings;
use App\Models\ShowroomReviews;
use App\Models\VehicleType;
use App\Models\Version;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    private $common;
    private $emptyObject;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->emptyObject = (object)[];
    }

    public function Splash()
    {
        $splashData = array();
        $splashData['cities'] = City::get(['id', 'name']);
        $splashData['colors'] = Color::get(['id', 'name']);
        $splashData['engine_types'] = Car::$engineTypes;
        $splashData['drive_types'] = Car::$driveTypes;
        $splashData['years'] = $this->common->GetYearsList();
        $splashData['features'] = Feature::get(['id', 'name']);
        $splashData['body_types'] = VehicleType::get(['id', 'name']);
        return $this->common->JsonResponseHandler('success', "Splash", $splashData);
    }

    public function HomeScreen(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'latitude' => 'numeric',
            'longitude' => 'numeric',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try {
            $homeData = array();
            $showRooms = Showroom::get();
            $showRooms = $this->NearbyShowroom($request, $showRooms);
            if(count($showRooms) == 0)
            {
                $showRooms = Showroom::orderBy('created_at','DESC')->get();
            }
            $homeData['showrooms'] = $showRooms;
            $homeData['brands'] = Brand::get(['id', 'name', 'logo']);
            //$homeData['top_picks'] = Car::limit(4)->get(['id', 'brand_id', 'model_id', 'image', 'price_range']);
            $homeData['recommended_showrooms'] = $this->GetRecommendedShowrooms($request->latitude,  $request->longitude);
            return $this->common->JsonResponseHandler('success', "Home Screen", $homeData);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function CarInfo(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'api_type' => 'required|in:GetBrands,GetModels,GetVersions',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation

            $responseData = $this->emptyObject;
            $responseMessage = '';
            switch ($request->api_type) {
                case 'GetBrands':
                    $responseData->brands = Brand::all();
                    $responseMessage = "Brands";
                    break;
                case 'GetModels':
                    #region Validation
                    $validation = Validator::make($request->all(), [
                        'brand_id' => 'required|exists:brands,id',
                    ]);

                    if ($validation->fails()) {
                        return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
                    }
                    #endregion Validation

                    $responseData->models = Models::where('brand_id', $request->brand_id)->get();
                    $responseMessage = "Models";
                    break;
                case 'GetVersions':
                    #region Validation
                    $validation = Validator::make($request->all(), [
                        'model_id' => 'required|exists:models,id',
                        'year' => 'nullable|numeric',
                    ]);

                    if ($validation->fails()) {
                        return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
                    }
                    #endregion Validation

                    if($request->has('year') && $request->filled('year'))
                    {
                        $responseData->versions = Version::where('model_id', $request->model_id)->where('from_year', '<=', $request->year)->where('to_year', '>=', $request->year)->get();
                    }
                    else
                    {
                        $responseData->versions = Version::where('model_id', $request->model_id)->get();
                    }

                    $responseMessage = "Versions";
                    break;
            }
            return $this->common->JsonResponseHandler('success', $responseMessage, $responseData);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function PostAd(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:models,id',
            'version_id' => 'exists:versions,id',
            'car_year' => 'required|numeric',
            'condition' => 'required|in:USED,NEW',
            'car_registeration_city' => 'numeric',
            'car_registeration_year' => 'numeric',
            'exterior_color' => 'required|exists:colors,id',
            'mileage' => 'numeric',
            'description' => 'required|max:255',
            'engine_type' => 'required',
            'transmission' => 'required|in:Automatic,Manual',
            'engine_capacity' => 'required|numeric',
            'drive_type' => 'required',
            'assembly' => 'required|in:Local,Imported',
            'feature' => '',
            'secondary_number' => '',
            'whatsapp' => 'required|in:YES,NO',
            'price_range' => 'required|numeric',
            'exterior_image' => 'required',
            'interior_image' => 'required',
            'hotspot_image' => '',
            'video' => '',
            'main_image_index' => 'required|numeric',

        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try {
            $showRoom = Showroom::where('user_id', Auth::guard('api')->user()->id)->first();
            if (empty($showRoom)) {
                return $this->common->JsonResponseHandler('error', 'No showroom registered with this user', $this->emptyObject);
            }

            $car = new Car;
            $car->city_id = $request->city_id;
            $car->showroom_id = $showRoom->id;
            $car->brand_id = $request->brand_id;
            $car->model_id = $request->model_id;

            if ($request->has('version_id') && $request->filled('version_id')) {
                $car->version_id = $request->version_id;
            }

            $car->car_year = $request->car_year;
            $car->condition = $request->condition;

            if ($request->has('car_registeration_city') && $request->filled('car_registeration_city')) {
                $car->car_registeration_city = $request->car_registeration_city;
            }
            if ($request->has('car_registeration_year') && $request->filled('car_registeration_year')) {
                $car->car_registeration_year = $request->car_registeration_year;
            }

            $car->exterior_color = $request->exterior_color;

            if ($request->has('mileage') && $request->filled('mileage')) {
                $car->mileage = $request->mileage;
            }


            $car->description = $request->description;
            $car->engine_type = $request->engine_type;
            $car->transmission = $request->transmission;
            $car->engine_capacity = $request->engine_capacity;
            $car->drive_type = $request->drive_type;
            $car->assembly = $request->assembly;
            $car->feature = $request->feature;

            if ($request->has('secondary_number') && $request->filled('secondary_number')) {
                $car->secondary_number = $request->secondary_number;
            }

            $car->whatsapp = $request->whatsapp;
            $car->price_range = $request->price_range;

            $car->save();

            foreach ($request->file("exterior_image") as $key => $value) {
                $image = $value->store('/car/exterior');
                $image = $this->common->StorageFileURL($image);

                if ($key == $request->main_image_index) {
                    $car->image = $image;
                }

                CarExteriorImage::create([
                    'car_id' => $car->id,
                    'image' => $image,
                ]);
            }

            foreach ($request->file("interior_image") as $key => $value) {
                $image = $value->store('/car/interior');
                $image = $this->common->StorageFileURL($image);

                CarInteriorImage::create([
                    'car_id' => $car->id,
                    'image' => $image,
                ]);
            }

            if ($request->has('hotspot_image')) {
                foreach ($request->file("hotspot_image") as $key => $value) {
                    $image = $value->store('/car/hotspot');
                    $image = $this->common->StorageFileURL($image);

                    CarHotspotImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                    ]);
                }
            }

            if ($request->has('video')) {
                $image = $request->file("video")->store('/car/video');
                $image = $this->common->StorageFileURL($image);

                $car->video = $image;
            }

            $car->save();

            return $this->common->JsonResponseHandler('success', 'Ad Posted Successfully', $this->emptyObject);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function EditAd(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'city_id' => 'required|exists:cities,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:models,id',
            'version_id' => 'exists:versions,id',
            'car_year' => 'required|numeric',
            'condition' => 'required|in:USED,NEW',
            'car_registeration_city' => 'numeric',
            'car_registeration_year' => 'numeric',
            'exterior_color' => 'required|exists:colors,id',
            'mileage' => 'numeric',
            'description' => 'required|max:255',
            'engine_type' => 'required',
            'transmission' => 'required|in:Automatic,Manual',
            'engine_capacity' => 'required|numeric',
            'drive_type' => 'required',
            'assembly' => 'required|in:Local,Imported',
            'feature' => '',
            'secondary_number' => '',
            'whatsapp' => 'required|in:YES,NO',
            'price_range' => 'required|numeric',
            'exterior_image' => '',
            'interior_image' => '',
            'hotspot_image' => '',
            'video' => '',
            'main_image_index' => 'numeric',

        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try {
            $showRoom = Showroom::where('user_id', Auth::guard('api')->user()->id)->first();
            if (empty($showRoom))
            {
                return $this->common->JsonResponseHandler('error', 'No showroom registered with this user', $this->emptyObject);
            }

            $existingOffer = CarOffer::where('car_id', $request->car_id)->where("status", "PENDING")->where("counter_amount", NULL)->first();
            if(!empty($existingOffer))
            {
                return $this->common->JsonResponseHandler('error', 'You have Pending Offers on this ad, you cannot edit it now', $this->emptyObject);
            }

            $car = Car::where('id', $request->car_id)->first();
            $car->city_id = $request->city_id;
            $car->showroom_id = $showRoom->id;
            $car->brand_id = $request->brand_id;
            $car->model_id = $request->model_id;

            if ($request->has('version_id') && $request->filled('version_id')) {
                $car->version_id = $request->version_id;
            }

            $car->car_year = $request->car_year;
            $car->condition = $request->condition;

            if ($request->has('car_registeration_city') && $request->filled('car_registeration_city')) {
                $car->car_registeration_city = $request->car_registeration_city;
            }
            if ($request->has('car_registeration_year') && $request->filled('car_registeration_year')) {
                $car->car_registeration_year = $request->car_registeration_year;
            }

            $car->exterior_color = $request->exterior_color;

            if ($request->has('mileage') && $request->filled('mileage')) {
                $car->mileage = $request->mileage;
            }


            $car->description = $request->description;
            $car->engine_type = $request->engine_type;
            $car->transmission = $request->transmission;
            $car->engine_capacity = $request->engine_capacity;
            $car->drive_type = $request->drive_type;
            $car->assembly = $request->assembly;
            $car->feature = $request->feature;

            if ($request->has('secondary_number') && $request->filled('secondary_number')) {
                $car->secondary_number = $request->secondary_number;
            }

            $car->whatsapp = $request->whatsapp;
            $car->price_range = $request->price_range;

            $car->status = 'PENDING';

            $car->save();

            if ($request->has('exterior_image')) {
                foreach ($request->file("exterior_image") as $key => $value) {
                    $image = $value->store('/car/exterior');
                    $image = $this->common->StorageFileURL($image);

                    if ($key == $request->main_image_index) {
                        $car->image = $image;
                    }

                    CarExteriorImage::where('car_id', $car->id)->delete();
                    CarExteriorImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                    ]);
                }
            }

            if ($request->has('interior_image')) {
                foreach ($request->file("interior_image") as $key => $value) {
                    $image = $value->store('/car/interior');
                    $image = $this->common->StorageFileURL($image);

                    CarInteriorImage::where('car_id', $car->id)->delete();
                    CarInteriorImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                    ]);
                }
            }

            if ($request->has('hotspot_image')) {
                foreach ($request->file("hotspot_image") as $key => $value) {
                    $image = $value->store('/car/hotspot');
                    $image = $this->common->StorageFileURL($image);

                    CarHotspotImage::where('car_id', $car->id)->delete();
                    CarHotspotImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                    ]);
                }
            }

            if ($request->has('video')) {
                $image = $request->file("video")->store('/car/video');
                $image = $this->common->StorageFileURL($image);

                $car->video = $image;
            }

            $car->save();

            return $this->common->JsonResponseHandler('success', 'Ad Edited Successfully', $this->emptyObject);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function FilterCars(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'city_id' => 'nullable|exists:cities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'model_id' => 'nullable|exists:models,id',
            'version_id' => 'nullable|exists:versions,id',
            'year_from' => 'nullable|numeric',
            'year_to' => 'nullable|numeric',
            'mileage_from' => 'nullable|numeric',
            'mileage_to' => 'nullable|numeric',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'car_registeration_city' => 'nullable|numeric',
            'verified' => 'nullable|in:YES,NO',
            'exterior_color' => 'nullable|exists:colors,id',
            'transmission' => 'nullable|in:Automatic,Manual',
            'engine_type' => 'nullable',
            'engine_capacity_from' => 'nullable|numeric',
            'engine_capacity_to' => 'nullable|numeric',
            'assembly' => 'nullable|in:Local,Imported',
            'body_type_id' => 'nullable|exists:vehicle_types,id',
            'keyword' => 'nullable'

        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation
        try {

            #region Filter Section
            $cars = Car::where('status', 'APPROVED'); //TODO Status need to change

            if ($request->has('city_id') && $request->filled('city_id')) {
                $cars = $cars->where('city_id', $request->city_id);
            }
            if ($request->has('brand_id') && $request->filled('brand_id')) {
                $cars = $cars->where('brand_id', $request->brand_id);
            }
            if ($request->has('model_id') && $request->filled('model_id')) {
                $cars = $cars->where('model_id', $request->model_id);
            }
            if ($request->has('version_id') && $request->filled('version_id')) {
                $cars = $cars->where('version_id', $request->version_id);
            }
            if ($request->has('year_from') && $request->filled('year_from')) {
                $cars = $cars->where('car_year', '>=', $request->year_from);
            }
            if ($request->has('year_to') && $request->filled('year_to')) {
                $cars = $cars->where('car_year', '<=', $request->year_to);
            }
            if ($request->has('mileage_from') && $request->filled('mileage_from')) {
                $cars = $cars->where('mileage', '>=', $request->mileage_from);
            }
            if ($request->has('mileage_to') && $request->filled('mileage_to')) {
                $cars = $cars->where('mileage', '<=', $request->mileage_to);
            }
            if ($request->has('price_from') && $request->filled('price_from')) {
                $cars = $cars->where('price_range', '>=', $request->price_from);
            }
            if ($request->has('price_to') && $request->filled('price_to')) {
                $cars = $cars->where('price_range', '<=', $request->price_to);
            }
            if ($request->has('car_registeration_city') && $request->filled('car_registeration_city')) {
                $cars = $cars->where('car_registeration_city', $request->car_registeration_city);
            }
            if ($request->has('verified') && $request->filled('verified') && $request->verified == "YES") {
                $cars = $cars->with('showroom')->whereRelation('showroom', 'verified', $request->verified);
            }
            if ($request->has('exterior_color') && $request->filled('exterior_color')) {
                $cars = $cars->where('exterior_color', $request->exterior_color);
            }
            if ($request->has('transmission') && $request->filled('transmission')) {
                $cars = $cars->where('transmission', $request->transmission);
            }
            if ($request->has('engine_type') && $request->filled('engine_type')) {
                $cars = $cars->where('engine_type', $request->engine_type);
            }
            if (
                $request->has('engine_capacity_from') && $request->filled('engine_capacity_from')
            ) {
                $cars = $cars->where('engine_capacity', '>=', $request->engine_capacity_from);
            }
            if ($request->has('engine_capacity_to') && $request->filled('engine_capacity_to')) {
                $cars = $cars->where('engine_capacity', '<=', $request->engine_capacity_to);
            }
            if ($request->has('assembly') && $request->filled('assembly')) {
                $cars = $cars->where('assembly', $request->assembly);
            }
            if ($request->has('body_type_id') && $request->filled('body_type_id')) {
                $cars = $cars->with('model')->whereRelation('model', 'type_id', $request->body_type_id);
            }
            if ($request->has('keyword') && $request->filled('keyword')) {
                $cars = $cars->with('brand', 'model')->whereRelation('brand', 'name', 'LIKE', '%' . $request->keyword . '%')
                ->orWhereRelation('model', 'name', 'LIKE', '%' . $request->keyword . '%');
            }
            #endregion Filter Section

            $pagination_count = $this->GetPaginationCount($cars);
            $page = $request->has('page') && $request->filled('page') ? $request->page : 1;
            $cars = $cars->with('showroom')->offset($this->common->pagination_limit * ($page - 1))->limit($this->common->pagination_limit)->get([
                'id', 'showroom_id', 'brand_id', 'model_id', 'version_id', 'image', 'video',
                'car_year', 'price_range', 'mileage', 'drive_type', 'transmission', 'updated_at',
                'city_id', 'engine_type',
            ]);

            $user = Auth::guard('api')->user();
            foreach ($cars as $key => $car)
            {
                if($user != null)
                {
                    $car->favourite = Favourite::CheckFavourite($user->id, $car->id);
                    $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
                }
                else
                {
                    $car->favourite = false;
                    $car->offer = $this->emptyObject;
                }
            }


            return $this->common->JsonResponseHandler('success', 'Car Filter', $cars, $pagination_count);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function FilterShowrooms(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'city_id' => 'nullable|exists:cities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'model_id' => 'nullable|exists:models,id',
            'version_id' => 'nullable|exists:versions,id',
            'verified' => 'nullable|in:YES,NO',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation
        try {

            #region Filter Section
            $showrooms = Showroom::select('*');

            if ($request->has('city_id') && $request->filled('city_id')) {
                $showrooms = $showrooms->where('city_id', $request->city_id);
            }
            if ($request->has('brand_id') && $request->filled('brand_id')) {
                $cars = Car::where('brand_id', $request->brand_id)->pluck('showroom_id')->all();
                $showrooms = $showrooms->whereIn('id', $cars);
            }
            if ($request->has('model_id') && $request->filled('model_id')) {
                $cars = Car::where('model_id', $request->model_id)->pluck('showroom_id')->all();
                $showrooms = $showrooms->whereIn('id', $cars);
            }
            if ($request->has('version_id') && $request->filled('version_id')) {
                $cars = Car::where('version_id', $request->version_id)->pluck('showroom_id')->all();
                $showrooms = $showrooms->whereIn('id', $cars);
            }
            if ($request->has('verified') && $request->filled('verified') && $request->verified == "YES") {
                $showrooms = $showrooms->where('verified', $request->verified);
            }
            #endregion Filter Section

            $pagination_count = $this->GetPaginationCount($showrooms);
            $page = $request->has('page') && $request->filled('page') ? $request->page : 1;
            $showrooms = $showrooms->offset($this->common->pagination_limit * ($page - 1))->limit($this->common->pagination_limit)->get();

            return $this->common->JsonResponseHandler('success', 'Showroom Filter', $showrooms, $pagination_count);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function CarDetails(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation
        try {
            $car = Car::where('id', $request->car_id)->with('exterior_images', 'interior_images', 'hotspot_images', 'showroom')->first();
            $top_picks = (new FrontController())->TopPickCar($car->model_id, $car->city_id, $car->id);

            $user = Auth::guard('api')->user();
            if ($user != null) {
                $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
                $car->favourite = Favourite::CheckFavourite($user->id, $car->id);
            } else {
                $car->offer = $this->emptyObject;
                $car->favourite = false;
            }
            foreach ($top_picks as $key => $value)
            {
                if ($user != null) {
                    $value->offer = CarOffer::OfferOnCar($user->id, $value->id);
                    $value->favourite = Favourite::CheckFavourite($user->id, $value->id);
                } else {
                    $value->offer = $this->emptyObject;
                    $value->favourite = false;
                }
            }
            $car->top_picks = $top_picks;
            return $this->common->JsonResponseHandler('success', 'Car Details', $car);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function ShowroomDetails(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'showroom_id' => 'required|exists:showrooms,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try {
            $showroom = Showroom::where('id', $request->showroom_id)->first();
            $showroom->latitude = $showroom->user->latitude;
            $showroom->longitude = $showroom->user->longitude;
            $showroom->whatsapp = $showroom->user->whatsapp == null ? "no" : "yes";

            $showroom->car_for_sale = Car::where('showroom_id', $request->showroom_id)->where('status', 'APPROVED')->get();

            $user = Auth::guard('api')->user();
            foreach ($showroom->car_for_sale as $key => $car) {
                if ($user != null) {
                    $car->favourite = Favourite::CheckFavourite($user->id, $car->id);
                    $car->offer = CarOffer::OfferOnCar($user->id, $car->id);
                } else {
                    $car->favourite = false;
                    $car->offer = $this->emptyObject;
                }
            }

            return $this->common->JsonResponseHandler('success', 'Showroom Details', $showroom);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function ShowroomReviews(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'showroom_id' => 'required|exists:showrooms,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        #endregion Validation

        try {
            $showroom = Showroom::where('id', $request->showroom_id)->first();
            $showroom->latitude = $showroom->user->latitude;
            $showroom->longitude = $showroom->user->longitude;

            $showroom->reviews = ShowroomReviews::where('showroom_id', $request->showroom_id)->with('user', 'reviewsratings')->get();

            foreach($showroom->reviews as $review)
            {
                $review->user->member_since = date('d/m/Y', strtotime($review->user->created_at));
            }

            return $this->common->JsonResponseHandler('success', 'Showroom Reviews', $showroom);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function ShowroomWriteReview (Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'showroom_id' => 'required|exists:showrooms,id',
            'dealing_rating' => 'required|numeric',
            'selection_rating' => 'required|numeric',
            'service_rating' => 'required|numeric',
            'review_title'  => 'required',
            'review_description'  => 'required',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try {
            $user = Auth::guard('api')->user();
            $ownShowroom = Showroom::where('id', $request->showroom_id)->where('user_id', $user->id)->first();
            if(!empty($ownShowroom))
            {
                return $this->common->JsonResponseHandler('error', 'You cannot write review for you own showroom');
            }

            $existingReview = user_id_in_showroom($request->showroom_id, $user->id);
            if($existingReview == true)
            {
                return $this->common->JsonResponseHandler('error', 'You already add review for this showroom');
            }

            $showroom_review = new ShowroomReviews();

            $avg_rating = ($request->input('dealing_rating') + $request->input('selection_rating') + $request->input('service_rating')) / 3;

            $showroom_review->user_id = $user->id;
            $showroom_review->review_type = 'OVERALL';
            $showroom_review->review_rating = round($avg_rating, 1);
            $showroom_review->showroom_id = $request->showroom_id;
            $showroom_review->review_title = $request->review_title;
            $showroom_review->review_description = $request->review_description;
            $showroom_review->save();

            $showroom_review_rating = new ShowroomReviewRatings();
            $showroom_review_rating->showroom_id = $request->showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('dealing_rating');
            $showroom_review_rating->rating_type = 'DEALING';
            $showroom_review_rating->save();

            $showroom_review_rating = new ShowroomReviewRatings();
            $showroom_review_rating->showroom_id = $request->showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('selection_rating');
            $showroom_review_rating->rating_type = 'SELECTION';
            $showroom_review_rating->save();

            $showroom_review_rating = new ShowroomReviewRatings();
            $showroom_review_rating->showroom_id = $request->showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('service_rating');
            $showroom_review_rating->rating_type = 'SERVICE';
            $showroom_review_rating->save();

            $showroom_avg_rating = ShowroomReviews::avg('review_rating');

            Showroom::where('id', $request->showroom_id)->update([
                'rating' => round($showroom_avg_rating)
            ]);


            return $this->common->JsonResponseHandler('success', 'Review Submiited');
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function CarReviews(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'brand_id' => 'nullable|exists:brands,id',
            'model_id' => 'nullable|exists:models,id',
            'version_id' => 'nullable|exists:versions,id',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try {
            $carReviews = CarReviews::with('user');

            if ($request->has('brand_id') && $request->filled('brand_id')) {
                $carReviews = $carReviews->where('make', $request->brand_id);
            }
            if ($request->has('model_id') && $request->filled('model_id')) {
                $carReviews = $carReviews->where('model', $request->model_id);
            }
            if ($request->has('version_id') && $request->filled('version_id')) {
                $carReviews = $carReviews->where('version', $request->version_id);
            }

            $carReviews = $carReviews->get();

            return $this->common->JsonResponseHandler('success', 'Car Reviews', $carReviews);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function VideoReviews(Request $request)
    {
        #region Validation
        $validation = Validator::make($request->all(), [
            'keyword' => 'nullable',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors());
        }
        #endregion Validation

        try
        {
            $videos = Video::orderBy('id','ASC');
            if($request->has('keyword') && $request->filled('keyword'))
            {
                $videos = $videos->where('brand_model_version', 'like', '%' . $request->keyword . '%');
            }
            $pagination_count = $this->GetPaginationCount($videos);
            $page = $request->has('page') && $request->filled('page') ? $request->page : 1;
            $videos = $videos->offset($this->common->pagination_limit * ($page - 1))->limit($this->common->pagination_limit)->get();

            return $this->common->JsonResponseHandler('success', 'Video Reviews', $videos, $pagination_count);
        }
        catch(Exception $e)
        {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage());
        }
    }

    public function NewsTips()
    {
        $news = News::all();
        return $this->common->JsonResponseHandler('success', 'News Tips', $news);
    }

    function NearbyShowroom($request, $collection)
    {
        $search_cars_filter_latitude_val = $request->input('latitude');
        $search_cars_filter_longitude_val = $request->input('longitude');
        if (
            isset($search_cars_filter_latitude_val) && !empty($search_cars_filter_latitude_val) &&
            isset($search_cars_filter_longitude_val) && !empty($search_cars_filter_longitude_val)
        ) {
            $distance = $this->common->GetSetting('nearby_showroom_radius');
            $showRoomArray = array();
            foreach ($collection as $key => $value) {
                $calculatedDistance = $this->common->CalculateDistanceLocal($search_cars_filter_latitude_val, $search_cars_filter_longitude_val, $value->user->latitude, $value->user->longitude);
                if ($calculatedDistance <= $distance) {
                    array_push($showRoomArray, $value);
                }
            }
        }
        return $showRoomArray;
    }

    function GetPaginationCount($collection)
    {
        return ceil($collection->count() / $this->common->pagination_limit);
    }

    function GetRecommendedShowrooms($latitude, $longitude)
    {
        $check = true;
        if (isset($latitude) && !empty($latitude) && isset($longitude) && !empty($longitude))
        {

        }
        else
        {
            $user = Auth::guard('api')->user();
            if ($user != null) {
                if ($user->is_business == "YES")
                {
                    $latitude = $user->latitude;
                    $longitude = $user->longitude;
                }
            }
            else
            {
                $check = false;
            }
        }

        if($check)
        {
            $showrooms = Showroom::all();

            $distance = $this->common->GetSetting('nearby_showroom_radius');
            foreach ($showrooms as $key => $value) {

                $calculatedDistance = $this->common->CalculateDistanceLocal($latitude, $longitude, $value->user->latitude, $value->user->longitude);
                if ($calculatedDistance > $distance) {
                    $showrooms->forget($key);
                }
            }

            $showrooms = $showrooms->take(8);

            if ($showrooms->isEmpty()) {
                $showrooms = Showroom::orderBy('rating', 'DESC')->limit(8)->get();
            }
        }
        else
        {
            $showrooms = Showroom::orderBy('rating', 'DESC')->limit(8)->get();
        }

        return $showrooms;
    }

}
