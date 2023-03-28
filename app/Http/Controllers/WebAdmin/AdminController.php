<?php

namespace App\Http\Controllers\WebAdmin;

use Exception;
use App\Models\Car;
use App\Models\User;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\CarOffer;
use App\Models\Showroom;
use App\Models\Favourite;
use App\Models\CarReviews;
use Illuminate\Http\Request;
use App\Models\CarHotspotImage;
use App\Models\CarExteriorImage;
use App\Models\CarInteriorImage;
use App\Http\Controllers\Controller;
use App\Models\ShowroomVerification;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\CommonController;

class AdminController extends Controller
{
    private $common;
    private $fcm;

    public function __construct()
    {
        $this->middleware('admin');
        $this->common = new CommonController;
        $this->fcm = new FCMController();
    }

    public function dashboard()
    {
        $data = array();
        $pendingCount = Car::where('status', 'PENDING')->count();
        $showRoomCount = Showroom::count();
        $soldCount = Car::where('status', 'SOLD')->count();
        $userCount = User::count();

        $data['pendingCount'] = $pendingCount;
        $data['showRoomCount'] = $showRoomCount;
        $data['soldCount'] = $soldCount;
        $data['userCount'] = $userCount;

        $data['offersPending'] = CarOffer::where('status', 'PENDING')->count();
        $data['offersAccepted'] = CarOffer::where('status', 'ACCEPTED')->count();
        $data['offersRejected'] = CarOffer::where('status', 'REJECTED')->count();

        return view('admin.dashboard', compact('data'));
    }

    public function viewSettings()
    {
        $settings = Setting::where('display', 'YES')->get();
        return view('admin.setting', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        try {

            $user = auth()->guard('admin')->user();
            if($user->role_id != "0")
            {
                return back()->with('notify_error', "Only super admin should be able to change settings");
            }

            $settings = Setting::where('display', 'YES')->get();
            foreach ($settings as $key => $value) {
                if ($value->type == 'file') {
                    if ($request->hasFile($value->tag_name)) {
                        $path = $request->file($value->tag_name)->store('/admin/settings');
                        $path = (new CommonController)->storageFileURL($path);
                        Setting::where('tag_name', $value->tag_name)->update(['value' => $path]);
                    }
                } else {
                    Setting::where('tag_name', $value->tag_name)->update(['value' => $request[$value->tag_name]]);
                }
            }
            return back()->with('notify_success', 'Settings Updated');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    public function PendingAds()
    {
        $data['cars'] = Car::where('status', 'PENDING')->get();
        return view('admin.pending-ads')->with($data);
    }

    public function AdminSoldCars()
    {
        $data['cars'] = Car::where('status', 'SOLD')->get();
        return view('admin.sold-cars')->with($data);
    }

    public function AdminShowrooms()
    {
        $data['showrooms'] = Showroom::where('status', 1)->paginate(100);
        return view('admin.showrooms')->with($data);
    }

    public function AdminShowroomDetail ($id)
    {
        $showroom = Showroom::where('id', $id)->first();
        $cars = Car::where('showroom_id', $showroom->id)->get();
        return view('admin.showroom-detail', compact('showroom', 'cars'));
    }

    public function AdminUsers()
    {
        $data['users'] = User::paginate(100);
        return view('admin.users')->with($data);
    }

    public function TargetTracker()
    {
        $admins = Admin::all();
        return view('admin.target-tracker', compact('admins'));
    }

    public function AdminCarOffers()
    {
        $data['offers'] = CarOffer::paginate(100);
        return view('admin.car-offers')->with($data);
    }

    public function VerfiedShowroom($id){

        Showroom::where('id', $id)->update(['verified' => 'YES']);
        return back()->with('notify_success', 'Showroom Updated');
    }
    public function ShowroomDelete($id){

        $cars = Car::where('showroom_id', $id)->pluck('id')->all();
        if (!empty($cars)) {
            Favourite::whereIn('car_id', $cars)->delete();
            CarOffer::whereIn('car_id', $cars)->delete();
            CarExteriorImage::whereIn('car_id', $cars)->delete();
            CarInteriorImage::whereIn('car_id', $cars)->delete();
            CarHotspotImage::whereIn('car_id', $cars)->delete();
        }
        Car::where('showroom_id', $id)->delete();
        $showroom = Showroom::where('id', $id)->first();
        $user = User::where('id', $showroom->user_id)->first();
        $user->is_business = "NO";
        $user->business_name = "";
        $user->business_email = "";
        $user->business_image = "";
        $user->business_phone_number = "";
        $user->latitude = "";
        $user->longitude = "";
        $user->address = "";
        $user->save();
        $showroom->delete();

        return back()->with('notify_success', 'Showroom Deleted');
    }

    public function updateShowRoomMarkVerified(Request $request)
    {
        try {

            $action = $request->input('adm_showroom_mark_action');

            if ($action == 'verify' && !empty($request->input('adm_showroom_mark'))) {
                Showroom::whereIn('id', $request->input('adm_showroom_mark'))
                    ->update(['verified' => 'YES']);


                foreach ($request->adm_showroom_mark as $key => $value)
                {
                    ShowroomVerification::create([
                        'showroom_id' => $value,
                        'admin_id' => auth()->guard('admin')->user()->id,
                    ]);
                }
            } else if ($action == 'delete' && !empty($request->input('adm_showroom_mark'))) {
                Showroom::whereIn('id', $request->input('adm_showroom_mark'))
                    ->update(['status' => 0]);
            }

            return back()->with('notify_success', 'Showroom Updated');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    public function updatePendingAdsStatus(Request $request)
    {
        try {

            $action = $request->input('adm_pending_ads_action');

            if ($action == 'accept' && !empty($request->input('adm_pending_ads_ids'))) {
                Car::whereIn('id', $request->input('adm_pending_ads_ids'))
                    ->update(['status' => 'APPROVED']);
                $cars = Car::whereIn('id', $request->input('adm_pending_ads_ids'))->get();
                foreach($cars as $car)
                {
                    $this->fcm->AdApprovedPush($car->id);
                }
            } else if ($action == 'reject' && !empty($request->input('adm_pending_ads_ids'))) {
                Car::whereIn('id', $request->input('adm_pending_ads_ids'))
                    ->update(['status' => 'REJECTED']);
                $cars = Car::whereIn('id', $request->input('adm_pending_ads_ids'))->get();
                foreach ($cars as $car) {
                    $this->fcm->AdRejectedPush($car->id);
                }
            }

            return back()->with('notify_success', 'Ads Updated');
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }

    public function DeleteUser ($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if(!empty($user))
            {
                if(!empty($user->showroom)){
                    $cars = Car::where('showroom_id', $user->showroom->id)->pluck('id')->all();
                    if(!empty($cars)){
                    Favourite::whereIn('car_id', $cars)->delete();
                    CarOffer::whereIn('car_id', $cars)->delete();
                    CarExteriorImage::whereIn('car_id', $cars)->delete();
                    CarInteriorImage::whereIn('car_id', $cars)->delete();
                    CarHotspotImage::whereIn('car_id', $cars)->delete();
                    }
                    Car::where('showroom_id', $user->showroom->id)->delete();
                }
                Showroom::where('user_id', $id)->delete();
                CarReviews::where('user_id', $id)->delete();
                CarOffer::where('user_id', $id)->delete();
                $user->delete();

                return redirect()->route('admin.admin-web-users')->with('notify_success', 'User Deleted');

            }
            else
            {
                return back()->with('notify_error', 'No Such User Found');
            }
        } catch (Exception $e) {
            return back()->with('notify_error', $e->getMessage());
        }
    }
}
