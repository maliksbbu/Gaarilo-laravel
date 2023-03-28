<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Car;
use App\Models\City;
use App\Models\News;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Video;
use App\Models\Models;
use App\Models\Feature;
use App\Models\Version;
use App\Models\CarOffer;
use App\Models\Province;
use App\Models\Showroom;
use App\Models\Favourite;
use App\Models\CarReviews;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Models\SupportContact;
use App\Models\CarHotspotImage;
use App\Models\ShowroomReviews;
use App\Models\CarExteriorImage;
use App\Models\CarInteriorImage;
use App\Models\OneTimePasswords;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\H3TechSmsController;
use Symfony\Component\HttpFoundation\Session\Session;

class FrontController extends Controller
{
    private $common;
    private $h3Tech;
    private $emptyObject;
    private $fcm;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->h3Tech = new H3TechSmsController();
        $this->fcm = new FCMController();
        $this->emptyObject = (object)[];
    }

    public function Landing()
    {
        $user_id = null;
        if (session()->exists('user')) {
            $user_id = Session('user')->id;
        }
        $user = User::where('id', $user_id)->where('is_business', 'YES')->first();
        $news = News::all();

        $showrooms = Showroom::orderBy('rating', 'DESC')->limit(8)->get();
        $cities = City::all();
        $brands = Brand::all();
        $cars = Car::where('status', 'APPROVED')->limit(8)->get();
        $types = VehicleType::all()->sortBy('name');
        return view('front.landing', compact('news', 'showrooms', 'cities', 'brands', 'cars', 'user', 'types'));
    }

    public function ViewLogin()
    {
        return view('front.auth.login');
    }

    public function SendOTP(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'phone' => 'required|numeric',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation

            $user = User::where('phone', $request->phone)->first();
            if (empty($user)) {
                return $this->common->JsonResponseHandler('error', 'User not registered. Please register first.', $this->emptyObject);
            }

            if ($this->common->GetSetting('verify_otp') == "YES")
            {
                $randomNumber = rand(100000, 999999);
                OneTimePasswords::SaveOTP($request->phone, $randomNumber);
                $this->h3Tech->SendSMSOTP($request->phone, $randomNumber);
            }
            else
            {
                $request->session()->put("user", $user);
                return $this->common->JsonResponseHandler('success', 'Login Successful', $user, 0, 2);
            }

            error_log("OTP: " . $randomNumber); // for debugging
            return $this->common->JsonResponseHandler('success', 'OTP sent successfully', $this->emptyObject);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function VerifyOtp(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'phone' => 'required|exists:users,phone',
                'otp' => 'required|numeric',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation
            if (OneTimePasswords::VerifyOTP($request->phone, $request->otp)) {
                $user = User::where("phone", $request->phone)->first();
                if (empty($user)) {
                    return $this->common->JsonResponseHandler('error', 'User not found', $this->emptyObject);
                }
                $request->session()->put("user", $user);
                return $this->common->JsonResponseHandler('success', 'Login Successful', $user);
            } else {
                return $this->common->JsonResponseHandler('error', 'Invalid OTP', $this->emptyObject);
            }
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function SignOut(Request $request)
    {
        $request->session()->forget('user');
        return redirect()->route('landing');
    }

    public function SignUp()
    {
        $cities  = City::all();
        return view('front.auth.signup', compact('cities'));
    }


    public function SignUpPost(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'required|numeric|unique:users,phone,NULL,id,deleted_at,NULL',
            'image' => 'image|required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file("image")->store('/user/profile');
                $image = (new CommonController)->StorageFileURL($image);
            }

            if ($request->is_business == "NO") {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $image
                ]);
            } else {
                $this->validate($request, [
                    'business_name' => 'required',
                    'business_email' => 'required|email',
                    'business_phone_number' => 'required|numeric',
                    'business_image' => 'image|required|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'main_address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'city' => 'required'
                ]);

                $business_image = "";
                if ($request->hasFile('business_image')) {
                    $business_image = $request->file("business_image")->store('/user/profile');
                    $business_image = (new CommonController)->StorageFileURL($business_image);
                }

                $user = User::create([
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
                    'address' => $request->main_address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $showroom = Showroom::create([
                    'user_id' => $user->id,
                    'name' => $request->business_name,
                    'city_id' => $request->city,
                    'address' => $request->main_address,
                    'logo' => $business_image,
                    'verified' => 'NO', //TODO: To be removed
                ]);
            }

            return redirect('/login')->with('notify_success', 'User Registered!! Sign-in To Proceed');
        } catch (Exception $e) {
            return redirect()->back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    public function ViewPostAd()
    {
        $cities = City::all()->sortBy('name');
        $colors = Color::all();
        $engineTypes = Car::$engineTypes;
        $driveTypes = Car::$driveTypes;
        $features = Feature::all();
        $brands = Brand::all();
        $data['cities'] = $cities;
        $data['colors'] = $colors;
        $data['engineTypes'] = $engineTypes;
        $data['features'] = $features;
        $data['brands'] = $brands;
        $data['driveTypes'] = $driveTypes;

        return view('front.post-add', compact('data'));
    }

    public function ViewEditAd($id)
    {
        $car = Car::where('id', $id)->first();
        $car->make_model = $car->brand->name . '/' . $car->model->name;
        if ($car->version != null) {
            $car->make_model .= '/' . $car->version->name;
        }
        $cities = City::all();
        $colors = Color::all();
        $engineTypes = Car::$engineTypes;
        $driveTypes = Car::$driveTypes;
        $features = Feature::all();
        $brands = Brand::all();
        $data['cities'] = $cities;
        $data['colors'] = $colors;
        $data['engineTypes'] = $engineTypes;
        $data['features'] = $features;
        $data['brands'] = $brands;
        $data['driveTypes'] = $driveTypes;
        $data['car'] = $car;

        return view('front.edit-add', compact('data'));
    }

    public function GetModels(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'brand_id' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation
            $models = Models::where('brand_id', $request->brand_id)->orderBy('name')->get();
            return $this->common->JsonResponseHandler('success', 'Models', $models);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function GetVersions(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'model_id' => 'required',
                'year_model' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation
            $versions = Version::where('model_id', $request->model_id)->where('from_year', '<=', $request->year_model)->where('to_year', '>=', $request->year_model)->orderBy('name')->get();
            return $this->common->JsonResponseHandler('success', 'Versions', $versions);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function GetAllVersions(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'model_id' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation
            $versions = Version::where('model_id', $request->model_id)->get();
            return $this->common->JsonResponseHandler('success', 'Versions', $versions);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }


    public function SubmitEditAd($id, Request $request)
    {
        //Validation Added On View

        try {
            $car = Car::where('id', $id)->first();
            $sessionUser = Session('user');
            if ($sessionUser->is_business == "NO") {
                return back()->with('notify_error', 'You cannot post ad, kindly become business user first')->withInput();
            }

            $existingOffer = CarOffer::where('car_id', $car->id)->where("status", "PENDING")->where("counter_amount", NULL)->first();
            if (!empty($existingOffer)) {
                return $this->common->JsonResponseHandler('error', 'You have Pending Offers on this ad, you cannot edit it now', $this->emptyObject);
            }

            $car->brand_id = $request->car_brand;
            $car->model_id = $request->car_model;

            if ($request->car_version != null) {
                $car->version_id = $request->car_version;
            }

            $car->car_year = $request->car_year;
            $car->condition = $request->condition;
            $car->city_id = $request->city;

            if ($request->has('car_registeration_city') && $request->filled('car_registeration_city')) {
                $car->car_registeration_city = $request->car_registeration_city;
            }
            if ($request->has('car_registeration_year') && $request->filled('car_registeration_year')) {
                $car->car_registeration_year = $request->car_registeration_year;
            }

            $car->exterior_color = $request->exterior_color;

            if ($request->filled('mileage')) {
                $car->mileage = $request->mileage;
            }


            $car->description = $request->description;
            $car->engine_type = $request->engine_type;
            $car->transmission = $request->transmission;
            $car->engine_capacity = $request->engine_capacity;
            $car->drive_type = $request->drive_type;
            $car->assembly = $request->assembly;

            $features = array();
            foreach ($request->feature as $key => $value) {
                $features[] += $value;
            }
            $featuresString = implode(',', $features);
            $car->feature = $featuresString;

            if ($request->secondary_number != null) {
                $car->secondary_number = $request->secondary_number;
            }

            if ($request->whatsapp == "on") {
                $car->whatsapp = "YES";
            }

            $car->price_range = $request->price;

            $car->status = 'PENDING';

            $car->save();

            if ($request->has('exterior_image')) {
                foreach ($request->file("exterior_image") as $key => $value) {
                    $image = $value->store('/car/exterior');
                    $image = (new CommonController)->StorageFileURL($image);

                    if ($key == $request->exterior_image_main) {
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
                    $image = (new CommonController)->StorageFileURL($image);

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
                    $image = (new CommonController)->StorageFileURL($image);

                    CarHotspotImage::where('car_id', $car->id)->delete();

                    CarHotspotImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                    ]);
                }
            }

            if ($request->has('video')) {
                $image = $request->file("video")->store('/car/video');
                $image = (new CommonController)->StorageFileURL($image);

                $car->video = $image;
            }



            $car->save();

            return redirect()->back()->with('notify_success', 'Ad Edited Successfully');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    public function SubmitPostAd(Request $request)
    {
        //Validation Added On View

        try {
            $car = new Car;
            $sessionUser = Session('user');
            if ($sessionUser->is_business == "NO") {
                return back()->with('notify_error', 'You cannot post ad, kindly become business user first')->withInput();
            }
            $userShowroom = Showroom::where('user_id', $sessionUser->id)->first();

            $car->brand_id = $request->car_brand;
            $car->showroom_id = $userShowroom->id;
            $car->model_id = $request->car_model;

            if ($request->car_version != null) {
                $car->version_id = $request->car_version;
            }

            $car->car_year = $request->car_year;
            $car->condition = $request->condition;
            $car->city_id = $request->city;

            if($request->has('car_registeration_city') && $request->filled('car_registeration_city'))
            {
                $car->car_registeration_city = $request->car_registeration_city;
            }
            if($request->has('car_registeration_year') && $request->filled('car_registeration_year'))
            {
                $car->car_registeration_year = $request->car_registeration_year;
            }
            $car->exterior_color = $request->exterior_color;

            if ($request->filled('mileage')) {
                $car->mileage = $request->mileage;
            }


            $car->description = $request->description;
            $car->engine_type = $request->engine_type;
            $car->transmission = $request->transmission;
            $car->engine_capacity = $request->engine_capacity;
            $car->drive_type = $request->drive_type;
            $car->assembly = $request->assembly;

            if(!empty($request->feature)){
            $features = array();
            foreach ($request->feature as $key => $value) {
                $features[] += $value;
            }
            $featuresString = implode(',', $features);
            $car->feature = $featuresString;
            }

            if ($request->secondary_number != null) {
                $car->secondary_number = $request->secondary_number;
            }

            if ($request->whatsapp == "on") {
                $car->whatsapp = "YES";
            }

            $car->price_range = $request->price;

            $car->save();

            foreach ($request->file("exterior_image") as $key => $value) {
                $image = $value->store('/car/exterior');
                $image = (new CommonController)->StorageFileURL($image);


                if ($key == $request->exterior_image_main) {
                    $car->image = $image;
                    $car->rotation = $request->input('rotation_image_'.($key+1));
                }

                CarExteriorImage::create([
                    'car_id' => $car->id,
                    'image' => $image,
                    'rotation' => $request->input('rotation_image_'.($key+1)),
                ]);
            }

            foreach ($request->file("interior_image") as $key => $value) {
                $image = $value->store('/car/interior');
                $image = (new CommonController)->StorageFileURL($image);

                CarInteriorImage::create([
                    'car_id' => $car->id,
                    'image' => $image,
                    'rotation' => $request->input('rotation_image_interior_' . ($key + 1)),
                ]);
            }

            if ($request->has('hotspot_image')) {
                foreach ($request->file("hotspot_image") as $key => $value) {
                    $image = $value->store('/car/hotspot');
                    $image = (new CommonController)->StorageFileURL($image);

                    CarHotspotImage::create([
                        'car_id' => $car->id,
                        'image' => $image,
                        'rotation' => $request->input('rotation_image_hotspot_' . ($key + 1)),
                    ]);
                }
            }

            if ($request->has('video')) {
                $image = $request->file("video")->store('/car/video');
                $image = (new CommonController)->StorageFileURL($image);

                $car->video = $image;
            }



            $car->save();

            return redirect()->back()->with('notify_success', 'Ad Posted');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    public function SearchGrid(Request $request)
    {
        $cars = $this->LandingFilter($request);
        $data['cities'] = City::all()->sortBy('name');
        $data['proviences'] = Province::all();
        $data['brands'] = Brand::all();
        $data['models'] = Models::all();
        $data['versions'] = Version::all();
        $data['colors'] = Color::all();
        $data['vehicle_types'] = VehicleType::all();
        $data['engine_types'] = Car::$engineTypes;
        $data['manual_cars'] = Car::where('transmission', 'Manual')->count();
        $data['automatic_cars'] = Car::where('transmission', 'Automatic')->count();
        $data['local_assembly'] = Car::where('assembly', 'Local')->count();
        $data['importad_assembly'] = Car::where('assembly', 'Imported')->count();

        return view('front.search-grid', compact('data', 'cars'));
    }

    public function SearchList(Request $request)
    {
        $cars = $this->LandingFilter($request);
        return view('front.search-list', compact('cars'));
    }

    function LandingFilter(Request $request)
    {
        $cars = Car::where('status', 'APPROVED'); //TODO Status need to change

        if ($request->has('showroom') && $request->filled('showroom')) {
            if($request->showroom ==  0){
                $cars = $cars;
            }else{
            $cars = $cars->where('showroom_id', $request->showroom);
            }

        }

        // if ($request->has('vehicle_type') && $request->filled('vehicle_type')) {
        //     $cars = $cars->with('model.type')->whereRelation('model', 'type_id', $request->vehicle_type);
        // }

        // if ($request->has('city') && $request->filled('city')) {
        //     $cars = $cars->where('city_id', $request->city);
        // }

        // if ($request->has('price_range') && $request->filled('price_range')) {
        //     $cars = $cars->where('price_range', '<=', $request->price_range);
        // }

        if ($request->has('condition') && $request->filled('condition')) {
            $cars = $cars->where('condition', $request->condition);
        }

        // if ($request->has('model_id') && $request->filled('model_id')) {
        //     $cars = $cars->where('model_id', $request->model_id);
        // }

        // if ($request->has('brand_id') && $request->filled('brand_id')) {
        //     $cars = $cars->where('brand_id', $request->brand_id);
        // }

        // //Showroom Verified filter
        // if ($request->has('verified') && $request->filled('verified')) {
        //     $cars = $cars->with('showroom')->whereRelation('showroom', 'verified', $request->verified);
        // }

        return $cars->/*paginate(21)*/get();
    }

    function InpageFilter(Request $request)
    {
        /*$cars = Car::where('status', 'PENDING'); //TODO Status need to change

        if ($request->has('showroom') && $request->filled('showroom'))
        {
            $cars = $cars->where('showroom_id', $request->showroom);
        }

        if ($request->has('vehicle_type') && $request->filled('vehicle_type'))
        {
           $cars->with('model.type')->whereRelation('model','type_id', $request->vehicle_type);
        }

        if ($request->has('city') && $request->filled('city'))
        {
            $cars = $cars->where('city_id', $request->city);
        }

        if ($request->has('price_range') && $request->filled('price_range'))
        {
            $cars = $cars->where('price_range', '<=', $request->price_range);
        }

        return $cars->get();*/

        $search_cars_filter_city_val = $request->input('search_cars_filter_city_val');
        $search_cars_filter_provience_val = $request->input('search_cars_filter_provience_val');
        $search_cars_filter_brand_val = $request->input('search_cars_filter_brand_val');
        $search_cars_filter_model_val = $request->input('search_cars_filter_model_val');
        $search_cars_filter_version_val = $request->input('search_cars_filter_version_val');
        $search_cars_filter_registered_val = $request->input('search_cars_filter_registered_val');
        $search_cars_filter_transmission_val = $request->input('search_cars_filter_transmission_val');
        $search_cars_filter_engine_type_val = $request->input('search_cars_filter_engine_type_val');
        $search_cars_filter_assembly_val = $request->input('search_cars_filter_assembly_val');
        $search_cars_filter_keword_val = $request->input('search_cars_filter_keword_val');
        $search_cars_price_from_val = (int) $request->input('search_cars_price_from_val');
        $search_cars_price_to_val = (int) $request->input('search_cars_price_to_val');
        $search_cars_year_from_val = (int) $request->input('search_cars_year_from_val');
        $search_cars_year_to_val = (int) $request->input('search_cars_year_to_val');
        $search_cars_mileage_from_val = (int) $request->input('search_cars_mileage_from_val');
        $search_cars_mileage_to_val = (int) $request->input('search_cars_mileage_to_val');
        $search_cars_capacity_from_val = (int) $request->input('search_cars_capacity_from_val');
        $search_cars_capacity_to_val = (int) $request->input('search_cars_capacity_to_val');
        $select_car_orders_val = $request->input('select_car_orders_val') ? $request->input('select_car_orders_val') : 'price_range|asc';
        $select_car_orders_val = explode("|", $select_car_orders_val);

        $cars = Car::where('status', 'APPROVED');

        if (isset($search_cars_filter_city_val) && !empty($search_cars_filter_city_val))  $cars->whereIn('city_id', $search_cars_filter_city_val);
        if (isset($search_cars_filter_provience_val) && !empty($search_cars_filter_provience_val)) {
            $city_ids_by_province = City::whereIn('province_id', $search_cars_filter_provience_val)
            ->pluck('id')->all();
            $cars->whereIn('city_id', $city_ids_by_province);
        }
        if (isset($search_cars_filter_brand_val) && !empty($search_cars_filter_brand_val))  $cars->whereIn('brand_id', $search_cars_filter_brand_val);
        if (isset($search_cars_filter_model_val) && !empty($search_cars_filter_model_val))  $cars->whereIn('model_id', $search_cars_filter_model_val);
        if (isset($search_cars_filter_version_val) && !empty($search_cars_filter_version_val))  $cars->whereIn('version_id', $search_cars_filter_version_val);
        if (isset($search_cars_filter_registered_val) && !empty($search_cars_filter_registered_val))  $cars->whereIn('car_registeration_city', $search_cars_filter_registered_val);
        if (isset($search_cars_filter_transmission_val) && !empty($search_cars_filter_transmission_val))  $cars->whereIn('transmission', $search_cars_filter_transmission_val);
        if (isset($search_cars_filter_engine_type_val) && !empty($search_cars_filter_engine_type_val))  $cars->whereIn('engine_type', $search_cars_filter_engine_type_val);
        if (isset($search_cars_filter_assembly_val) && !empty($search_cars_filter_assembly_val))  $cars->whereIn('assembly', $search_cars_filter_assembly_val);
        if (isset($search_cars_filter_keword_val) && !empty($search_cars_filter_keword_val))  $cars->where('description', 'LIKE', '%' . $search_cars_filter_keword_val . '%');
        if (isset($search_cars_price_from_val) && !empty($search_cars_price_from_val) && isset($search_cars_price_to_val) && !empty($search_cars_price_to_val))  $cars->whereBetween('price_range', [$search_cars_price_from_val, $search_cars_price_to_val]);
        if (isset($search_cars_year_from_val) && !empty($search_cars_year_from_val) && isset($search_cars_year_to_val) && !empty($search_cars_year_to_val))  $cars->whereBetween('car_registeration_year', [$search_cars_year_from_val, $search_cars_year_to_val]);
        if (isset($search_cars_mileage_from_val) && !empty($search_cars_mileage_from_val) && isset($search_cars_mileage_to_val) && !empty($search_cars_mileage_to_val))  $cars->whereBetween('mileage', [$search_cars_mileage_from_val, $search_cars_mileage_to_val]);
        if (isset($search_cars_capacity_from_val) && !empty($search_cars_capacity_from_val) && isset($search_cars_capacity_to_val) && !empty($search_cars_capacity_to_val))  $cars->whereBetween('engine_capacity', [$search_cars_capacity_from_val, $search_cars_capacity_to_val]);

        if (isset($select_car_orders_val) && !empty($select_car_orders_val))  $cars->orderBy($select_car_orders_val[0], $select_car_orders_val[1]);

        return $cars->paginate(21);
        //$query = str_replace(array('?'), array('\'%s\''), $cars->toSql());
        //$query = vsprintf($query, $cars->getBindings());
        //echo $query;
        //die;
    }

    public function SearchCars(Request $request)
    {
        $cars = Car::where('status', 'APPROVED');


        $data['cities'] = City::all();
        $data['proviences'] = Province::all();
        $data['showrooms'] = Showroom::get(['id', 'name']);
        $data['brands'] = Brand::all();
        $data['models'] = Models::all();
        $data['versions'] = Version::all();
        $data['colors'] = Color::all();
        $data['vehicle_types'] = VehicleType::all();
        $data['engine_types'] = Car::$engineTypes;
        $data['manual_cars'] = Car::where('transmission', 'Manual')->where('status', 'APPROVED')->count();
        $data['automatic_cars'] = Car::where('transmission', 'Automatic')->where('status', 'APPROVED')->count();
        $data['local_assembly'] = Car::where('assembly', 'Local')->where('status', 'APPROVED')->count();
        $data['importad_assembly'] = Car::where('assembly', 'Imported')->where('status', 'APPROVED')->count();

        $carCount = $cars;
        $data['pagination_count'] = ceil($carCount->count()/$this->common->pagination_limit);
        $data['cars'] = $cars->limit($this->common->pagination_limit)->get();

        //return view('front.search-cars.search-cars-main', compact('data', 'cars'));
        if ($request->ajax()) {
            return view('front.search-cars.search-cars-list')->with($data)->render();
            // return view('front.search-cars.search-cars-main', [$data])->render();
        }
        return view('front.search-cars.search-cars-main')->with($data);
    }

    public function SearchCarsByViewType(Request $request)
    {
        $data['cars'] = $this->InpageFilter($request);

        $search_view_selected = $request->input('search_view_selected');

        $view_name = ($search_view_selected == 'list') ? 'front.search-cars.search-cars-list' : 'front.search-cars.search-cars-grid';

        $search_car_view = View::make($view_name)->with($data);
        $search_car_view = $search_car_view->render();


        return response()->json([
            'success'                =>  'cars',
            'search_car_view_html'   =>  $search_car_view
        ]);
    }

    public function CarDetail($id)
    {
        $car = Car::where('id', $id)->first();
        $car->ad_views = $car->ad_views + 1;
        $car->save();
        $topPicks = $this->TopPickCar($car->model_id, $car->city_id, $car->id);
        $user = Session('user');
        $ownCar = $this->IsOwnCar($user, $id);
        $car_offer = $this->IsOffer($user, $id);
        return view('front.car-detail', compact('car', 'topPicks', 'ownCar', 'car_offer'));
    }

    public function IncrementCarDetailPhoneCount(Request $request)
    {
        $car = Car::where('id', $request->car_id)->first();
        if (!empty($car)) {
            $car->phone_views = $car->phone_views + 1;
            $car->save();
        }
    }

    function InpageFilterCarsShowrooms(Request $request)
    {

        $search_cars_filter_city_val = $request->input('search_cars_filter_city_val');
        $search_cars_filter_provience_val = $request->input('search_cars_filter_provience_val');
        $search_cars_filter_brand_val = $request->input('search_cars_filter_brand_val');
        $search_cars_filter_model_val = $request->input('search_cars_filter_model_val');
        $search_cars_filter_version_val = $request->input('search_cars_filter_version_val');
        $search_cars_filter_registered_val = $request->input('search_cars_filter_registered_val');
        $search_cars_filter_transmission_val = $request->input('search_cars_filter_transmission_val');
        $search_cars_filter_engine_type_val = $request->input('search_cars_filter_engine_type_val');
        $search_cars_filter_assembly_val = $request->input('search_cars_filter_assembly_val');
        $search_cars_filter_keword_val = $request->input('search_cars_filter_keword_val');

        //$select_car_orders_val = $request->input('select_car_orders_val') ? $request->input('select_car_orders_val') : 'price_range|asc';
        //$select_car_orders_val = explode("|", $select_car_orders_val);

        $cars = Car::select('showroom_id')->distinct();

        if (isset($search_cars_filter_city_val) && !empty($search_cars_filter_city_val))  $cars->whereIn('city_id', $search_cars_filter_city_val);
        if (isset($search_cars_filter_provience_val) && !empty($search_cars_filter_provience_val)) {
            $city_ids_by_province = City::whereIn('province_id', $search_cars_filter_provience_val)->pluck('id')->all();
            $cars->whereIn('city_id', $city_ids_by_province);
        }
        if (isset($search_cars_filter_brand_val) && !empty($search_cars_filter_brand_val))  $cars->whereIn('brand_id', $search_cars_filter_brand_val);
        if (isset($search_cars_filter_model_val) && !empty($search_cars_filter_model_val))  $cars->whereIn('model_id', $search_cars_filter_model_val);
        if (isset($search_cars_filter_version_val) && !empty($search_cars_filter_version_val))  $cars->whereIn('version_id', $search_cars_filter_version_val);
        if (isset($search_cars_filter_registered_val) && !empty($search_cars_filter_registered_val))  $cars->whereIn('car_registeration_city', $search_cars_filter_registered_val);
        if (isset($search_cars_filter_transmission_val) && !empty($search_cars_filter_transmission_val))  $cars->whereIn('transmission', $search_cars_filter_transmission_val);
        if (isset($search_cars_filter_engine_type_val) && !empty($search_cars_filter_engine_type_val))  $cars->whereIn('engine_type', $search_cars_filter_engine_type_val);
        if (isset($search_cars_filter_assembly_val) && !empty($search_cars_filter_assembly_val))  $cars->whereIn('assembly', $search_cars_filter_assembly_val);
        if (isset($search_cars_filter_keword_val) && !empty($search_cars_filter_keword_val))  $cars->where('description', 'LIKE', '%' . $search_cars_filter_keword_val . '%');

        //if (isset($select_car_orders_val) && !empty($select_car_orders_val))  $cars->orderBy($select_car_orders_val[0], $select_car_orders_val[1]);
        return $cars->pluck('showroom_id')->all();
    }

    public function SearchShowrooms(Request $request , $new = null)
    {
        if($request->has('condition')){
            $cars = Car::where('condition', $request->condition)->select('showroom_id')->distinct()->get();
            $showroom_ids = array();
            foreach ($cars as $car) {
                array_push($showroom_ids, $car->showroom_id);
            }
            $showrooms = Showroom::whereIn('id', $showroom_ids);
        }
        else{
            $showrooms = Showroom::limit($this->common->pagination_limit);
        }

        $data['cities'] = City::all();
        $data['proviences'] = Province::all();
        $data['brands'] = Brand::all();
        $data['models'] = Models::all();
        $data['versions'] = Version::all();
        $data['colors'] = Color::all();
        $data['vehicle_types'] = VehicleType::all();
        $data['engine_types'] = Car::$engineTypes;
        $data['manual_cars'] = Car::where('transmission', 'Manual')->count();
        $data['automatic_cars'] = Car::where('transmission', 'Automatic')->count();
        $data['local_assembly'] = Car::where('assembly', 'Local')->count();
        $data['importad_assembly'] = Car::where('assembly', 'Imported')->count();

        $showroomCount = $showrooms;
        $data['pagination_count'] = ceil($showroomCount->count() / $this->common->pagination_limit);
        $data['showrooms'] = $showrooms->limit($this->common->pagination_limit)->orderBy('rating','DESC')->get();

        if ($request->ajax()) {
            return view('front.search-showrooms.search-showrooms-list')->with($data)->render();
        }
        //return view('front.search-cars.search-cars-main', compact('data', 'cars'));
        return view('front.search-showrooms.search-showrooms-main')->with($data);
    }
    public function SearchShowroomsHaveOldCar(Request $request , $verfied = null)
    {
        if($verfied){
            $data['showrooms'] = Showroom::where('verified', 'YES')->paginate(21);
        }
        else{
            $cars = Car::where('condition', 'USED')->select('showroom_id')->distinct()->get();
            $showroom_ids = array();
            foreach ($cars as $car) {
                array_push($showroom_ids, $car->showroom_id);
            }
            $data['showrooms'] = Showroom::whereIn('id', $showroom_ids)->paginate(21);
        }
        $data['cities'] = City::all();
        $data['proviences'] = Province::all();
        $data['brands'] = Brand::all();
        $data['models'] = Models::all();
        $data['versions'] = Version::all();
        $data['colors'] = Color::all();
        $data['vehicle_types'] = VehicleType::all();
        $data['engine_types'] = Car::$engineTypes;
        $data['manual_cars'] = Car::where('transmission', 'Manual')->count();
        $data['automatic_cars'] = Car::where('transmission', 'Automatic')->count();
        $data['local_assembly'] = Car::where('assembly', 'Local')->count();
        $data['importad_assembly'] = Car::where('assembly', 'Imported')->count();

        if ($request->ajax()) {
            return view('front.search-showrooms.search-showrooms-list')->with($data)->render();
        }
        //return view('front.search-cars.search-cars-main', compact('data', 'cars'));
        return view('front.search-showrooms.search-showrooms-main')->with($data);
    }

    public function SearchShowroomsByViewType(Request $request)
    {

        $filtered_showroom_ids = $this->InpageFilterCarsShowrooms($request);
        $data['showrooms'] = Showroom::whereIn('id', $filtered_showroom_ids)->paginate(21);
        $search_view_selected = $request->input('search_view_selected');
        $view_name = ($search_view_selected == 'list') ? 'front.search-showrooms.search-showrooms-list' : 'front.search-showrooms.search-showrooms-grid';

        $search_showrooms_view = View::make($view_name)->with($data);
        $search_showrooms_view = $search_showrooms_view->render();


        return response()->json([
            'success'                =>  'cars',
            'search_showrooms_view_html'   =>  $search_showrooms_view
        ]);


    }

    public function ShowroomDetail($id)
    {
        $data['showroom'] = Showroom::where('id', $id)->first();
        $data['cars'] = Car::where('showroom_id', $id)->where('status', 'APPROVED')->orderBy('price_range','asc')->get();
        return view('front.showroom-detail.showroom-detail-main')->with($data);
    }

    public function supportContact()
    {
        return view('front.support-contact');
    }
    public function saveSupportContact(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'description' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
        }
        $support_contact = new SupportContact();
        $support_contact->name = $request->name;
        $support_contact->phone = $request->phone;
        $support_contact->email = $request->email;
        $support_contact->description = $request->description;
        $support_contact->save();
        return redirect('/')->withSuccess( 'your message will recived we will contact you ASAP' );
    }
    public function ShowroomReviewsDetail($id)
    {
        $showroom = Showroom::where('id', $id)->first();
        $cars = Car::where('showroom_id', $id)->where('status', 'APPROVED')->get();

        // $showroom_rating = ShowroomReviews::where('showroom_id', $id)->get();
        // $showroom_rating_arr = array();
        // foreach($showroom_rating as $rating){
        //       array_push($showroom_rating_arr, $rating->review_rating);
        // }
        // $over_all_rating = array_sum($showroom_rating_arr)/  $showroom_rating->count();


        return view('front.showroom-reviews.showroom-review-main', compact('showroom', 'cars'));
    }

    public function CarOfferPost(Request $request)
    {
        $this->validate($request, [
            'car_id' => 'required',
            'user_id' => 'required',
            'amount' => 'required',
        ]);

        try {
            $offer = CarOffer::create([
                'car_id' => $request->car_id,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
            ]);
            $this->fcm->ShowroomOfferPush($request->car_id, $offer->id);
            return back()->with('notify_success', 'Offer Made Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    public function CarUpdatePrice(Request $request)
    {
        $this->validate($request, [
            'car_id' => 'required',
            'user_id' => 'required',
            'amount' => 'required',
        ]);

        try {

            $car = Car::where('id', $request->car_id)->first();
            if(empty($car))
            {
                return redirect()->back()->with('notify_error','No Such Car Found');
            }

            $car->price_range = $request->amount;
            $car->save();

            return back()->with('notify_success', 'Price Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    public function TopPickCar($model_id, $city_id, $car_id)
    {
        $topPicks = Car::where('id', '!=', $car_id)->where('status', 'APPROVED')->where('city_id', $city_id)->where('model_id', $model_id)->with('showroom')->limit(4)->orderBy('id', 'desc')->get();
        if ($topPicks->count() != 4) {
            $topPicksWithOutCity = Car::where('id', '!=', $car_id)->where('status', 'APPROVED')->where('model_id', $model_id)->with('showroom')->limit(4)->orderByRaw("FIELD(city_id , " . $city_id . ") ASC")->get();
            if ($topPicksWithOutCity->count() == 0) {
                return [];
            } else {
                return $topPicksWithOutCity;
            }
        } else {
            return $topPicks;
        }
    }

    function IsOwnCar($user, $car_id)
    {
        if ($user == null) {
            return false;
        }

        $showRoom = Showroom::where('user_id', $user->id)->first();
        if (empty($showRoom)) {
            return false;
        }

        $car = Car::where('showroom_id', $showRoom->id)->where('id', $car_id)->first();
        if (empty($car)) {
            return false;
        }

        return true;
    }
    function IsOffer($user, $car_id)
    {
        if ($user == null) {
            return false;
        }
        if ($car_id == null) {
            return false;
        }

        $carOffer = CarOffer::where('user_id', $user->id)->where('car_id', $car_id)->first();
        if (empty($carOffer)) {
            return false;
        }

        return true;
    }

    public function AdMarkSold(Request $request)
    {
        $car = Car::where('id', $request->id)->where('status', 'SOLD')->first();
        if (!empty($car)) {
            return back()->with('notify_error', 'Already Mark sold');
        }
        $car = Car::where('id', $request->id)->first();
        $car->status = 'SOLD';
        $car->sold_price =  $request->price;
        $car->save();
        CarOffer::where('car_id',$request->id)->delete();
        Favourite::where('car_id', $request->id)->delete();
        return redirect()->back()->with('notify_success', 'Mark sold suucessfully');
    }

    public function DeleteAd($id)
    {
        $car = Car::where('id', $id)->first();
        if (empty($car)) {
            return back()->with('notify_error', 'No record found');
        }
        Favourite::where('car_id', $car->id)->delete();
        CarOffer::where('car_id', $car->id)->delete();
        CarExteriorImage::where('car_id', $car->id)->delete();
        CarInteriorImage::where('car_id', $car->id)->delete();
        CarHotspotImage::where('car_id', $car->id)->delete();
        $car->delete();
        return redirect()->route('landing')->with('notify_success', 'Ad Deleted suucessfully');
    }

    public function ViewMyOffer()
    {
        $user_id = "";
        if (session()->exists('user')) {
            $user_id = Session('user')->id;
        }

        $cars = Car::where('showroom_id', Showroom::where('user_id', $user_id)->first()->id)->with('car_offers')->has('car_offers')->get();

        return view('front.offers.offer-main', compact('cars'));
    }

    public function ViewOfferSwitch(Request $request)
    {

        $user_id = "";
        if (session()->exists('user')) {
            $user_id = Session('user')->id;
        }

        $viewType = $request->input('view_type');

        if ($viewType == "Received") {
            $data['cars'] = Car::where('showroom_id', Showroom::where('user_id', $user_id)->first()->id)->with('car_offers')->has('car_offers')->get();
        } else {
            $data['cars'] = Car::whereIn('id', CarOffer::where('user_id', $user_id)->get(['car_id'])->pluck('car_id'))->get();
        }

        $data['view_type'] = $viewType;

        $viewOffers = View::make('front.offers.offer-dynamic')->with($data);
        $viewOffers = $viewOffers->render();


        return response()->json([
            'offer_html'   =>  $viewOffers,
        ]);
    }

    public function OfferDetails($id)
    {
        $offers = CarOffer::where('car_id', $id)->get();
        return view('front.offers.offer-details', compact('offers'));
    }

    public function ShowroomOffers($id)
    {
        $cars = Car::where('showroom_id', $id)->get();
        $offerArray= array();
        foreach ($cars as $car ){
            array_push($offerArray, $car->id);
        }
        $offers = CarOffer::whereIn('car_id', $offerArray)->get();
        return view('front.offers.offer-details', compact('offers'));
    }

    public function SubmitCounterOffer($id, Request $request)
    {
        try {
            CarOffer::where('id', $id)->update(['counter_amount' => $request->counter_amount]);
            $offer = CarOffer::where('id', $id)->first();
            $this->fcm->CounterOfferPush($offer->car_id, $id);
            return redirect()->back()->with('notify_success', "Counter Offer Made");
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    public function ChangeOfferStatus($id, $status)
    {
        try {
            CarOffer::where('id', $id)->update(['status' => $status]);
            if($status == "ACCEPTED")
            {
                $offer = CarOffer::where('id', $id)->first();
                $this->fcm->AcceptedOfferPush($offer->car_id, $id);
            }
            return redirect()->back()->with('notify_success', "Status being changed");
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    public function UserProfile()
    {
        $loginUser = Session('user');
        $user = User::where('id', $loginUser->id)->first();
        return view('front.profile.user-profile', compact('user'));
    }

    public function UserProfileStore(Request $request)
    {
        if ($request->showroom_name || $request->showroom_phone ||  $request->showroom_email || $request->main_address) {
            $validation = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email',
                'showroom_name' => 'required',
                'showroom_email' => 'required|email',
                'showroom_phone' => 'required|numeric',
                'main_address' => 'required',
            ]);
        } elseif ($request->has('b_img') || empty($request->showroom_name) || empty($request->showroom_phone) ||  empty($request->showroom_email) || empty($request->main_address)) {
            $validation = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email',
            ]);
        } else {
            $validation = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email',
            ]);
        }
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }
        $user_id = Session('user')->id;
        if ($request->has('image')) {
            $image = $request->file("image")->store('/user/profile');
            $image = (new CommonController)->StorageFileURL($image);
            $user_image = $image;
            User::where('id',  $user_id)->update(['image' => $user_image]);
        }

        if ($request->has('b_img') || empty($request->showroom_name) ||  empty($request->showroom_phone) || empty($request->showroom_email) ||  empty($request->main_address)) {

            $datupdate = ([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,

            ]);
            $request->has('b_img');
            $image = $request->file("b_img")->store('/user/profile');
            $image = (new CommonController)->StorageFileURL($image);
            $b_image = '';
            User::where('id',  $user_id)->update(['business_image' => $b_image]);
        }
        if ($request->has('b_img')) {
            $image = $request->file("b_img")->store('/user/profile');
            $image = (new CommonController)->StorageFileURL($image);
            $b_image = $image;
            User::where('id',  $user_id)->update(['business_image' => $b_image]);
        }

        $datupdate = ([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'business_name' => $request->showroom_name,
            'business_phone_number' => $request->showroom_phone,
            'business_email' => $request->showroom_email,
            'address' => $request->main_address,
        ]);
        User::where('id',  $user_id)->update($datupdate);
        return  redirect()->back()->withErrors('notify_success', "Record updated");
    }

    public function CarReview()
    {
        $data['cars'] = Car::where('status', 'APPROVED')->orderBy('id', 'desc')->take(4)->get();
        $data['car_reviews'] = CarReviews::orderBy('id', 'desc')->take(4)->get();
        $data['brands'] = Brand::get();
        $data['models'] = Models::get();
        return view('front.search-cars.car-reviews-list', compact('data'));
    }
    public function CarAddReview()
    {
        $data['years'] = $this->common->GetYearsList();
        $data['brands'] = Brand::get();
        $data['models'] = Models::get();
        $data['versions'] = Version::get();
        $data['colors'] = Color::get();
        return view('front.search-cars.car-add-reviews', compact('data'));
    }

    public function CarAddReviewStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'year' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
            'exterior' => 'required',
            'interior' => 'required',
            'comfort' => 'required',
            'fuel' => 'required',
            'performance' => 'required',
            'parts' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $existingReview = CarReviews::where('user_id', $request->user_id)->where('make', $request->make)->where('model', $request->model)->first();
        if(!empty($existingReview))
        {
            return redirect()->back()->withInput()->withErrors('You Alreday add review of specfic make and model ');
        }
        $carReview = new CarReviews();
        $carReview->user_id = $request->user_id;
        $carReview->year = $request->year;
        $carReview->make = $request->make;
        $carReview->model = $request->model;
        $carReview->version = $request->version;
        $carReview->color = $request->color;
        $carReview->exterior = $request->exterior;
        $carReview->interior = $request->interior;
        $carReview->comfort = $request->comfort;
        $carReview->performance = $request->performance;
        $carReview->fuel = $request->fuel;
        $carReview->parts = $request->parts;
        $carReview->title = $request->title;
        $carReview->description = $request->description;
        $carReview->save();
        return redirect('/car-review');
    }

    public function SearchCarReviewDynamicView (Request $request)
    {
        $brand_id = $request->brand_id;
        $model_id = $request->model_id;

        $data['car_reviews'] = CarReviews::where('make', $brand_id)->where('model', $model_id);

        if($request->has('version_id') && $request->version_id != null)
        {
            $data['car_reviews'] = $data['car_reviews']->where('version', $request->version_id);
        }

        $data['car_reviews'] = $data['car_reviews']->get();

        $viewReviews = View::make('front.search-cars.car-review-inner-list')->with($data);
        $viewReviews = $viewReviews->render();


        return response()->json([
            'reviews'   =>  $viewReviews,
        ]);

    }

    public function NewsTips ()
    {
        $news = News::all();
        return view('front.news_tips', compact('news'));
    }

    public function VideosReview ()
    {
        $videos = Video::limit(4)->get();
        return view('front.video_reviews', compact('videos'));
    }

    public function SavedAds ()
    {
        if (!session()->exists('user')) {
            return back()->with('notify_error', 'Login to procced');
        }
        $user_id = Session('user')->id;
        $favourites = Favourite::where('user_id', $user_id)->with('car')->get();
        return view('front.saved_ads', compact('favourites'));
    }

    public function UnfavouriteAd ($id, Request $request)
    {
        if (!session()->exists('user')) {
            if($request->ajax())
            {
                return $this->common->JsonResponseHandler('error', "Login to procced");
            }
            return back()->with('notify_error', 'Login to procced');
        }
        $user_id = Session('user')->id;

        Favourite::where('user_id', $user_id)->where('car_id',$id)->delete();
        if ($request->ajax()) {
            return $this->common->JsonResponseHandler('success', "Ad Unfavourited", $id);
        }
        return redirect()->back()->with('notify_success', 'Ad Unfavourited');
    }

    public function FavouriteAd($id, Request $request)
    {
        if (!session()->exists('user')) {
            if ($request->ajax()) {
                return $this->common->JsonResponseHandler('error', "Login to procced");
            }
            return back()->with('notify_error', 'Login to procced');
        }
        $user_id = Session('user')->id;

        $existing = Favourite::CheckFavourite($user_id, $id);
        if($existing == true)
        {
            if ($request->ajax()) {
                return $this->common->JsonResponseHandler('error', "You have already favourited");
            }
            return back()->with('notify_error', 'You have already favourited');
        }

        Favourite::create([
            'user_id' => $user_id,
            'car_id' => $id,
        ]);
        if ($request->ajax()) {
            return $this->common->JsonResponseHandler('success', "Ad Favourited", $id);
        }
        return redirect()->back()->with('notify_success', 'Ad Favourited');
    }

    public function SearchVideos (Request $request)
    {
        $videos = Video::where('brand_model_version', 'like', '%'.$request->key_word.'%')->get();
        return response()->json([
            'videos'   =>  $videos,
        ]);

    }

    public function GetRecommendedShowrooms (Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        if (isset($latitude) && !empty($latitude) && isset($longitude) && !empty($longitude))
        {

        }
        else
        {
            if(session()->exists('user'))
            {
                $sessionUser = Session('user');
                if ($sessionUser->is_business == "NO") {
                    return $this->common->JsonResponseHandler('error', 'No recommended');
                }
                $latitude = $sessionUser->latitude;
                $longitude = $sessionUser->longitude;
            }
            else
            {
                return $this->common->JsonResponseHandler('error', 'No recommended');
            }
        }

        $showrooms = Showroom::all();

        $distance = $this->common->GetSetting('nearby_showroom_radius');
        foreach ($showrooms as $key => $value) {

            $calculatedDistance = $this->common->CalculateDistanceLocal($latitude, $longitude, $value->user->latitude, $value->user->longitude);
            if ($calculatedDistance > $distance) {
                $showrooms->forget($key);
            }
        }

        $showrooms = $showrooms->take(8);

        if($showrooms->isEmpty())
        {
            $showrooms = Showroom::orderBy('rating', 'DESC')->limit(8)->get();
        }

        $data['showrooms'] = $showrooms;

        $viewShowrooms = View::make('front.recommended-showroom')->with($data);
        $viewShowrooms = $viewShowrooms->render();


        return response()->json([
            'result' => 1,
            'html'   =>  $viewShowrooms,
        ]);

    }
}
