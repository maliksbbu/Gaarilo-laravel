@extends('front.layout.main')
@section('content')

<div id="carforsale">

    <section class="section-space" id="filtersec">
        <div class="container-xl">
            <h2 class="page-heading mb-5">Cars For Sale In Pakistan</h2>

            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="filterhead d-flex align-items-center">
                        <h2 class="m-0">Search cars by</h2>
                        <img src="{{URL::asset('front/images/filticon.png')}}" class="ms-auto">
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#keywords">
                            <h3 class="m-0">Search by keywords</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="keywords">
                            <div class="innerbody">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="e.g. Honda">
                                    <div class="input-group-text" id="btnGroupAddon2">GO</div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#cityexpand">
                            <h3 class="m-0">CITY</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="cityexpand">
                            <div class="innerbody">
                                @foreach($data['cities'] as $city)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{strtoupper($city->name)}}
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{car_in_city($city->id)}}
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Cities</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand3">
                            <h3 class="m-0">PROVINCE</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="expand3">
                            <div class="innerbody">
                                @foreach($data['proviences'] as $provience)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$provience->name}}
                                        <input type="checkbox" checked="checked">
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

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand4">
                            <h3 class="m-0">MAKE</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="expand4">
                            <div class="innerbody">
                                @foreach($data['brands'] as $brand)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$brand->name}}
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{car_make_count($brand->id)}}
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Brands</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand5">
                            <h3 class="m-0">MODEL</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="expand5">
                            <div class="innerbody">
                                @foreach($data['models'] as $model)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$model->name}}
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{car_model_count($model->id)}}
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Models</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand6">
                            <h3 class="m-0">VERSION</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="expand6">
                            <div class="innerbody">
                                @foreach($data['versions'] as $version)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$version->name}}
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{car_version_count($version->id)}}
                                    </div>
                                </div>
                                @endforeach

                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Versions</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand7">
                            <h3 class="m-0">PRICE RANGE</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody show" id="expand7">
                            <div class="innerbody">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="From">
                                    <input type="text" class="form-control" placeholder="To">
                                    <div class="input-group-text" id="btnGroupAddon2">GO</div>
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
                                    <input type="text" class="form-control" placeholder="From">
                                    <input type="text" class="form-control" placeholder="To">
                                    <div class="input-group-text" id="btnGroupAddon2">GO</div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand9">
                            <h3 class="m-0">MILEAGE (KM)</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody " id="expand9">
                            <div class="innerbody">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="From">
                                    <input type="text" class="form-control" placeholder="To">
                                    <div class="input-group-text" id="btnGroupAddon2">GO</div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand10">
                            <h3 class="m-0">REGISTERED</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody " id="expand10">
                            <div class="innerbody">
                                @foreach($data['proviences'] as $provience)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$provience->name}}
                                        <input type="checkbox" checked="checked">
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
                                        GaariLo Verified
                                        <input type="checkbox" checked="checked">
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
                                        Manual
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{$data['manual_cars']}}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        Automatic
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{$data['automatic_cars']}}
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
                                @foreach($data['colors'] as $color)
                                <div class="colors">
                                    <i class="fa fa-circle me-2" style="color: {{$color->hexa}}"></i>
                                    <label>{{$color->name}}</label>
                                </div>
                                @endforeach
                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Choices</a>
                                </div>

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
                                @foreach($data['engine_types'] as $engine_type)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                       {{$engine_type}}
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{car_engine_type_count($engine_type)}}
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup">
                                    <a>More Choices</a>
                                </div>



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
                                    <input type="text" class="form-control" placeholder="From">
                                    <input type="text" class="form-control" placeholder="To">
                                    <div class="input-group-text" id="btnGroupAddon2">GO</div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="expandbox">
                        <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand17">
                            <h3 class="m-0">ASSEMBLY</h3>
                            <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                        </div>
                        <div class="collapse exbody " id="expand17">
                            <div class="innerbody">
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        Local
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{$data['local_assembly']}}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        Imported
                                        <input type="checkbox" checked="checked">
                                        <span class="checkmark"></span>
                                    </label>
                                    <div class="salecounter ms-auto">
                                        {{$data['importad_assembly']}}
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
                                @foreach($data['vehicle_types'] as $vehicle_type)
                                <div class="d-flex align-items-center mb-2">
                                    <label class="custom-checkbox">
                                        {{$vehicle_type->name}}
                                        <input type="checkbox" checked="checked">
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
                <div class="col-xl-9 col-lg-8 col-md-7">


                    <div class="sort-header mb-4">
                        <div class="d-flex align-items-center">
                            <label class="me-3">Sort By</label>
                            <div class="postad-select w-50">
                                <select>
                                    <option value="AL" selected disabled>Updated Date: Recent First</option>
                                    <option value="AL">Update Date: Oldest First</option>
                                    <option value="WY">Price: Low to High</option>
                                    <option value="WY">Price: High to Low</option>
                                    <option value="WY">Modal Year: Latest First</option>
                                    <option value="WY">Modal Year: Oldest First</option>
                                    <option value="WY">Mileage : Low To High</option>
                                    <option value="WY">Mileage: High To Low</option>
                                    <option value="WY">Reviews & Ratings: High to Low</option>
                                    <option value="WY">Reviews & Ratings: Low to High</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group ms-auto sort-btngroup" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio1"> <i class="fa fa-list me-2"></i>
                                List</label>
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="btnradio2"> <i class="fa fa-th-large me-2"></i>
                                Grid</label>
                        </div>
                    </div>


                    <div class="row mb-5">
                        @foreach ($cars as $car)
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="detail-box">
                                <div class="db-top">
                                    <img src="{{$car->image}}" class="w-100" style="rotate:{{$car->rotation}}deg;">
                                    <div class="imgcounter">
                                        <span class="me-2">{{$car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count() }}</span>
                                        <img src="{{URL::asset('front/images/images-icon.png')}}">
                                    </div>
                                    <div class="dbprice">
                                        <h5 class="m-0">PKR. 50,00000</h5>
                                        <p class="m-0">Showroom offer</p>
                                    </div>
                                </div>
                                <div class="db-body">
                                    <h5 class="m-0 mb-3">
                                        Toyota Harrier 2014
                                    </h5>
                                    <div class="d-flex align-items-center mb-3">
                                        <h3 class="m-0">PKR. {{$car->price_range}}</h3>
                                        <img src="@if($car->showroom != null){{$car->showroom->logo}} @endif" class="ms-auto">
                                    </div>


                                    <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
                                        <div><span class="me-3">2014</span> |</div>
                                        <div><span class="mx-3 ps-2"> 60,000 km </span> </div>
                                        <div>|<span class="ms-3">Hybrid</span></div>
                                    </div>
                                    <div class="m-0 mb-3 sepreator-text d-flex flex-wrap">
                                        <div></div>
                                        <div><span class="me-3">2500cc </span> |</div>
                                        <div><span class="ms-3">Automatic</span></div>
                                    </div>
                                    <div class="mb-3 text-muted mb-4">Last Updated: 11/13/2022</div>

                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-phone-alt me-2"></i>
                                        Show Phone No.
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach


                    </div>

                    <div class="d-flex align-items-center justify-content-center">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">

                                <li class="page-item"><a class="page-link active" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item"><a class="page-link" href="#">....</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next ></a></li>
                                <li class="page-item"><a class="page-link" href="#">Last >></a></li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>


        </div>
    </section>

    <section id="getapp" class="section-space">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-7 col-md-8 mb-3 mb-lg-0">
                    <div class="get-left">
                        <div class="getinner">
                            <h4 class="get-heading mb-3">Get <span class="clr-primary">GaariLo</span> APP</h4>
                            <h5 class="mb-2">Buy & Sell cars from anywhere in Pakistan</h5>
                            <h6 class="mb-4">You can Download Gaarilo apps by Scanning below QR Code</h6> <img src="{{URL::asset('front/images/qr.png')}}" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-4 d-lg-block d-none"> <img src="{{URL::asset('front/images/appimg.png')}}" class="img-fluid"> </div>
            </div>
        </div>
    </section>



</div>

@endsection

@section('scripts')
<script>
    /*
    Reference: http://jsfiddle.net/BB3JK/47/
    */

    $('select').each(function() {
        var $this = $(this),
            numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        var $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());

        var $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (var i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
            //if ($this.children('option').eq(i).is(':selected')){
            //  $('li[rel="' + $this.children('option').eq(i).val() + '"]').addClass('is-selected')
            //}
        }

        var $listItems = $list.children('li');

        $styledSelect.click(function(e) {
            e.stopPropagation();
            $('div.select-styled.active').not(this).each(function() {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $listItems.click(function(e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            //console.log($this.val());
        });

        $(document).click(function() {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
</script>

<script>
    $(document).ready(function() {
        $("#btnradio1").change(function() {
            if ($("#btnradio1").is(":checked")) {
                var url = window.location.href;
                url = url.replace("search-grid", "search-list");
                console.log(url);
                RedirectToURL(url);
            }
        });

        $("#btnradio2").change(function() {
            if ($("#btnradio2").is(":checked")) {
                var url = window.location.href;
                url = url.replace("search-list", "search-grid");
                RedirectToURL(url);
            }
        });
    });
</script>
@endsection
