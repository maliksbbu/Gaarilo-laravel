@extends('front.layout.main')
@section('content')
    <?php use App\Models\CarOffer; ?>
    <section class="section-space mt-5 pb-0" id="car-detail-gallery">
        <div class="container-xl">
            <div class="row">
                <div class="col-xl-8 col-lg-7 mb-4 mb-lg-0">
                    <div class="cbox">
                        <div class="cbox-header mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="mb-3">{{ car_name($car->id) }}</h3>
                                    <p><i class="fa fa-map-marker-alt clr-primary me-2"></i>{{ $car->city->name }},
                                        {{ $car->city->province->name }}</p>
                                    @if ($ownCar == true)
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted me-2 fs-12">
                                                <i class="fa fa-eye me-1"></i>
                                                {{ $car->ad_views }} Views
                                            </span>
                                            <span class="text-muted me-2 fs-12">
                                                <i class="fa fa-phone-alt me-1"></i>
                                                {{ $car->phone_views }} Views
                                            </span>
                                            <div class="text-muted fs-12">Last Updated:
                                                {{ date('m/d/Y', strtotime($car->updated_at)) }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 text-end">
                                    @if ($ownCar == true && $car->status != 'PENDING' && $car->status != 'SOLD')
                                        <button class="btn btn-danger ms-auto btn-height-sm btn-offer mb-4"><i
                                                class="fa fa-exclamation-circle fa-lg"></i> {{ $car->CountPendingOffer() }}
                                            New Offers</button>
                                    @else
                                        <h5 class="mb-4">Last Updated: {{ date('m/d/Y', strtotime($car->updated_at)) }}
                                        </h5>
                                    @endif
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="cbtag active" id="exterior_tag" onclick="ChangeImageType(1)">Exterior
                                        </div>
                                        <div class="cbtag ms-3" id="interior_tag" onclick="ChangeImageType(2)">Interior
                                        </div>
                                        <div class="cbtag ms-3" id="hotspot_tag" onclick="ChangeImageType(3)">Hotspot</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($car->exterior_images as $key => $images)
                            @if ($key == 0)
                                <div class="gallery-main-view mb-3">
                                    <img src="{{ $images->image }}" id="main_image" class="w-100 h-100">
                                    <div class="gallery-arrows">
                                        <a class="arrowl" href="javascript:PrevImage()">
                                            <img src="{{ URL::asset('front/images/arrowl.png') }}">
                                        </a>
                                        <a class="arrowr" href="javascript:NextImage()">
                                            <img src="{{ URL::asset('front/images/arrowr.png') }}">
                                        </a>
                                    </div>
                                    @if ($car->video != '')
                                        <div class="gallery-playbtn" onclick="OpenVideoModal()">
                                            <div class="vdo-icon">
                                                <i class="fa fa-play"></i>
                                            </div>
                                        </div>
                                        <div class="modal fade show popup-small" id="popup_video" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            {{ car_name($car->id) }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close" onclick='$("#popup_video").modal("hide");'>
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <video width="100%" controls type="*"
                                                        src="{{ $car->video }}"></video>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="gallery-items d-flex align-items-center flex-wrap mb-5 " id="images_slider">



                                </div>
                            @break
                        @endif
                    @endforeach


                    <div class="icon-details d-flex align-items-center flex-wrap mb-5">
                        <div class="me-5 text-center">
                            <div class="icomain mb-3">
                                <img src="{{ URL::asset('front/images/auto.png') }}">
                            </div>
                            <h5>{{ $car->transmission }}</h5>
                        </div>
                        <div class="me-5 text-center">
                            <div class="icomain mb-3">
                                <img src="{{ URL::asset('front/images/2020.png') }}">
                            </div>
                            <h5>{{$car->car_year}}</h5>
                        </div>
                        <div class="me-5 text-center">
                            <div class="icomain mb-3">
                                <img src="{{ URL::asset('front/images/petrol.png') }}">
                            </div>
                            <h5>{{ $car->engine_type }}</h5>
                        </div>
                        <div class="me-5 text-center">
                            <div class="icomain mb-3">
                                <img src="{{ URL::asset('front/images/km.png') }}">
                            </div>
                            <h5>{{ $car->mileage }} km</h5>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-xl-6 col-lg-12 col-md-6">
                            <div class="dtbox">
                                <span>Registered In</span>
                                <b class="ms-auto">{{ registertion_city($car->car_registeration_city) }}</b>
                            </div>
                            <div class="dtbox bg-white">
                                <span>Assembly</span>
                                <b class="ms-auto">{{ $car->assembly }}</b>
                            </div>
                            <div class="dtbox">
                                <span>Drive Type</span>
                                <b class="ms-auto">{{ $car->drive_type }}</b>
                            </div>
                            <div class="dtbox bg-white">
                                <span>Body Type</span>
                                <b class="ms-auto">{{ $car->model->type->name }}</b>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-md-6">
                            <div class="dtbox">
                                <span>Color</span>
                                <b class="ms-auto">{{ $car->color->name }}</b>
                            </div>
                            <div class="dtbox bg-white">
                                <span>Engine Capacity</span>
                                <b class="ms-auto">{{ $car->engine_capacity }}cc</b>
                            </div>
                            <div class="dtbox">
                                <span>Condition</span>
                                <b class="ms-auto">{{ $car->condition }}</b>
                            </div>
                            @if ($car->car_registeration_year)
                                <div class="dtbox bg-white">
                                    <span>Registered Year</span>
                                    <b class="ms-auto">{{ $car->car_registeration_year }}</b>
                                </div>
                            @endif
                        </div>
                    </div>


                    <div class="cdfeature mb-4">
                        <h3 class="cdheading mb-4 mt-5">Car Features</h3>
                        <div class="row">
                            @foreach ($car->list_feature as $feature)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <span>{{ $feature->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <h3 class="cdheading mb-4 mt-5">Seller Comments</h3>
                    <p>{{ $car->description }} </p>

                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                @if ($ownCar == true)
                    @switch($car->status)
                        @case('PENDING')
                            <a class="btn w-100 mb-3 btn-showphone btn-primary" href="{{ route('edit-ad', $car->id) }}">Edit
                                Ad
                            </a>
                        @break

                        @case('SOLD')
                            <a class="btn w-100 mb-3 btn-showphone btn-primary" href="{{ route('post-ad') }}">
                                Post Similar Ad
                            </a>
                        @break

                        @case('REJECTED')
                            <a class="btn w-100 mb-3 btn-showphone btn-primary"
                                href="{{ route('edit-ad', $car->id) }}">Review Ad
                            </a>
                        @break

                        @default
                            <a class="btn w-100 mb-3 btn-showphone btn-primary" href="{{ route('edit-ad', $car->id) }}"><img
                                    src="{{ URL::asset('front/images/edit-white.svg') }}" class="me-2">
                                Edit Ad
                            </a>
                    @endswitch


                    <a class="btn w-100 mb-3 btn-showphone btn-danger" href="{{ route('car.delete', $car->id) }}"><i
                            class="fa fa-trash-alt me-2"></i>
                        Delete Ad
                    </a>

                    @if ($car->status != 'SOLD' && $car->status != 'PENDING' && $car->status != 'REJECTED')
                        <a class="btn w-100 mb-5 btn-showphone btn-success" href="javascript:OpenSoldDialog()"><i
                                class="fa fa-check me-2"></i>
                            Mark as sold
                        </a>
                    @endif
                    <div class="modal fade popup-small" id="mark-sold-popup" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <form action="{{ route('car.sold') }}" method="POST" id="mark-sold-form">
                                {{ csrf_field() }}
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h2 class="modal-heading text-center my-3 mb-5">Mark as Sold</h2>

                                        <div class="d-flex align-items-center justify-content-center mb-3">
                                            <label class="white-space-nowrap me-2 w-35">Sold Price</label>
                                            <input type="number" class="form-control" placeholder="Price"
                                                id="price" name="price" oninput="check(this)">
                                            <input hidden class="form-control" name="id"
                                                value="{{ $car->id }}">
                                        </div>

                                        <div class="d-flex justify-content-center my-4 text-center">
                                            <label class="custom-checkbox">
                                                Sold by GaariLo
                                                <input type="checkbox" checked="checked">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="text-center mb-3">
                                            <button class="btn btn-primary" onclick="MarkSold(event)">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                @if ($car->status != 'SOLD')
                    <div class="cbox mb-3">
                        @if ($ownCar == true)
                            <form action="{{ route('car.update.price') }}" method="POST">
                                {{ csrf_field() }}
                            @else
                                <form action="{{ route('car.offer') }}" method="POST">
                                    {{ csrf_field() }}
                        @endif
                        <h2 class="my-3">PKR. {{ number_format($car->price_range) }}</h2>
                        <hr>

                        @if (Session::has('user'))
                            <?php $response = CarOffer::OfferOnCar(Session::get('user')->id, $car->id); ?>

                            @if (count((array) $response) != 0)
                                @switch($response['color'])
                                    @case('blue')
                                        <h5 class="my-5 shoffer" style="color: blue">{{ $response['message'] }} PKR.
                                            {{ number_format($response['price']) }}</h5>
                                    @break

                                    @case('green')
                                        <h5 class="my-5 shoffer" style="color: green">{{ $response['message'] }} PKR.
                                            {{ number_format($response['price']) }}</h5>
                                    @break

                                    @case('red')
                                        <h5 class="my-5 shoffer">{{ $response['message'] }} PKR.
                                            {{ number_format($response['price']) }}</h5>
                                    @break

                                    @default
                                @endswitch
                            @endif
                        @endif



                        @if ($ownCar != true)
                            <label class="mb-2">Make Offer</label>
                            <input type="number" class="form-control mb-4" name="amount"
                                placeholder="Amount in PKR" min="0" oninput="check(this)">
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            @if (!Session::has('user'))
                                <a class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                                    onclick="ShowToaster('Error', 'Sign-in First To Place Offer');">Submit</a>
                            @elseif($car_offer == true && $car->status != 'PENDING')
                                <a class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                                    onclick="ShowToaster('Error', 'You Already Submit Offer For This Car');">Submit</a>
                            @else
                                <input type="hidden" name="user_id" value="{{ Session::get('user')->id }}">
                                <button class="btn btn-primary w-100" type="submit">Submit</button>
                            @endif
                        @else
                            <input type="number" class="form-control mb-4" name="amount"
                                placeholder="Amount in PKR" min="0">
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            <input type="hidden" name="user_id" value="{{ Session::get('user')->id }}">
                            <button class="btn btn-primary w-100" type="submit">Update Price</button>
                        @endif

                        </form>
                    </div>
                @endif
                <div class="cbox">
                    <div class="row">
                        <div class="align-items-center col-md-4 d-flex pe-0 justify-content-center">
                            <img src="@if ($car->showroom != null) {{ $car->showroom->logo }} @endif"
                                class="overflow-hidden rounded-circle" style="width:80px; height:80px;" />
                        </div>
                        <div class="col-md-8 align-self-center">
                            <h3 class="gshow">
                                @if ($car->showroom != null)
                                    {{ $car->showroom->name }}
                                @else
                                    N/A
                                @endif
                            </h3>
                            <h4>
                                @if ($car->showroom != null)
                                    {{ $car->showroom->city->name }}
                                @else
                                    N/A
                                @endif
                            </h4>
                            <div class="rating mb-2 justify-content-start">
                                <span class="me-2">
                                    @if ($car->showroom != null)
                                        {{ $car->showroom->rating }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <?php
                                $stars_count = $car->showroom->rating;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($stars_count >= $i) {
                                ?>
                                <span class="fa fa-star checked"></span>
                                <?php
                                    } else {
                                    ?>
                                <span class="fa fa-star"></span>
                                <?php

                                    }
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                    <hr>


                    <div class="my-4">
                        <div class="row">
                            <div class="col-md-7">
                                Member Since:
                            </div>
                            <div class="col-md-5 text-end">
                                <b class="fw-normal">
                                    @if ($car->showroom != null)
                                        {{ date('Y', strtotime($car->showroom->updated_at)) }}
                                    @else
                                        N/A
                                    @endif
                                </b>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-md-7">
                                Years of service:
                            </div>
                            <div class="col-md-5 text-end">
                                <b class="fw-normal">
                                    @if ($car->showroom != null)
                                        {{ date('Y', strtotime('now')) - date('Y', strtotime($car->showroom->updated_at)) }}
                                    @else
                                        N/A
                                    @endif
                                </b>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-md-7">
                                No. of reviews:
                            </div>
                            <div class="col-md-5 text-end">
                                <b class="fw-normal">
                                    @if ($car->showroom != null)
                                        {{ $car->showroom->count_review }}
                                    @else
                                        N/A
                                    @endif
                                </b>
                            </div>
                        </div>
                    </div>

                    <button class="btn w-100 mb-3 btn-showphone btn-success" id="showroom_phone_number"
                        @if ($car->showroom != null) onclick="ShowShowroomPhoneNumber({{ $car->showroom->user->phone }})" @endif><i
                            class="fa fa-phone-alt me-2"></i>
                        Show Phone No.
                    </button>
                    <a class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                        @if ($car->showroom != null) href="{{ route('showroom-detail', $car->showroom->id) }}" @endif>Visit
                        Showroom</a>
                </div>

            </div>
        </div>
    </div>
</section>


<section id="top-pics" class="section-space">
    <div class="container-xl">
        <h2 class="heading mb-5">Similar <span>Picks</span></h2>

        <div class="row mb-4">

            @foreach ($topPicks as $key => $topCar)
                <div class="col-lg-3 col-md-6 mb-3">
                    <a href="{{ route('car-detail', $topCar->id) }}">
                        <div class="detail-box">
                            <div class="db-top">
                                <img src="{{ $topCar->image }}" class="w-100" style="rotate:{{$topCar->rotation}}deg;">
                                <div class="imgcounter">
                                    <span class="me-2">{{ $topCar->exterior_images->count() }}</span>
                                    <img src="{{ URL::asset('front/images/images-icon.png') }}">
                                </div>
                                @if (Session::has('user'))
                                    <?php $response = CarOffer::OfferOnCar(Session::get('user')->id, $topCar->id); ?>

                                    @if (count((array) $response) != 0)
                                        @switch($response['color'])
                                            @case('blue')
                                                <div class="dbprice bg-blue" onclick="car_detail({{ $topCar->id }})"
                                                    style="cursor: pointer">
                                                    <h5 class="m-0">PKR. {{ number_format($response['price']) }}</h5>
                                                    <p class="m-0">{{ $response['message'] }}</p>
                                                </div>
                                            @break

                                            @case('green')
                                                <div class="dbprice" onclick="car_detail({{ $topCar->id }})"
                                                    style="cursor: pointer; background-color: green">
                                                    <h5 class="m-0">PKR. {{ number_format($response['price']) }}</h5>
                                                    <p class="m-0">{{ $response['message'] }}</p>
                                                </div>
                                            @break

                                            @case('red')
                                                <div class="dbprice" onclick="car_detail({{ $topCar->id }})"
                                                    style="cursor: pointer">
                                                    <h5 class="m-0">PKR. {{ number_format($response['price']) }}</h5>
                                                    <p class="m-0">{{ $response['message'] }}</p>
                                                </div>
                                            @break

                                            @default
                                        @endswitch
                                    @endif
                                @endif
                            </div>
                            <div class="db-body">
                                <h5 class="m-0 mb-3">
                                    {{ $topCar->brand->name }} {{ $topCar->model->name }} {{ $topCar->car_year }}
                                </h5>
                                <div class="d-flex align-items-center mb-3">
                                    <h3 class="m-0">PKR. {{ number_format($topCar->price_range) }}</h3>
                                    <img src="@if ($topCar->showroom != null) {{ $topCar->showroom->logo }} @endif"
                                        class="ms-auto pkrimg">
                                </div>
                                <h6 class="mb-3">{{ $topCar->city->name }}</h6>

                                <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
                                    <div><span class="me-3">{{ $topCar->car_year }}</span> |</div>
                                    <div><span class="mx-3 ps-2"> {{ $topCar->mileage }} km </span> </div>
                                    <div>|<span class="ms-3">{{ $topCar->engine_type }}</span></div>
                                </div>
                                <div class="m-0 mb-3 sepreator-text d-flex flex-wrap">
                                    <div></div>
                                    <div><span class="me-3">{{ $topCar->engine_capacity }}cc </span> |</div>
                                    <div><span class="ms-3">{{ $topCar->transmission }}</span></div>
                                </div>
                                <div class="mb-3 text-muted mb-4">Last Updated:
                                    {{ date('m/d/Y', strtotime($topCar->updated_at)) }}
                                </div>

                                <button class="btn btn-success w-100">
                                    <i class="fa fa-phone-alt me-2"></i>
                                    Show Phone No.
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach



        </div>




    </div>
</section>

<section id="getapp" class="section-space pb-0">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-7 col-md-8 mb-3 mb-lg-0">
                <div class="get-left">
                    <div class="getinner">
                        <h4 class="get-heading mb-3">Get <span class="clr-primary">GaariLo</span> APP</h4>
                        <h5 class="mb-2">Buy & Sell cars from anywhere in Pakistan</h5>
                        <!-- <h6 class="mb-4">You can Download Gaarilo apps by Scanning below QR Code</h6> <img
                            src="{{ URL::asset('front/images/qr.png') }}" class="img-fluid"> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-4 d-lg-block d-none"> <img src="{{ URL::asset('front/images/appimg.png') }}"
                    class="img-fluid"> </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    var imagesExterior, imagesInterior, imagesHotspot, countImages, selectedImages, imageType;
    var currentIndex;

    $(document).ready(function() {
        imagesExterior = ({!! json_encode($car->exterior_images) !!});
        imagesInterior = ({!! json_encode($car->interior_images) !!});
        imagesHotspot = ({!! json_encode($car->hotspot_images) !!});
        countImages = imagesExterior.length;
        selectedImages = imagesExterior;
        currentIndex = 0;
        incrementFlag = 0;
        $("#images_slider").html(RenderImages());
    });

    function ShowShowroomPhoneNumber(number) {
        $("#showroom_phone_number").html(number)
        if (incrementFlag == 0) {
            incrementFlag = 1;
            IncrementPhoneNumber();
        }
    }

    function NextImage() {
        currentIndex++;
        currentIndex = currentIndex % countImages;

        var image = selectedImages[currentIndex].image;
        var rotation = selectedImages[currentIndex].rotation;
        ChangeMainImage(image, rotation);
    }

    function PrevImage() {
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = countImages - 1;
        }

        var image = selectedImages[currentIndex].image;
        var rotation = selectedImages[currentIndex].rotation;
        ChangeMainImage(image, rotation);
    }

    function ChangeMainImage(image, rotation ,index = "") {
        if (index != "") {
            currentIndex = index;
            image = selectedImages[currentIndex].image;
            rotation = selectedImages[currentIndex].rotation;
        }
        $("#main_image").attr('src', image);
        $("#main_image").css("rotate", rotation+"deg");
    }

    function ChangeImageType(type) {
        imageType = type;
        if (type == 1) {
            $("#exterior_tag").addClass("active");
            $("#interior_tag").removeClass("active");
            $("#hotspot_tag").removeClass("active");

            selectedImages = imagesExterior;
            countImages = imagesExterior.length;

            $("#images_slider").html(RenderImages());
        } else if (type == 2) {
            $("#exterior_tag").removeClass("active");
            $("#interior_tag").addClass("active");
            $("#hotspot_tag").removeClass("active");

            selectedImages = imagesInterior;
            countImages = imagesInterior.length;

            $("#images_slider").html(RenderImages());
        } else {
            if (imagesHotspot.length > 0) {
                $("#exterior_tag").removeClass("active");
                $("#interior_tag").removeClass("active");
                $("#hotspot_tag").addClass("active");

                selectedImages = imagesHotspot;
                countImages = imagesHotspot.length;

                $("#images_slider").html(RenderImages());
            } else {}
        }
    }

    function IncrementPhoneNumber() {
        var car_id = {{ $car->id }};
        $.ajax({
            url: '{{ route('webapi.increment.phonecount') }}',
            type: 'GET',
            data: {
                car_id: car_id,
            },
            success: function(data) {

            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function RenderImages() {
        var html = '';
        currentIndex = 0;
        for (let index = 0; index < selectedImages.length; index++) {
            const element = selectedImages[index];
            if (index == 0) {
                ChangeMainImage(element.image, element.rotation);
            }
            html += '<div class="imgitem me-3">';
            html += '<img class="h-100 w-100" src="' + element.image + '" width="200px" onclick=ChangeMainImage("","'+element.rotation+'","' +
                index + '") style="rotate: '+element.rotation+'deg;"> </div>';
        }
        return html;
    }

    function OpenSoldDialog() {
        $("#mark-sold-popup").modal('show');
    }

    function check(input) {
        var price = "<?php echo $car->price_range; ?>";
        if (Number(input.value) > price) {
            input.setCustomValidity('your offer must be equel or less then car price.');
        } else {
            input.setCustomValidity('');
        }
    }

    function MarkSold(e) {
        e.preventDefault();
        var price = parseInt("{{ $car->price_range }}");
        var addedPrice = parseInt($("#price").val());
        if (addedPrice > price) {
            ShowToaster("Error", "your offer must be equel or less then car price.");
            return;
        } else {
            $("#mark-sold-popup").modal("hide");
            $("#mark-sold-form").submit();
        }
    }

    function OpenVideoModal() {
        $("#popup_video").modal("show");
    }
</script>
@endsection
