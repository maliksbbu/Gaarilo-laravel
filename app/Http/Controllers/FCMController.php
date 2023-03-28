<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarOffer;
use Illuminate\Http\Request;

class FCMController extends Controller
{

    private $common;

    public function __construct()
    {
        $this->common = new CommonController();
    }

    public function test ()
    {
        //$this->AdRejectedPush(1);
    }


    public function ShowroomOfferPush ($ad_id, $offer_id)
    {
        $ad = Car::where('id', $ad_id)->first();
        if(!empty($ad))
        {
            $device_token = $ad->showroom->user->device_token;
            if($device_token != "")
            {
                $offer = CarOffer::where('id', $offer_id)->first();
                $title = __('notifications.LBL_HEADER');
                $body = __("notifications.LBL_SHOWROOM_OFFER_RECEIVED");
                $body = str_ireplace("#PRICE#", $offer->amount , $body);
                $body = str_ireplace("#COLOR#", $ad->color->name, $body);
                $body = str_ireplace("#CAR_NAME#", $ad->car_name, $body);
                $payload = $this->PayloadsFormat($ad_id);
                $this->CurlCall(array($device_token), $title, $body, $payload);
            }
        }
    }

    public function CounterOfferPush($ad_id, $offer_id)
    {
        $ad = Car::where('id', $ad_id)->first();
        $offer = CarOffer::where('id', $offer_id)->first();
        if (!empty($ad)) {
            $device_token = $offer->user->device_token;
            if ($device_token != "") {
                $title = __('notifications.LBL_HEADER');
                $body = __("notifications.LBL_COUNTER_OFFER_SENT");
                $body = str_ireplace("#PRICE#", $offer->counter_amount, $body);
                $body = str_ireplace("#COLOR#", $ad->color->name, $body);
                $body = str_ireplace("#CAR_NAME#", $ad->car_name, $body);
                $body = str_ireplace("#SHOWROOM#", $ad->showroom->name, $body);
                $payload = $this->PayloadsFormat($ad_id);
                $this->CurlCall(array($device_token), $title, $body, $payload);
            }
        }
    }

    public function AcceptedOfferPush($ad_id, $offer_id)
    {
        $ad = Car::where('id', $ad_id)->first();
        $offer = CarOffer::where('id', $offer_id)->first();
        if (!empty($ad)) {
            $device_token = $offer->user->device_token;
            if ($device_token != "") {
                $title = __('notifications.LBL_HEADER');
                $body = __("notifications.LBL_OFFER_ACCEPTED");
                $body = str_ireplace("#PRICE#", $offer->counter_amount, $body);
                $body = str_ireplace("#COLOR#", $ad->color->name, $body);
                $body = str_ireplace("#CAR_NAME#", $ad->car_name, $body);
                $body = str_ireplace("#SHOWROOM#", $ad->showroom->name, $body);
                $payload = $this->PayloadsFormat($ad_id);
                $this->CurlCall(array($device_token), $title, $body, $payload);
            }
        }
    }

    public function AdApprovedPush($ad_id)
    {
        $ad = Car::where('id', $ad_id)->first();
        if (!empty($ad)) {
            $device_token = $ad->showroom->user->device_token;
            if ($device_token != "") {
                $title = __('notifications.LBL_HEADER');
                $body = __("notifications.LBL_AD_APPROVED");
                $body = str_ireplace("#CAR_NAME#", $ad->car_name, $body);
                $payload = $this->PayloadsFormat($ad_id);
                $this->CurlCall(array($device_token), $title, $body, $payload);
            }
        }
    }

    public function AdRejectedPush($ad_id)
    {
        $ad = Car::where('id', $ad_id)->first();
        if (!empty($ad)) {
            $device_token = $ad->showroom->user->device_token;
            if ($device_token != "") {
                $title = __('notifications.LBL_HEADER');
                $body = __("notifications.LBL_AD_REJECTED");
                $body = str_ireplace("#CAR_NAME#", $ad->car_name, $body);
                $payload = $this->PayloadsFormat($ad_id);
                $this->CurlCall(array($device_token), $title, $body, $payload);
            }
        }
    }


    #region Internal functions
    function PayloadsFormat($ad_id = '0')
    {
        return array('ad_id' => $ad_id, 'date_time' => strtotime("now"));
    }

    function CurlCall($token, $title, $body, $payloads)
    {
        $firebaseToken = $this->common->GetSetting('fcm_server');
        if ($payloads == []) {
            $json_data = ["to" => implode(',', $token), "notification" => ["body" => $body, "title" => $title, 'sound' => 'default', 'channelId' => 1], 'priority' => 'high'];
        } else {
            $json_data = ["to" => implode(',', $token), "notification" => ["body" => $body, "title" => $title, 'sound' => 'default', 'channelId' => 1], 'priority' => 'high', "data" => $payloads];
        }
        $data = json_encode($json_data);
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $firebaseToken
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return $this->common->ArrayResponseHandler('error', curl_error($ch));
        }
        curl_close($ch);
        $resp = json_decode($result);
        if ($resp->success > 0) {
            return $this->common->ArrayResponseHandler('success', 'SENT');
        } else {
            return $this->common->ArrayResponseHandler('error', 'Firebase Error: ' . $resp->results[0]->error);
        }
    }
    #endregion
}
