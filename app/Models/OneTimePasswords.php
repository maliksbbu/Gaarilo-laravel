<?php

namespace App\Models;

use App\Http\Controllers\CommonController;
use Illuminate\Database\Eloquent\Model;

class OneTimePasswords extends Model
{
    protected $guarded = [];

    public static function SaveOTP ($contact, $otp, $type="USER")
    {
        $existingOTP = OneTimePasswords::where('contact', $contact)->where('type',$type)->first();
        if(empty($existingOTP))
        {
            OneTimePasswords::create([
                'contact' => $contact,
                'otp' => $otp,
                'type' => $type,
            ]);
        }
        else
        {
            $existingOTP->otp = $otp;
            $existingOTP->save();
        }
    }

    public static function GetOTP ($contact, $type = "USER")
    {
        $existingOTP = OneTimePasswords::where('contact', $contact)->where('type', $type)->first();
        if(empty($existingOTP))
        {
            return false;
        }
        else
        {
            return $existingOTP->otp;
        }
    }

    public static function ForgotOTP ($contact, $type = "USER")
    {
        $existingOTP = OneTimePasswords::where('contact', $contact)->where('type', $type)->first();
        if(empty($existingOTP))
        {
            return false;
        }
        else
        {
            $existingOTP->delete();
            return true;
        }
    }

    public static function VerifyOTP ($contact, $otp, $type = "USER")
    {
        if((new CommonController())->GetSetting('verify_otp') == "NO")
        {
            OneTimePasswords::ForgotOTP($contact, $type);
            return true;
        }
        $existingOTP = OneTimePasswords::where('contact', $contact)->where('type', $type)->first();
        if(empty($existingOTP) || $existingOTP->otp == "")
        {
            return false;
        }
        else
        {
            if($existingOTP->otp == $otp)
            {
                OneTimePasswords::ForgotOTP($contact, $type);
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}
