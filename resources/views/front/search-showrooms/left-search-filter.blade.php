<div class="col-xl-3 col-lg-4 col-md-5">
    <div class="filterhead d-flex align-items-center">
        <h2 class="m-0">Search Showrooms by</h2>
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
                    <input type="text" name="filter_keyword" id="filter_keyword" class="form-control" placeholder="e.g. Go Showroom">
                    <input type="hidden" name="search_cars_filter_keword" id="search_cars_filter_keword" class="form-control" placeholder="e.g. Honda">
                    <div class="input-group-text" id="btnGroupAddon2" onclick="ApplyAlphaNumericFilters('fil_key');" style="cursor: pointer;">GO</div>

                </div>
            </div>
        </div>
    </div>

    <div class="expandbox" id="city_expandbox">
    @include('front.search-cars.filters-components.city-filter',[
        'cities' => $cities,
        'for' => 'showroom',
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
                        {{$provience->name}}
                        <input type="checkbox" name="filter_provience" value="{{$provience->id}}"/>
                        <span class="checkmark"></span>
                    </label>
                    <div class="salecounter ms-auto">
                        {{showroom_in_province($provience->id)}}
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
                        {{$brand->name}}
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
        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand11">
            <h3 class="m-0">TRUSTED CARS</h3>
            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
        </div>
        <div class="collapse exbody show" id="expand11">
            <div class="innerbody">
                <div class="d-flex align-items-center mb-2">
                    <label class="custom-checkbox">
                        GaariLo Verified
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


    <input type="hidden" name="latitude" id="latitude" value="" />
    <input type="hidden" name="longitude" id="longitude" value="" />


</div>
