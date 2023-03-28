@extends('front.layout.main')
@section('content')

<?php use App\Http\Controllers\CommonController; ?>
<!-- Modal -->
<div class="modal fade" id="carinfo-popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <div class="arrow-pointer active" id="year-pointer">
                            <div class="pointer-text">
                                1. Model Year
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="arrow-pointer" id="make-pointer">
                            <div class="pointer-text">
                                2. Make
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="arrow-pointer" id="model-pointer">
                            <div class="pointer-text">
                                3. Model
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="arrow-pointer" id="version-pointer">
                            <div class="pointer-text">
                                4. Version <small>(Optional)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="infobox" id="infobox-year">
                            @foreach ((new CommonController())->GetYearsList() as $year)
                            <a href="javascript:SelectModalYear({{$year}})" id="year{{$year}}">
                                <div class="modalbox">
                                    {{$year}}
                                    <i class="clr-primary fa fa-chevron-right"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="infobox" id="infobox-brand">
                            <h5>Popular</h5>
                            @foreach ($data['brands'] as $brand)
                            @if($brand->mark_popular == "YES")
                            <a href="javascript:SelectModalMake({{$brand->id}})" id="make{{$brand->id}}"
                                data-make_name="{{$brand->name}}">
                                <div class="modalbox">
                                    {{$brand->name}}
                                    <i class="clr-primary fa fa-chevron-right"></i>
                                </div>
                            </a>
                            @endif
                            @endforeach
                            <br>

                            <h4 class="other">Others</h4>
                            @foreach ($data['brands'] as $brand)
                            @if($brand->mark_popular == "NO")
                            <a href="javascript:SelectModalMake({{$brand->id}})" id="make{{$brand->id}}"
                                data-make_name="{{$brand->name}}">
                                <div class="modalbox">
                                    {{$brand->name}}
                                    <i class="clr-primary fa fa-chevron-right"></i>
                                </div>
                            </a>
                            @endif
                            @endforeach

                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="infobox" id="infobox-model">


                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="infobox" id="infobox-version">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="CloseCarInfoDialog()" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<section id="postbox" class="section-space pb-4">
    <div class="container-xl">
        <div class="pbox">
            <h4 class="mb-3">Post an Ad</h4>
            <p class="mb-3">Post an ad in just 3 simple steps</p>
            <div class="row mx-5">
                <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="pcirc"> <img src="{{URL::asset('front/images/pcar.png')}}"> </div>
                        </div>
                        <div class="ptxt"> Enter your car
                            <br> information
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="pcirc"> <img src="{{URL::asset('front/images/up.png')}}"> </div>
                        </div>
                        <div class="ptxt"> Upload Photos </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="pcirc"> <img src="{{URL::asset('front/images/ci.png')}}"> </div>
                        </div>
                        <div class="ptxt"> Put your contact
                            <br> Information (Optional)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<form id="post_ad_form" action="{{route('post-ad')}}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <section class="section-space pb-0">
        <div class="container-xl">
            <div id="carinfo">
                <h2>Car Information</h2>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>City<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select id="city" name="city">
                                <option value="0">Select City</option>
                                @foreach ($data['cities'] as $city)
                                <option value="{{$city->id}}">{{strtoupper($city->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Car Info<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6 col-11">
                        <input class="form-control cinfo-field" type="text" id="make_model_version"
                            placeholder="Make/Model/Version" readonly onclick="OpenCarInfoDialog()">
                    </div>
                    <div class="col-1" id="tick_icon" style="display: none"> <img
                            src="{{URL::asset('front/images/green-tik.png')}}"> </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Registered In<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select id="car_registeration_city" name="car_registeration_city">
                                <option value="">Select City</option>
                                <option value="0">Un-Registered</option>
                                @foreach ($data['cities'] as $city)
                                <option value="{{$city->id}}">{{strtoupper($city->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Condition<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select class="remove_pointer_events" id="condition" name="condition">
                                <option value="USED">Used</option>
                                <option value="NEW">Brand New</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Exterior Color<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select id="exterior_color" name="exterior_color">
                                <option value="0">Select Color</option>
                                @foreach ($data['colors'] as $color)
                                <option value="{{$color->id}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Mileage<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6 ">
                        <div class="input-group"> <span class="input-group-text inputlbl" id="basic-addon1">KM</span>
                            <input type="number" class="form-control border-tl-0 border-bl-0" placeholder="Mileage"
                                aria-label="Username" aria-describedby="basic-addon1" id="mileage" name="mileage"
                                min="1">
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Price<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group"> <span class="input-group-text inputlbl" id="basic-addon1">PKR</span>
                            <input type="number" class="form-control border-tl-0 border-bl-0 " placeholder="Price"
                                aria-label="Username" aria-describedby="basic-addon1" min="100000" id="price"
                                name="price">
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label class="mt-3">Description<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" rows="5" placeholder="1000 Characters Maximum" id="description"
                            name="description" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Engine Type<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select class="remove_pointer_events" id="engine_type" name="engine_type">
                                @foreach ($data['engineTypes'] as $engine)
                                <option value="{{$engine}}">{{$engine}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Transmission<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select class="remove_pointer_events" id="transmission" name="transmission">
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                                <option value="Triptonic">Triptonic</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Engine Capacity<span class="required">*</span> (cc)</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control" placeholder="Select Capacity" id="engine_capacity"
                            name="engine_capacity">
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Drive type</label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select class="remove_pointer_events" id="drive_type" name="drive_type">
                                @foreach ($data['driveTypes'] as $drive)
                                <option value="{{$drive}}">{{$drive}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Assembly</label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select class="remove_pointer_events" id="assembly" name="assembly">
                                <option value="Local">Local</option>
                                <option value="Imported">Imported</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-end text-start mb-3 mb-md-0">
                        <label>Registered Year</label>
                    </div>
                    <div class="col-md-6">
                        <div class="postad-select">
                            <select id="car_registeration_year" name="car_registeration_year">
                                <option value="">Select Year</option>
                                <option value="0">Un-Registered</option>
                                @foreach ((new CommonController())->GetYearsList() as $year)
                                <option value="{{$year}}">{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col-md-2 text-md-end text-start mb-3 mb-md-0">
                        <label>Features</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            @foreach ($data['features'] as $feature)
                            <div class="col-xl-4 col-md-6">
                                <label class="custom-checkbox mb-4 me-5"> {{$feature->name}}
                                    <input type="checkbox" id="feature" name="feature[]" value="{{$feature->id}}"> <span
                                        class="checkmark"></span> </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space pb-0">
        <div class="container-xl">
            <div id="uploadphotos">
                <h2>Upload Photos</h2>
                <div class="upload-area mb-4" id="exterior_div_top">
                    <h5 class="mb-5">
                        Exterior <span class="required">*</span>
                    </h5>
                    <div class="d-flex flex-column flex-sm-row justify-content-md-center justify-content-start mb-5">
                        <div class="uploaded-img mb-2 mb-md-0"> <img src="{{URL::asset('front/images/img-icon.png')}}"> </div>
                        <div class="uploaded-action">
                            <label class="browsbtn btn btn-primary white-space-nowrap mb-2"> + Add Photos
                                <input type="file" id="exterior_image" name="exterior_image[]" size="60" multiple>
                            </label> <span>(Max limit 5 MB per image)</span>
                        </div>
                    </div>
                    <input type="hidden" value="0" id="exterior_image_main" name="exterior_image_main" />
                    <div class="d-flex align-items-center mx-md-5 justify-content-center" hidden>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_1" class="w-100 h-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(0);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 1);"><i
                                    class="fa fa-times"></i></a>
                            <a class="img-title">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(1)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_1" name="rotation_image_1" value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_2" class="w-100 h-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(1);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 2);"><i class="fa fa-times"></i></a>
                            <a class="img-title" style="display:none;">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(2)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_2" name="rotation_image_2" value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_3" class="w-100 h-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(2);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 3);"><i class="fa fa-times"></i></a>
                            <a class="img-title" style="display:none;">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(3)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_3" name="rotation_image_3" value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_4" class="w-100 h-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(3);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 4);"><i class="fa fa-times"></i></a>
                            <a class="img-title" style="display:none;">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(4)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_4" name="rotation_image_4" value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_5" class="w-100 h-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(4);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 5);"><i class="fa fa-times"></i></a>
                            <a class="img-title" style="display:none;">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(5)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_5" name="rotation_image_5" value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="exterior_image_6" class="w-100"
                                onclick="$('.img-title').hide();$(this).parent().children('.img-title').show();$('#exterior_image_main').val(5);">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 1, 6);"><i class="fa fa-times"></i></a>
                            <a class="img-title" style="display:none;">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImage(6)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_6" name="rotation_image_6" value="0">
                                </a>
                            </div>
                        </div>



                    </div>
                    <div class="uploadimg-list">
                        <div class="uploadimg-list_exterior"></div>
                        <div class="row ms-0 ms-lg-5 ">
                            <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Max
                                        upto 5 images allowed</span> </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Jpeg,
                                        jpg and png only</span> </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4">
                                    <span>Minimum of 1 image to be uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end" id="textend_exterior_image"> </div>
                </div>
                <div class="upload-area mb-4" id="interior_div_top">
                    <h5 class="mb-5">
                        Interior <span class="required">*</span>
                    </h5>
                    <div class="d-flex flex-column flex-sm-row justify-content-md-center justify-content-start mb-5">
                        <div class="uploaded-img mb-2 mb-md-0"> <img src="{{URL::asset('front/images/img-icon.png')}}"> </div>
                        <div class="uploaded-action">
                            <label class="browsbtn btn btn-primary white-space-nowrap mb-2"> + Add Photos
                                <input type="file" size="60" id="interior_image" name="interior_image[]" multiple>
                            </label> <span>(Max limit 5 MB per image)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mx-md-5  justify-content-center" hidden>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_1" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 1);"><i class="fa fa-times"></i></a>
                            <a class="img-title">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(1)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_1" name="rotation_image_interior_1"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_2" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 2);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(2)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_2" name="rotation_image_interior_2"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_3" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 3);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(3)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_3" name="rotation_image_interior_3"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_4" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 4);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(4)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_4" name="rotation_image_interior_4"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_5" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 5);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(5)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_5" name="rotation_image_interior_5"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="interior_image_6" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 2, 6);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageInterior(6)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_interior_6" name="rotation_image_interior_6"
                                        value="0">
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="uploadimg-list">
                        <div class="row ms-0 ms-lg-5 ">
                            <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Max
                                        upto 5 images allowed</span> </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Jpeg,
                                        jpg and png only</span> </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4">
                                    <span>Minimum of 1 image to be uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end" id="textend_interior_image"> </div>
                </div>
                <div class="upload-area mb-4" id="hotspot_div_top">
                    <h5 class="mb-5">
                        Hotspot
                    </h5>
                    <div class="d-flex flex-column flex-sm-row justify-content-md-center justify-content-start mb-5">
                        <div class="uploaded-img mb-2 mb-md-0"> <img src="{{URL::asset('front/images/img-icon.png')}}"> </div>
                        <div class="uploaded-action">
                            <label class="browsbtn btn btn-primary white-space-nowrap mb-2"> + Add Photos
                                <input type="file" size="60" id="hotspot_image" name="hotspot_image[]" multiple>
                            </label> <span>(Max limit 5 MB per image)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mx-md-5 justify-content-center" hidden>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_1" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 1);"><i class="fa fa-times"></i></a>
                            <a class="img-title">Main Image</a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(1)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_1" name="rotation_image_hotspot_1"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_2" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 2);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(2)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_2" name="rotation_image_hotspot_2"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_3" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 3);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(3)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_3" name="rotation_image_hotspot_3"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_4" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 4);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(4)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_4" name="rotation_image_hotspot_4"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_5" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 5);"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(5)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_5" name="rotation_image_hotspot_5"
                                        value="0">
                                </a>
                            </div>
                        </div>
                        <div class="uploaded-box mx-1" style="display: none">
                            <img src="{{URL::asset('front/images/carsm.png')}}" id="hotspot_image_6" class="w-100 h-100">
                            <a class="ubcross" onclick="ImageRemoveMultiple($(this).parent(), 3, 6);"
                                style="javascript:this.parent().hide();"><i class="fa fa-times"></i></a>
                            <div class="rotate-arrows">
                                <a href="javascript:RotateImageHotSpot(6)">
                                    <img src="{{URL::asset('front/images/rotate.png')}}" alt="">
                                    <input type="hidden" id="rotation_image_hotspot_6" name="rotation_image_hotspot_6"
                                        value="0">
                                </a>
                            </div>
                        </div>



                    </div>
                    <div class="uploadimg-list">
                        <div class="row ms-0 ms-lg-5 ">
                            <div class="col-xl-6 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Max
                                        upto 5 images allowed</span> </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center"> <img
                                        src="{{URL::asset('front/images/tick.png')}}" class="me-4"> <span>Jpeg,
                                        jpg and png only</span> </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end" id="textend_hotspot_image"> </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space pb-0">
        <div class="container-xl">
            <div id="uploadphotos">
                <h2>Upload Video <small>(Optional)</small></h2>
                <div class="upload-area mb-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-md-center justify-content-start">
                        <div class="me-3 text-center text-lg-start" id="video_content" style="display: none"> <video
                                style=" width: 230px;height: 125px;" id="preview-business_image"
                                src="{{URL::asset('front/images/vdoicon.png')}}" controls muted> </div>
                        <div class="mb-3 mb-lg-0 text-lg-start text-center uploaded-action">
                            <label class="browsbtn btn btn-primary white-space-nowrap mb-1"> + Add Video
                                <input type="file" size="60" id="video" name="video"
                                    accept="video/mp4,video/x-m4v,video/*"> </label> <span>(Max video size should be
                                25mb)</span>
                        </div>
                        <div class="me-3 uploaded-action" id="video_remove" style="display: none"> <a
                                class="browsbtn btn btn-danger mb-0 mb-lg-4" href="javascript:RemoveVideo()"> - Remove Video
                            </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space pb-5">
        <div class="container-xl">
            <div id="contact-info">
                <h2>Contact Information (Optional)</h2>
                <div class="row align-items-center mb-4">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label>Primary Number</label>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-7">
                        <input class="form-control priphone-field" type="text"
                            placeholder="{{(!empty(Session::get('user')))?Session::get('user')->phone:'-'}}">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label>Secondary Number <small class="w-100">(Optional)</small></label>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-7">
                        <input class="form-control" type="text" placeholder="Secondary Number" id="secondary_number"
                            name="secondary_number" value="+92">
                    </div>
                </div>
                <div class="d-flex justify-content-md-center justify-content-start mb-5 align-items-center">
                    <div class="d-flex align-items-center me-5"> <img src="{{URL::asset('front/images/whatsapp.png')}}"
                            class="me-3"> <span>Allow
                            Whatsapp Contact</span> </div>
                    <label class="switch">
                        <input type="checkbox" name="whatsapp"> <span class="slider round"></span> </label>
                </div>
            </div>
        </div>
    </section>
    <div class="text-end section-space">
        <div class="container-xl">
            <button class="btn btn-primary" onclick="Submit(event)">Submit & Continue</button>
        </div>
    </div>
    <input type="hidden" name="car_year" id="car_year" value="" />
    <input type="hidden" name="car_brand" id="car_brand" value="" />
    <input type="hidden" name="car_model" id="car_model" value="" />
    <input type="hidden" name="car_version" id="car_version" value="" />
