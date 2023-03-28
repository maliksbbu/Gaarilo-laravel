<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class H3TechSmsController extends Controller
{
    private $common;
    private $email;
    private $key;
    private $mask;

    public function __construct()
    {
        $this->common = new CommonController();
        $this->email = "hr@gaarilo.com";
        $this->key = "02775b4c044293f1d5234dd7e25073ee19";
        $this->mask = "81478";
    }

    /**
     * Baseline Method
     * Function Name: SendSMSOTP
     * Description: Sends Gsm Otp text message from H3tech server to given number
     * Output Type: Array Response.
     * Parameter Count: 2
     * Parameter 1: Number to which sms should be sent
     * Parameter 2: Random generated otp number
     */
    public function SendSMSOTP($number = "", $otp = "")
    {
        try {
            $body = $this->common->GetSetting('company_name') . PHP_EOL . __('notifications.LBL_OTP_SMS') . ' ' . $otp;
            $result = $this->H3TechSmsCURL($number, $body);
            if ($result['result'] == 1) {
                return $this->common->ArrayResponseHandler('success', 'SMS Sent');
            } else {
                return $this->common->ArrayResponseHandler('error', $result['message']);
            }
        } catch (Exception $e) {
            return $this->common->ArrayResponseHandler('system_error', $e->getMessage());
        }
    }

    function H3TechSmsCURL($number = "", $body = "")
    {
        $url = "https://secure.h3techs.com/sms/api/send";

        $number = str_replace("+", "", $number);
        $mask = urlencode($this->mask);
        $message = urlencode($body);
        $data = "email=" . $this->email . "&key=" . $this->key . "&mask=" . $mask . "&to=" . $number . "&message=" . $message;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        if (curl_errno($ch)) {
            return $this->common->ArrayResponseHandler('error', curl_error($ch));
        }
        curl_close($ch);

        //Nothing to check in response

        return $this->common->ArrayResponseHandler('success', 'SENT');
    }
}
