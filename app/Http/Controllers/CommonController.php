<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Str;
use App\Models\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    public $pagination_limit = 10;

    public function __construct()
    {

    }

    /**
     * Baseline Method
     * Function Name: ValidationErrorResponse
     * Description: Return Validation Response string and converts all validation
     *              errors into string and pass it to JsonResponseHandler.
     *              Mainly used for REST Api's
     * Output Type: Json Formatted Response
     * Parameter Count: 2
     * Parameter 1: validation_errors object that contains all errors.
     * Parameter 2: data is to keep interface response data type same.
     */
    public function ValidationErrorResponse($validation_errors, $data = [])
    {
        $messages = $validation_errors->messages();
        $keys = array_keys($messages);
        $arr =  "";
        foreach ($keys as $key => $value) {
            $arr .=  ' ' . $value . '||' . $messages[$value][0] . ' ';
        }
        $errorMessage = 'Validation Error' . $arr;

        return $this->JsonResponseHandler('system_error', $errorMessage, $data);
    }

    /**
     * Baseline Method
     * Function Name: JsonResponseHandler
     * Description: Return uniform response for all interfaces interaction.
     *              Mainly used for REST Api's
     * Output Type: Json Formatted Response
     * Parameter Count: 4
     * Parameter 1: success, error or system_error
     * Parameter 2: message to display
     * Parameter 3: data to return and handle
     * Parameter 4: to handle pagination cases
     * Parameter 4: to handle success number
     *              1 means normal case
     *              2 means direct login no otp required
     */
    public function JsonResponseHandler($type, $message, $data = [], $pagination_count = 0, $number = 1)
    {
        if ($type == 'success') {
            return response()->json(['result' =>  $number, 'status' => $type, 'message' => $message, 'pagination_count' => $pagination_count, 'data' => $data]);
        } elseif ($type == 'error') {
            return response()->json(['result' => 0, 'status' => $type, 'message' => $message, 'pagination_count' => $pagination_count, 'data' => $data]);
        } elseif ($type == 'system_error') {
            if ($this->GetSetting('app_mode') == "PRODUCTION") {
                $message = "Something went wrong. Please try again later";
            }
            return response()->json(['result' => 0, 'status' => 'error', 'message' => $message, 'pagination_count' => $pagination_count, 'data' => $data]);
        }
    }

    /**
     * Baseline Method
     * Function Name: ArrayResponseHandler
     * Description: Return uniform response for all interfaces interaction.
     *              Mainly used for within controllers to check CURL responses.
     * Output Type: Array Formatted Response
     * Parameter Count: 3
     * Parameter 1: success, error
     * Parameter 2: message to display
     * Parameter 3: data to return and handle
     */
    public function ArrayResponseHandler($type, $message, $data = [])
    {
        if ($type == 'success') {
            return ['result' => 1, 'status' => $type, 'message' => $message, 'data' => $data];
        } else {
            return ['result' => 0, 'status' => $type, 'message' => $message, 'data' => $data];
        }
    }

    /**
     * Baseline Method
     * Function Name: CalculateDistanceLocal
     * Description: Calculates the distance between 2 points based on unit provided.
     * Output Type: Distance between points (double value)
     * Parameter Count: 5
     * Parameter 1: latitude of first location
     * Parameter 2: longitude of first location
     * Parameter 3: latitude of second location
     * Parameter 4: londitude of second location
     * Parameter 5: unit of distance K, M or N
     */
    public function CalculateDistanceLocal($lat1, $lon1, $lat2, $lon2, $unit = "K")
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round($miles * 1.609344);
        } else if ($unit == "N") {
            return round($miles * 0.8684);
        } else {
            return round($miles);
        }
    }

    /**
     * Baseline Method
     * Function Name: GetSetting
     * Description: Checks the tag name in database and gives value against it.
     * Output Type: String typed value against then tag.
     * Parameter Count: 1
     * Parameter 1: tag name to search against for.
     */
    public function GetSetting($tag_name)
    {
        try {
            return Setting::where('tag_name', $tag_name)->first(['value'])->value;
        } catch (Exception $e) {
            return "";
        }
    }

    public function StorageFileURL($path)
    {
        if ($this->GetSetting('deploy_mode') == 'LOCAL') {
            return $this->GetSetting('local_link') . $path;
        } else {
            return $this->GetSetting('hosting_link') . $path;
        }
    }

    public function GetSiteURL($URI = false)
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        if (!$URI) {
            return $protocol . "://" . $_SERVER['HTTP_HOST'];
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function GetRandomString($length = 1)
    {
        return Str::random($length);
    }

    public function GetYearsList()
    {
        $yearStart = 1940;
        $currentYear = date("Y",strtotime("now"));
        $listYear = array();
        while($yearStart <= $currentYear)
        {
            $listYear[] += $yearStart++;
        }

        return array_reverse($listYear);
    }

    public function FormatNumberIntoK ($value)
    {
        if($value > 1000)
        {
            $value = $value/1000;
            $value .= 'K';
        }
        return $value;
    }

    public function GetTrendingCities ()
    {
        $cities = DB::table('cities')
            ->select('cities.id', DB::raw('COUNT(cars.city_id) AS ad_count'), 'cities.name')
            ->join('cars', 'cities.id', '=', 'cars.city_id')
            ->groupBy('cities.name', 'cities.id')
            ->orderBy('ad_count','DESC')
            ->limit(5)
            ->get();

        return $cities;
    }

    public function GetTrendingBrands ()
    {
        $brands = DB::table('brands')
            ->select('brands.id', DB::raw('COUNT(cars.brand_id) AS ad_count'), 'brands.name')
            ->join('cars', 'brands.id', '=', 'cars.brand_id')
            ->groupBy('brands.name', 'brands.id')
            ->orderBy('ad_count','DESC')
            ->limit(5)
            ->get();

        return $brands;
    }

    public function GetTrendingModels ()
    {
        $models = DB::table('models')
            ->select('models.id', DB::raw('COUNT(cars.model_id) AS ad_count'))
            ->join('cars', 'models.id', '=', 'cars.model_id')
            ->groupBy('models.id')
            ->orderBy('ad_count','DESC')
            ->limit(5)
            ->get();

        $formattedModels = array();
        foreach ($models as $key => $value)
        {
            $tempModel = Models::where('id', $value->id)->first();
            if(!empty($tempModel))
            {
                $temp = array(
                    'id' => $value->id,
                    'formatted_name' => $tempModel->brand->name. ' '. $tempModel->name,
                );
                array_push($formattedModels, $temp);
            }

        }

        return $formattedModels;
    }

    public function GenerateVersionNumber ()
    {
        $tag  = exec('git describe --tags --abbrev=0');
        if (empty($tag)) {
            $tag = '-.-.-';
        }
        $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $date = Carbon::parse(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $string = $tag."-".$hash."-". $date->format('d/m/y-H:i');
        Storage::disk("version")->put("version.bat", password_hash($string, PASSWORD_DEFAULT));
        error_log("Version Number Assigned: ".$string);
    }
}