</form>
@endsection

@section('scripts')
<script type='text/javascript'
    src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script>
    var maxImages = 6;

function Submit (e)
{
    e.preventDefault();

    if($("#car_year").val() == "" || $("#car_brand").val() == "" || $("#car_model").val() == "")
    {
        ShowToaster("Error", "Please select all Make/Model & Year");
        return;
    }
    if($("#city").val() == "0")
    {
        ShowToaster("Error", "Kindly select city first");
        return;
    }
    if($("#car_registeration_city").val() == "")
    {
    ShowToaster("Error", "Kindly select car registeration city first");
    return;
    }
    if($("#exterior_color").val() == "0")
    {
    ShowToaster("Error", "Kindly select exterior color first");
    return;
    }

    if($("#mileage").val() == "")
    {
        ShowToaster("Error", "Enter Mileage");
        return;
    }

    if($("#price").val() == "")
    {
        ShowToaster("Error", "Enter price");
        return;
    }
    if($("#price").val() < 100000)
    {
        ShowToaster("Error", "Your price is must be greater then 100000");
        return;
    }

    if($("#description").val() == "")
    {
        ShowToaster("Error", "Enter description");
        return;
    }

    if($("#engine_capacity").val() == "")
    {
        ShowToaster("Error", "Enter engine capacity");
        return;
    }

    if($("#exterior_image")[0].files.length == 0)
    {
        ShowImageError("exterior_image", "Atleast select one photo");
        var access = document.getElementById("exterior_div_top");
        access.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    else if($("#exterior_image")[0].files.length > 5)
    {
        ShowImageError("exterior_image", "Maximum 5 photos allowed");
        var access = document.getElementById("exterior_div_top");
        access.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    else
    {
        RemoveImageError("exterior_image");
    }

    if($("#interior_image")[0].files.length == 0)
    {
        ShowImageError("interior_image", "Atleast select one photo");
        var access = document.getElementById("interior_div_top");
        access.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    else if($("#interior_image")[0].files.length > 5)
    {
        ShowImageError("interior_image", "Maximum 5 photos allowed");
        var access = document.getElementById("interior_div_top");
        access.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    else
    {
        RemoveImageError("interior_image");
    }

    if($("#hotspot_image")[0].files.length > 5)
    {
        ShowImageError("hotspot_image", "Maximum 5 photos allowed");
        return;
    }
    else
    {
        RemoveImageError("hotspot_image");
    }

    PreLoader();
    $("#post_ad_form").submit();


}

function SelectModalYear (year)
{
    $('input[name=car_year]').val(year);



    $("#infobox-year").children().children().removeClass("active");


    $("#year"+year).children().addClass("active");


    $("#year-pointer").removeClass("active");
    $("#make-pointer").addClass("active");
    $("#model-pointer").removeClass("active");
    $("#version-pointer").removeClass("active");

    $("#infobox-brand").show();
    $("#infobox-model").hide();
    $("#infobox-version").hide();
}

function SelectModalMake(brand_id)
{
    $('input[name=car_brand]').val(brand_id);

    $("#infobox-brand").children().children().removeClass("active");

    $("#make"+brand_id).children().addClass("active");

    PreLoader();
    $.ajax({
    url: '{{route('webapi.models')}}',
    type: 'POST',
    data: {
    brand_id: brand_id,
    _token: '{{csrf_token()}}'
    },
    success: function(data) {
    if (data.result == 1)
    {
        var html = PopulateModel(data.data);
        $("#infobox-model").html(html);

        $("#infobox-brand").show();
        $("#infobox-model").show();
        $("#infobox-year").show();

        PreLoader("hide");

    }
    else
    {
        ShowToaster("Error", data.message);
    }
    },
    error: function(error) {
    console.log(error);
    }
    });


    $("#year-pointer").removeClass("active");
    $("#make-pointer").removeClass("active");
    $("#model-pointer").addClass("active");
    $("#version-pointer").removeClass("active");

    $("#infobox-version").hide();
}

function SelectModalModel(model_id)
{

    $('input[name=car_model]').val(model_id);

    $("#infobox-model").children().children().removeClass("active");

    $("#model"+model_id).children().addClass("active");


    var car_year = $("#car_year").val();

    $("#year-pointer").removeClass("active");
    $("#make-pointer").removeClass("active");
    $("#model-pointer").removeClass("active");
    $("#version-pointer").addClass("active");

    PreLoader();
    $.ajax({
    url: '{{route('webapi.versions')}}',
    type: 'POST',
    data: {
    model_id: model_id,
    year_model: car_year,
    _token: '{{csrf_token()}}'
    },
    success: function(data) {
    if (data.result == 1)
    {
    var html = PopulateVersion(data.data);
    $("#infobox-version").html(html);

    $("#infobox-brand").show();
    $("#infobox-model").show();
    $("#infobox-year").show();
    $("#infobox-version").show();

    PreLoader("hide");

    }
    else
    {
    ShowToaster("Error", data.message);
    }
    },
    error: function(error) {
    console.log(error);
    }
    });
}

function SelectModalVersion (version_id)
{
    $('input[name=car_version]').val(version_id);

    $("#infobox-version").children().children().removeClass("active");

    $("#version"+version_id).children().addClass("active");


}

function CloseCarInfoDialog()
{
    var make_id = $('input[name=car_brand]').val();
    var model_id = $('input[name=car_model]').val();
    if (make_id == "" || model_id == "")
    {
        $('#make_model_version').val('');
        $("#carinfo-popup").modal("hide");
        $("#make_model_version").removeClass("input-green");
        $("#tick_icon").hide();
        return;
    }
    var make = $("a#make"+make_id).data("make_name");
    var model = $("a#model"+model_id).data("model_name");
    var version_id = $('input[name=car_version]').val();
    if(version_id != "")
    {
        var version = $("a#version"+version_id).data("version_name");
        $('#make_model_version').val(make+'/'+model+'/'+version);
    }
    else
    {
        $('#make_model_version').val(make+'/'+model);
    }

    $("#carinfo-popup").modal("hide");
    $("#make_model_version").addClass("input-green");
    $("#tick_icon").show();
}

function OpenCarInfoDialog()
{
    $("#year-pointer").addClass("active");
    $("#make-pointer").removeClass("active");
    $("#model-pointer").removeClass("active");
    $("#version-pointer").removeClass("active");

    $("#infobox-brand").hide();
    $("#infobox-model").hide();
    $("#infobox-version").hide();

    $("#carinfo-popup").modal("show");

    $('input[name=car_year]').val("");
    $('input[name=car_brand]').val("");
    $('input[name=car_model]').val("");
    $('input[name=car_version]').val("");
}

function PopulateModel (data)
{
    var html = "";
    html += "<h5>Popular</h5>";
    for(var i = 0; i < data.length; i++)
    {
        if(data[i].mark_popular == "YES")
        {
            html += '<a href="javascript:SelectModalModel('+data[i].id+')" id="model'+data[i].id+'" data-model_name="'+data[i].name+'">';
            html += '<div class="modalbox">';
            html += data[i].name;
            html += '<i class="clr-primary fa fa-chevron-right"></i>';
            html += '</div>';
            html += '</a>';
        }

    }

    html += '<br><h4 class="other">Others</h4>';

    for(var i = 0; i < data.length; i++) {
        if(data[i].mark_popular=="NO" )
        {
            html +='<a href="javascript:SelectModalModel('+data[i].id+')" id="model'+data[i].id+'" data-model_name="'+data[i].name+'">';
            html += '<div class="modalbox">';
                html += data[i].name;
                html += '<i class="clr-primary fa fa-chevron-right"></i>';
                html += '</div>';
            html += '</a>';
        }

        }

    return html;
}

function PopulateVersion(data)
{
    var html = "";
    html += "<h5>All</h5>";
    for(var i = 0; i < data.length; i++)
    {
        html +='<a href="javascript:SelectModalVersion(' +data[i].id+')" id="version'+data[i].id+'" data-version_name="'+data[i].name+'">';
        html += '<div class="vbox">';
            html += '<h3>'+data[i].name+'</h3>';
            html += '<h6>'+data[i].description+'</h6>';
            html += '</div>';
        html += '</a>';
        }
    return html;
}

function RotateImage(name)
{
    var rotate = parseInt($("#rotation_image_"+name).val());
    rotate += 90;
    rotate %= 360;
    $("#rotation_image_"+name).val(rotate);
    $("#exterior_image_"+name).css("rotate", rotate+"deg");
}

function RotateImageInterior(name)
{
    var rotate = parseInt($("#rotation_image_interior_"+name).val());
    rotate += 90;
    rotate %= 360;
    $("#rotation_image_interior_"+name).val(rotate);
    $("#interior_image_"+name).css("rotate", rotate+"deg");
}

function RotateImageHotSpot(name)
{
    var rotate = parseInt($("#rotation_image_hotspot_"+name).val());
    rotate += 90;
    rotate %= 360;
    $("#rotation_image_hotspot_"+name).val(rotate);
    $("#hotspot_image_"+name).css("rotate", rotate+"deg");
}

function ImageRemoveMultiple (imageAddress, type, index)
{
    imageAddress.hide();
    if(type == 1)//for exterior
    {
        var files = $("#exterior_image")[0].files;
        var fileBuffer = new DataTransfer();
        for (let i = 0; i < files.length; i++)
        { // Exclude file in specified index
            if ((index-1) !== i)
                fileBuffer.items.add(files[i]);
        }
        $("#exterior_image")[0].files = fileBuffer.files
        MultipleImageValidation(type);
    }
    else if(type == 2)//Interior
    {
        var files = $("#interior_image")[0].files;
        var fileBuffer = new DataTransfer();
        for (let i = 0; i < files.length; i++)
        { // Exclude file in specified index
            if ((index-1) !== i)
                fileBuffer.items.add(files[i]);
        }
        $("#interior_image")[0].files = fileBuffer.files
        MultipleImageValidation(type);
    }
    else
    {
        var files = $("#hotspot_image")[0].files;
        var fileBuffer = new DataTransfer();
        for (let i = 0; i < files.length; i++)
        { // Exclude file in specified index
            if ((index-1) !== i)
                fileBuffer.items.add(files[i]);
        }
        $("#hotspot_image")[0].files = fileBuffer.files
        MultipleImageValidation(type);
    }



}

function MultipleImageValidation (type)
{
    if(type == 1)//Exterior
    {
        if($("#exterior_image")[0].files.length == 0)
        {
            ShowImageError("exterior_image", "Atleast select one photo");
            return;
        }
        else if($("#exterior_image")[0].files.length > 5)
        {
            ShowImageError("exterior_image", "Maximum 5 photos allowed");
            return;
        }
        else
        {
            RemoveImageError("exterior_image");
        }
    }
    else if(type == 2)//Interior
    {
        if($("#interior_image")[0].files.length == 0)
        {
            ShowImageError("interior_image", "Atleast select one photo");
            return;
        }
        else if($("#interior_image")[0].files.length > 5)
        {
            ShowImageError("interior_image", "Maximum 5 photos allowed");
            return;
        }
        else
        {
            RemoveImageError("interior_image");
        }
    }
    else //Hotspot
    {
        if($("#hotspot_image")[0].files.length > 5)
        {
            ShowImageError("hotspot_image", "Maximum 5 photos allowed");
            return;
        }
        else
        {
            RemoveImageError("hotspot_image");
        }
    }
}
</script>
<script>
    /*
Reference: http://jsfiddle.net/BB3JK/47/
*/

$('select').each(function(){
    var $this = $(this), numberOfOptions = $(this).children('option').length;

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
    }

    var $listItems = $list.children('li');

    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').not(this).each(function(){
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

    $(document).ready(function (e) {

    $('#video').change(function(){

    let reader = new FileReader();

    $("#video_content").show();
    $("#video_remove").show();

    reader.onload = (e) => {

        $('#preview-business_image').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);

    });

});

});

$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;
        if(filesAmount > maxImages ){
            filesAmount = maxImages;
        }
            for (i = 0; i < filesAmount; i++) {
                /*var reader = new FileReader();

                reader.onload = function(event) {
                    $("#exterior_image_"+(i)).attr('src', event.target.result);
                    $("#exterior_image_"+(i)).parent().show();

                }

                reader.readAsDataURL(input.files[i]);*/

                setupFileReader(input.files[i], i+1, placeToInsertImagePreview)
            }
        }

    };

    var setupFileReader = function(file, index, placeToInsertImagePreview) {

            var name = file.name;
            var reader = new FileReader();

            reader.onload = function(event) {
                $(placeToInsertImagePreview + (index)).attr('src', event.target.result);
                $(placeToInsertImagePreview + (index)).parent().show();

            }

            reader.readAsDataURL(file);

    };

    $('#exterior_image').on('change', function() {
        imagesPreview(this, '#exterior_image_');
    });

    $('#interior_image').on('change', function() {
        imagesPreview(this, '#interior_image_');
    });

    $('#hotspot_image').on('change', function() {
        imagesPreview(this, '#hotspot_image_');
    });

});



