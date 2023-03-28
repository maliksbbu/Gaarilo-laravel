<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\City;
use App\Models\Models;
use App\Models\Showroom;
use App\Models\Version;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    private $common;

    public function __construct()
    {
        $this->common = new CommonController();
    }

    public function GetCities(Request $request)
    {
        if ($request->province_id != 0)
            $data['cities'] = City::where('province_id', $request->province_id)->get();
        else
            $data['cities'] = City::all();

        $data['for'] = $request->for;
        $view = View::make('front.search-cars.filters-components.city-filter', $data);
        $view = $view->render();

        return response()->json([
            'html'   =>  $view
        ]);
    }

    public function GetModels(Request $request)
    {
        if ($request->brand_id != 0)
            $data['models'] = Models::where('brand_id', $request->brand_id)->get();
        else
            $data['models'] = Models::all();

        $view = View::make('front.search-cars.filters-components.model-filter', $data);
        $view = $view->render();

        return response()->json([
            'html'   =>  $view
        ]);
    }

    public function GetVersions(Request $request)
    {
        if ($request->model_id != 0)
            $data['versions'] = Version::where('model_id', $request->model_id)->get();
        else
            $data['versions'] = Version::all();

        $view = View::make('front.search-cars.filters-components.version-filter', $data);
        $view = $view->render();

        return response()->json([
            'html'   =>  $view
        ]);
    }

    function Filter($request)
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
        $search_cars_filter_vehicle_val = $request->input('search_cars_filter_vehicle_val');
        $search_cars_filter_verified_val = $request->input('search_cars_filter_verified_val');
        $search_cars_filter_color_val = $request->input('search_cars_filter_color_val');
        $search_cars_filter_showroom_id = $request->input('search_cars_filter_showroom_id');
        $search_cars_filter_condition = $request->input('search_cars_filter_condition');

        $select_car_orders_val = $request->input('select_car_orders_val') ? $request->input('select_car_orders_val') : 'price_range|asc';
        $select_car_orders_val = explode("|", $select_car_orders_val);

        $page = $request->input('page');
        $view_update = $request->input('view_update');

        $cars = Car::where('status', 'APPROVED');

        #region Filter Section
        if (isset($search_cars_filter_city_val) && !empty($search_cars_filter_city_val)) {
            $cars->where('city_id', $search_cars_filter_city_val);
        }
        if (isset($search_cars_filter_provience_val) && !empty($search_cars_filter_provience_val)) {
            $city_ids_by_province = City::where('province_id', $search_cars_filter_provience_val)
                ->pluck('id')->all();
            $cars->whereIn('city_id', $city_ids_by_province);
        }
        if (isset($search_cars_filter_brand_val) && !empty($search_cars_filter_brand_val)) {
            $cars->where('brand_id', $search_cars_filter_brand_val);
        }
        if (isset($search_cars_filter_model_val) && !empty($search_cars_filter_model_val)) {
            $cars->where('model_id', $search_cars_filter_model_val);
        }
        if (isset($search_cars_filter_version_val) && !empty($search_cars_filter_version_val)) {
            $cars->where('version_id', $search_cars_filter_version_val);
        }
        if (
            $request->has('search_cars_price_from_val') && $request->filled('search_cars_price_from_val') &&
            $request->has('search_cars_price_to_val') && $request->filled('search_cars_price_to_val')
        ) {
            $cars->whereBetween('price_range', [$request->search_cars_price_from_val, $request->search_cars_price_to_val]);
        }
        if (
            $request->has('search_cars_year_from_val') && $request->filled('search_cars_year_from_val') &&
            $request->has('search_cars_year_to_val') && $request->filled('search_cars_year_to_val')
        ) {
            $cars->whereBetween('car_year', [$request->search_cars_year_from_val, $request->search_cars_year_to_val]);
        }
        if (
            $request->has('search_cars_mileage_from_val') && $request->filled('search_cars_mileage_from_val') &&
            $request->has('search_cars_mileage_to_val') && $request->filled('search_cars_mileage_to_val')
        ) {
            $cars->whereBetween('mileage', [$request->search_cars_mileage_from_val, $request->search_cars_mileage_to_val]);
        }
        if (
            $request->has('search_cars_capacity_from_val') && $request->filled('search_cars_capacity_from_val') &&
            $request->has('search_cars_capacity_to_val') && $request->filled('search_cars_capacity_to_val')
        ) {
            $cars->whereBetween('engine_capacity', [$request->search_cars_capacity_from_val, $request->search_cars_capacity_to_val]);
        }
        if (isset($search_cars_filter_registered_val) && !empty($search_cars_filter_registered_val))
        {
            $city_ids_by_province = City::where('province_id', $search_cars_filter_registered_val)
                ->pluck('id')->all();
            $cars->whereIn('car_registeration_city', $city_ids_by_province);
        }
        if (isset($search_cars_filter_transmission_val) && !empty($search_cars_filter_transmission_val))  {
            $cars->where('transmission', $search_cars_filter_transmission_val);
        }
        if (isset($search_cars_filter_engine_type_val) && !empty($search_cars_filter_engine_type_val))  {
            $cars->where('engine_type', $search_cars_filter_engine_type_val);
        }
        if (isset($search_cars_filter_assembly_val) && !empty($search_cars_filter_assembly_val))  {
            $cars->where('assembly', $search_cars_filter_assembly_val);
        }
        if (isset($search_cars_filter_keword_val) && !empty($search_cars_filter_keword_val))  {
            //$cars->where('description', 'LIKE', '%' . $search_cars_filter_keword_val . '%');
            $cars = $cars->with('brand', 'model')->whereRelation('brand', 'name', 'LIKE' ,'%' . $search_cars_filter_keword_val . '%')
            ->orWhereRelation('model', 'name', 'LIKE', '%' . $search_cars_filter_keword_val . '%');
        }
        if (isset($search_cars_filter_vehicle_val) && !empty($search_cars_filter_vehicle_val)) {
            $model_id_by_type = Models::where('type_id', $search_cars_filter_vehicle_val)
                ->pluck('id')->all();
            $cars->whereIn('model_id', $model_id_by_type);
        }
        if (isset($search_cars_filter_verified_val) && !empty($search_cars_filter_verified_val)) {
            $model_id_by_type = Showroom::where('verified', 'YES')
                ->pluck('id')->all();
            $cars->whereIn('showroom_id', $model_id_by_type);
        }
        if (isset($search_cars_filter_color_val) && !empty($search_cars_filter_color_val)) {
            $cars->where('exterior_color', $search_cars_filter_color_val);
        }
        if (isset($search_cars_filter_showroom_id) && !empty($search_cars_filter_showroom_id)) {
            $cars->where('showroom_id', $search_cars_filter_showroom_id);
        }
        if (isset($search_cars_filter_condition) && !empty($search_cars_filter_condition)) {
            $cars->where('condition', $search_cars_filter_condition);
        }
        #endregion Filter Section

        if (isset($select_car_orders_val) && !empty($select_car_orders_val))  {
            $cars->orderBy($select_car_orders_val[0], $select_car_orders_val[1]);
        }

        $pagination_count = $this->GetPaginationCount($cars);

        $pageCounter = 1;
        if($view_update == "false")
        {
            $pageCounter = $page;
            $cars = $cars->offset($this->common->pagination_limit*($pageCounter-1))->limit($this->common->pagination_limit)->get();
        }
        else
        {
            $cars = $cars->limit($this->common->pagination_limit)->get();
        }

        return array(
            'cars' => $cars,
            'pagination_count' => $pagination_count,
            'pageCounter' => $pageCounter,
        );
    }

    public function SearchCarsByViewType(Request $request)
    {
        $response = $this->Filter($request);
        $data['cars'] = $response['cars'];

        $search_view_selected = $request->input('search_view_selected');
        $view_name = ($search_view_selected == 'list') ? 'front.search-cars.search-cars-list' : 'front.search-cars.search-cars-grid';

        $search_car_view = View::make($view_name)->with($data);
        $search_car_view = $search_car_view->render();


        return response()->json([
            'html'   =>  $search_car_view,
            'pagination_count' => $response['pagination_count'],
            'pageCounter' => $response['pageCounter'],
        ]);
    }

    function NearbyShowroom($request, $collection)
    {
        $search_cars_filter_latitude_val = $request->input('search_cars_filter_latitude_val');
        $search_cars_filter_longitude_val = $request->input('search_cars_filter_longitude_val');
        if (
            isset($search_cars_filter_latitude_val) && !empty($search_cars_filter_latitude_val) &&
            isset($search_cars_filter_longitude_val) && !empty($search_cars_filter_longitude_val)
        )
        {
            $distance = $this->common->GetSetting('nearby_showroom_radius');
            foreach($collection as $key => $value)
            {
                $calculatedDistance = $this->common->CalculateDistanceLocal($search_cars_filter_latitude_val, $search_cars_filter_longitude_val, $value->user->latitude, $value->user->longitude);
                if($calculatedDistance > $distance)
                {
                    $collection->forget($key);
                }
            }

        }
        return $collection;

    }

    function FilterShowrooms ($request)
    {
        $search_cars_filter_city_val = $request->input('search_cars_filter_city_val');
        $search_cars_filter_provience_val = $request->input('search_cars_filter_provience_val');
        $search_cars_filter_brand_val = $request->input('search_cars_filter_brand_val');
        $search_cars_filter_model_val = $request->input('search_cars_filter_model_val');
        $search_cars_filter_version_val = $request->input('search_cars_filter_version_val');
        $search_cars_filter_keword_val = $request->input('search_cars_filter_keword_val');
        $search_cars_filter_verified_val = $request->input('search_cars_filter_verified_val');

        $showrooms = Showroom::select('*');

        if (isset($search_cars_filter_city_val) && !empty($search_cars_filter_city_val)) {
            $showrooms = $showrooms->where('city_id', $search_cars_filter_city_val);
        }
        if (isset($search_cars_filter_provience_val) && !empty($search_cars_filter_provience_val)) {
            $city_ids_by_province = City::where('province_id', $search_cars_filter_provience_val)
                ->pluck('id')->all();
            $showrooms = $showrooms->whereIn('city_id', $city_ids_by_province);
        }
        if (isset($search_cars_filter_brand_val) && !empty($search_cars_filter_brand_val)) {
            $cars = Car::where('brand_id', $search_cars_filter_brand_val)->pluck('showroom_id')->all();
            $showrooms = $showrooms->whereIn('id', $cars);
        }
        if (isset($search_cars_filter_model_val) && !empty($search_cars_filter_model_val)) {
            $cars = Car::where('model_id', $search_cars_filter_model_val)->pluck('showroom_id')->all();
            $showrooms = $showrooms->whereIn('id', $cars);
        }
        if (isset($search_cars_filter_version_val) && !empty($search_cars_filter_version_val)) {
            $cars = Car::where('version_id', $search_cars_filter_version_val)->pluck('showroom_id')->all();
            $showrooms = $showrooms->whereIn('id', $cars);
        }
        if (isset($search_cars_filter_keword_val) && !empty($search_cars_filter_keword_val)) {
            // $cars = Car::where('description', 'LIKE', '%' . $search_cars_filter_keword_val . '%')->pluck('showroom_id')->all();
            // $showrooms = $showrooms->whereIn('id', $cars);
            $showrooms = $showrooms->where('name', 'LIKE', '%' . $search_cars_filter_keword_val . '%');
        }
        if (isset($search_cars_filter_verified_val) && !empty($search_cars_filter_verified_val)) {
            $showrooms = $showrooms->where('verified', 'YES');
        }

        return $showrooms->pluck('id')->all();

    }

    public function SearchShowroomsByViewType(Request $request)
    {
        $filtered_showroom_ids = $this->FilterShowrooms($request);
        $showrooms = Showroom::whereIn('id', $filtered_showroom_ids)->/*paginate(21)*/get();
        $showrooms = $this->NearbyShowroom($request, $showrooms);
        $response = $this->PaginationForShowroom($request, $showrooms);
        $data['showrooms'] = $response['showrooms'];
        $search_view_selected = $request->input('search_view_selected');
        $view_name = ($search_view_selected == 'list') ? 'front.search-showrooms.search-showrooms-list' : 'front.search-showrooms.search-showrooms-grid';

        $search_showrooms_view = View::make($view_name)->with($data);
        $search_showrooms_view = $search_showrooms_view->render();


        return response()->json([
            'html'   =>  $search_showrooms_view,
            'pagination_count' => $response['pagination_count'],
            'pageCounter' => $response['pageCounter'],
        ]);
    }

    public function ShowroomCarsByViewType(Request $request)
    {
        $search_cars_filter_keword_val = $request->input('search_cars_filter_keword_val');
        $select_car_orders_val = $request->input('select_car_orders_val') ? $request->input('select_car_orders_val') : 'price_range|desc';
        $select_car_orders_val = explode("|", $select_car_orders_val);


        $cars = Car::where('showroom_id', $request->showroom_id)->where('status', 'APPROVED');

        if (isset($search_cars_filter_keword_val) && !empty($search_cars_filter_keword_val)) {
            //$cars->where('description', 'LIKE', '%' . $search_cars_filter_keword_val . '%');
            $cars = $cars->with('brand', 'model')->whereRelation('brand', 'name', 'LIKE', '%' . $search_cars_filter_keword_val . '%')
                ->orWhereRelation('model', 'name', 'LIKE', '%' . $search_cars_filter_keword_val . '%')->where('showroom_id', $request->showroom_id);
        }

        $cars = $cars->get();

        if (isset($select_car_orders_val) && !empty($select_car_orders_val)) {
            if ($select_car_orders_val[0] == "rating") {
                if ($select_car_orders_val[1] == "asc") {
                    $cars = $cars->sortBy(function ($value) {
                        return $value->rating;
                    });
                } else {
                    $cars = $cars->sortByDesc(function ($value) {
                        return $value->rating;
                    });
                }
            } else {
                if ($select_car_orders_val[1] == "asc") {
                    $cars = $cars->sortBy($select_car_orders_val[0]);
                } else {
                    $cars = $cars->sortByDesc($select_car_orders_val[0]);
                }
            }
        }

        $data['cars'] = $cars;

        $search_showrooms_view = View::make('front.showroom-detail.showroom-detail-cars')->with($data);
        $search_showrooms_view = $search_showrooms_view->render();


        return response()->json([
            'html'   =>  $search_showrooms_view,
        ]);
    }

    function PaginationForShowroom($request, $collection)
    {
        $page = $request->input('page');
        $view_update = $request->input('view_update');
        $select_car_orders_val = $request->input('select_car_orders_val') ? $request->input('select_car_orders_val') : 'rating|desc';
        $select_car_orders_val = explode("|", $select_car_orders_val);

        $showroom = Showroom::whereIn('id', $collection->pluck('id'));



        $pagination_count = $this->GetPaginationCount($showroom);

        $pageCounter = 1;
        if ($view_update == "false") {
            $pageCounter = $page;
            $showroom = $showroom->offset($this->common->pagination_limit * ($pageCounter - 1))->limit($this->common->pagination_limit)->get();
        } else {
            $showroom = $showroom->limit($this->common->pagination_limit)->get();
        }

        if (isset($select_car_orders_val) && !empty($select_car_orders_val)) {
            if ($select_car_orders_val[0] == "sold") {
                if ($select_car_orders_val[1] == "asc") {
                    $showroom = $showroom->sortBy(function ($value) {
                        return $value->GetNumberSoldCars();
                    });
                } else {
                    $showroom = $showroom->sortByDesc(function ($value) {
                        return $value->GetNumberSoldCars();
                    });
                }
            } else {
                if ($select_car_orders_val[1] == "asc") {
                    $showroom = $showroom->sortBy($select_car_orders_val[0]);
                } else {
                    $showroom = $showroom->sortByDesc($select_car_orders_val[0]);
                }
            }
        }

        return array(
            'showrooms' => $showroom,
            'pagination_count' => $pagination_count,
            'pageCounter' => $pageCounter,
        );
    }

    function GetPaginationCount($collection)
    {
        return ceil($collection->count()/$this->common->pagination_limit);
    }
}
