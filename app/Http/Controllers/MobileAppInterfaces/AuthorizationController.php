<?php

namespace App\Http\Controllers\MobileAppInterfaces;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\H3TechSmsController;
use App\Models\OneTimePasswords;
use App\Models\Showroom;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    private $common;
    private $emptyObject;
    private $h3Tech;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->emptyObject = (object)[];
        $this->h3Tech = new H3TechSmsController();
    }

    public function SendOneTimePassword(Request $request)
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
            $randomNumber = rand(100000, 999999);
            OneTimePasswords::SaveOTP($request->phone, $randomNumber);

            if ($this->common->GetSetting('verify_otp') == "YES") {
                $this->h3Tech->SendSMSOTP($request->phone, $randomNumber);
            }

            $response = array('verify_otp' => $this->common->GetSetting('verify_otp'));

            error_log("OTP: " . $randomNumber); // for debugging
            return $this->common->JsonResponseHandler('success', 'OTP sent successfully', $response);
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function Login(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'phone' => 'required|exists:users,phone',
                'otp' => 'required|numeric',
                'device_token' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation

            $deviceToken = '';
            if ($request->filled('device_token')) {
                $deviceToken = $request->device_token;
            }

            if (OneTimePasswords::VerifyOTP($request->phone, $request->otp)) {
                $user = User::where("phone", $request->phone)->first();
                if (empty($user)) {
                    return $this->common->JsonResponseHandler('error', 'User not found', $this->emptyObject);
                }
                // if ($user->status == "INACTIVE") {
                //     return $this->common->JsonResponseHandler('error', 'Your account is inactive due to ' . $user->disable_message, $this->emptyObject);
                // }
                $userToken = Auth::guard("api")->login($user);
                $user->device_token = $deviceToken;
                $user->save();
                $user->token = $userToken;
                $user->showroom()->exists() ? $user->showroom : $user->showroom = $this->emptyObject;

                return $this->common->JsonResponseHandler('success', 'Login Successful', $user);
            } else {
                return $this->common->JsonResponseHandler('error', 'Invalid OTP', $this->emptyObject);
            }
        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

    public function Register(Request $request)
    {
        try {
            #region Validation
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
                'is_business' => 'required|in:YES,NO',
            ]);

            if ($validation->fails()) {
                return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
            }
            #endregion Validation

            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file("image")->store('/user/profile');
                $image = $this->common->StorageFileURL($image);
            }

            if($request->is_business == "NO")
            {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $image
                ]);
            }
            else
            {
                #region Validation
                $validation = Validator::make($request->all(), [
                    'business_name' => 'required',
                    'business_email' => 'required|email',
                    'business_phone_number' => 'required',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'city_id' => 'required|exists:cities,id'
                ]);

                if ($validation->fails()) {
                    return $this->common->ValidationErrorResponse($validation->errors(), $this->emptyObject);
                }
                #endregion Validation

                $business_image = "";
                if ($request->hasFile('business_image')) {
                    $business_image = $request->file("business_image")->store('/user/profile');
                    $business_image = $this->common->StorageFileURL($business_image);
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
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                $showroom = Showroom::create([
                    'user_id' => $user->id,
                    'name' => $request->business_name,
                    'city_id' => $request->city_id,
                    'address' => $request->address,
                    'logo' => $business_image,
                    'phone' => $request->business_phone_number,
                    'verified' => 'NO', //TODO: To be removed
                ]);
            }

            return $this->common->JsonResponseHandler('success', "User have been registered", $this->emptyObject);

        } catch (Exception $e) {
            return $this->common->JsonResponseHandler('system_error', $e->getMessage(), $this->emptyObject);
        }
    }

}