$('document').ready(function() {

    $('#secondary_number').inputmask('+999999999999');

    $(".select-options li").on("click", function() {
        const rel_value = $(this).attr("rel");
        if( rel_value != 0 ) {
            $(this).parent().parent().addClass('input-green');
        }
        else {
            if($(this).parent().parent().children().attr("name") == "car_registeration_city" || $(this).parent().parent().children().attr("name") == "car_registeration_year")
            {
                $(this).parent().parent().addClass('input-green');
            }
            else
            {
                $(this).parent().parent().removeClass("input-green");
            }
        }
    });

    $(".remove_pointer_events").each(function() {
        $(this).parent().addClass("parent_remove_pointer_events");
    });

    $(".parent_remove_pointer_events").each(function() {
        if( !$(".parent_remove_pointer_events ul li").hasClass("remove_pointer_event") ) {
            $(".parent_remove_pointer_events ul li").addClass("remove_pointer_event");
        }
    });

    $("#condition").parent().addClass('input-green');
    $("#engine_type").parent().addClass('input-green');
    $("#transmission").parent().addClass('input-green');
    $("#drive_type").parent().addClass('input-green');
    $("#assembly").parent().addClass('input-green');

    $("#mileage").change(function() {
        if($("#mileage").val() != "")
        {
            $("#mileage").addClass("input-green");
        }
        else
        {
            $("#mileage").removeClass("input-green");
        }
    });

    $("#price").change(function() {
    if($("#price").val() != "")
    {
    $("#price").addClass("input-green");
    }
    else
    {
    $("#price").removeClass("input-green");
    }
    });

    $("#engine_capacity").change(function() {
    if($("#engine_capacity").val() != "")
    {
    $("#engine_capacity").addClass("input-green");
    }
    else
    {
    $("#engine_capacity").removeClass("input-green");
    }
    });

    $("#secondary_number").change(function() {
    if($("#secondary_number").val() != "")
    {
    $("#secondary_number").addClass("input-green");
    }
    else
    {
    $("#secondary_number").removeClass("input-green");
    }
    });


    $("#exterior_image").change(function(){
        MultipleImageValidation(1);
    });

    $("#interior_image").change(function(){
        MultipleImageValidation(2);
    });

    $("#hotspot_image").change(function(){
        MultipleImageValidation(3);
    });




});

function RemoveVideo()
{
    $("#video_content").hide();
    $("#video_remove").hide();

    $('#preview-business_image').attr('src', '');

    $("#video").val("");
}
</script>
@endsection
