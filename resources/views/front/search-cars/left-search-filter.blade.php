<div class="col-xl-3 col-lg-4 col-md-5">
    <div class="filterhead d-flex align-items-center">
        <h2 class="m-0">Search cars by</h2>
        <img src="{{URL::asset('front/images/filticon.png')}}" class="ms-auto">
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#filterExpand">
            <h3 class="m-0">SEARCH FILTERS</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="filterExpand">
            <div class="innerbody" id="filterToShow">

            </div>
        </div>
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#keywords">
            <h3 class="m-0">Search by keywords</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="keywords">
            <div class="innerbody">
                <div class="input-group">
                    <input type="text" name="filter_keyword" id="filter_keyword" class="form-control" placeholder="e.g. Honda">
                    <input type="hidden" name="search_cars_filter_keword" id="search_cars_filter_keword" class="form-control" placeholder="e.g. Honda">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_key');" style="cursor: pointer;">GO</div>

                </div>
            </div>
        </div>
    </div>

    <div class="expandbox" id="city_expandbox">
    @include('front.search-cars.filters-components.city-filter',[
        'cities' => $cities,
        'for' => 'cars',
    ])
    </div>

    <div class="expandbox" id="province-expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand3">
            <h3 class="m-0">PROVINCE</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="expand3">
            <div class="innerbody">
                @foreach($proviences as $provience)
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        {{strtoupper($provience->name)}}
                        <input type="checkbox" name="filter_provience" value="{{$provience->id}}"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{car_in_provience($provience->id)}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="expandbox" id="make_expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand4">
            <h3 class="m-0">MAKE</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="expand4">
            <div class="innerbody">
                @foreach($brands->slice(0, 5) as $brand)
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        {{strtoupper($brand->name)}}
                        <input type="checkbox" name="filter_brand" id="filter_brand_{{$brand->id}}" value="{{$brand->id}}" />
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{car_make_count($brand->id)}}
                    </div>
                </div>
                @endforeach

                @if($brands->count() > 5)
                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup-brands">
                    <a>More Brands</a>
                </div>

                @include('front.search-cars.make-more-popup', [
                    'brands' => $brands,
                    'div_id' => 'more-detail-popup-brands'
                ])
                @endif
            </div>
        </div>
    </div>

    <div class="expandbox" id="model_expandbox">
        @include('front.search-cars.filters-components.model-filter', [
            'models' => $models,
        ])
    </div>

    <div class="expandbox" id="version_expandbox">
        @include('front.search-cars.filters-components.version-filter', [
            'versions' => $versions,
        ])
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand7">
            <h3 class="m-0">PRICE RANGE</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="expand7">
            <div class="innerbody">
                <div class="input-group">
                    <input type="number" min="0" class="form-control" placeholder="From" name="" id="price_from">
                    <input type="number" min="0" class="form-control" placeholder="To" name="" id="price_to">
                    <input type="hidden" class="form-control" placeholder="From" name="search_cars_price_from" id="search_cars_price_from">
                    <input type="hidden" class="form-control" placeholder="To" name="search_cars_price_to" id="search_cars_price_to">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_price');" style="cursor: pointer;">GO</div>
                </div>
            </div>
        </div>
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand8">
            <h3 class="m-0">YEAR</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="expand8">
            <div class="innerbody">
                <div class="input-group">
                    <input type="number" min="0" class="form-control" placeholder="From" name="" id="year_from">
                    <input type="number" min="0" class="form-control" placeholder="To" name="" id="year_to">
                    <input type="hidden" class="form-control" placeholder="From" name="search_cars_year_from" id="search_cars_year_from">
                    <input type="hidden" class="form-control" placeholder="To" name="search_cars_year_to" id="search_cars_year_to">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_year');" style="cursor: pointer;">GO</div>

                </div>
            </div>
        </div>
    </div>
    <div class="expandbox" >
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand9" >
            <h3 class="m-0">MILEAGE (KM)</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand9" >
            <div class="innerbody" >
                <div class="input-group">
                    <input type="number" min="0" class="form-control" placeholder="From" name="" id="mileage_from">
                    <input type="number" min="0" class="form-control" placeholder="To" name="" id="mileage_to">
                    <input type="hidden" class="form-control" placeholder="From" name="search_cars_mileage_from" id="search_cars_mileage_from">
                    <input type="hidden" class="form-control" placeholder="To" name="search_cars_mileage_to" id="search_cars_mileage_to">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_mileage');" style="cursor: pointer;">GO</div>

                </div>
            </div>
        </div>
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand10">
            <h3 class="m-0">REGISTERED</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand10">
            <div class="innerbody">
                @foreach($proviences as $provience)
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        {{strtoupper($provience->name)}}
                        <input type="checkbox" name="filter_registered" value="{{$provience->id}}"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{car_registerd_provience($provience->id)}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand11">
            <h3 class="m-0">TRUSTED CARS</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand11">
            <div class="innerbody">
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        GAARILO VERIFIED
                        <input type="checkbox" name="filter_verified" id="verified" value="YES" />
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{VerfiedCars()}}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand12">
            <h3 class="m-0">TRANSMISSION</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand12">
            <div class="innerbody">
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                         MANUAL
                        <input type="checkbox" name="filter_transmission" value="Manual"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{$manual_cars}}
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        AUTOMIATIC
                        <input type="checkbox" name="filter_transmission" value="Automatic"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{$automatic_cars}}
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand13">
            <h3 class="m-0">COLOR</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand13">
            <div class="innerbody">
                @foreach($colors as $color)
                {{-- <div class="colors">
                    <i class="fa fa-circle me-2" style="color: {{$color->hexa}}"></i>
                    <label>{{strtoupper($color->name)}}</label>
                </div> --}}
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        <i class="fa fa-circle me-2" style="color: {{$color->hexa}}"></i>
                        <label>{{strtoupper($color->name)}}</label>
                        <input type="checkbox" name="filter_color" id="filter_color_{{$color->id}}" value="{{$color->id}}" />
                        <span class="checkmark"></span>
                    </label>
                    {{-- <div class="salecounter ms-auto">
                        {{car_engine_type_count($engine_type)}}
                    </div> --}}
                </div>
                @endforeach
                {{-- <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                    <a>More Choices</a>
                </div> --}}

            </div>
        </div>
    </div>
    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand14">
            <h3 class="m-0">ENGINE TYPE</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand14">
            <div class="innerbody">
                @foreach($engine_types as $engine_type)
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        {{strtoupper($engine_type)}}
                        <input type="checkbox" name="filter_engine" id="filter_engine_{{$engine_type}}" value="{{$engine_type}}"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{car_engine_type_count($engine_type)}}
                    </div>
                </div>
                @endforeach
                {{-- <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup-engine-type">
                    <a>More Choices</a>
                </div>

                @include('front.search-cars.engine-type-more-popup', [
                    'engine_types' => $engine_types,
                    'div_id' => 'more-detail-popup-engine-type'
                ]) --}}

            </div>
        </div>
    </div>
    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand16">
            <h3 class="m-0">ENGINE CAPACITY (CC)</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand16">
            <div class="innerbody">
                <div class="input-group">
                    <input type="number" min="0" class="form-control" placeholder="From" name="" id="capacity_from">
                    <input type="number" min="0" class="form-control" placeholder="To" name="" id="capacity_to">
                    <input type="hidden" class="form-control" placeholder="From" name="search_cars_capacity_from" id="search_cars_capacity_from">
                    <input type="hidden" class="form-control" placeholder="To" name="search_cars_capacity_to" id="search_cars_capacity_to">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_capacity');" style="cursor: pointer;">GO</div>

                </div>
            </div>
        </div>
    </div>

    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand17">
            <h3 class="m-0">ASSEMBLY</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand17">
            <div class="innerbody">
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        LOCAL
                        <input type="checkbox" name="filter_assembly" value="Local"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{$local_assembly}}
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                         IMPORTED
                        <input type="checkbox" name="filter_assembly" value="Imported"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{$importad_assembly}}
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="expandbox">
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand18">
            <h3 class="m-0">BODY TYPE</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody" id="expand18">
            <div class="innerbody">
                @foreach($vehicle_types as $vehicle_type)
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        {{strtoupper($vehicle_type->name)}}
                        <input type="checkbox" name="filter_vehicle" value="{{$vehicle_type->id}}" id="filter_vehicle_{{$vehicle_type->id}}" />
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{car_type_count($vehicle_type->id)}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

