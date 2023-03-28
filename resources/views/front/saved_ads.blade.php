@extends('front.layout.main')
@section('content')
<?php use App\Models\CarOffer;?>
<section class="section-space py-5 review-header">
    <div class="container-xl">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3 class="mb-0">My Saved Ads</h3>
        </div>
    </div>
</section>



<section class="section-space pb-3 offers-page">
    <div class="container-xl">



        @foreach ($favourites as $fav)
        <div class="detail-box listview mb-4">
            <div class="d-flex align-items-center flex-wrap">
                <div class="db-top me-2" onclick="car_detail({{ $fav->car->id }})" style="cursor: pointer">
                    <img src="{{$fav->car->image}}" class="w-100 h-100" style="rotate:{{$fav->car->rotation}}deg;">
                    <div class="imgcounter">
                        <span class="me-2">{{$fav->car->exterior_images->count() + $fav->car->interior_images->count() + $fav->car->hotspot_images->count()}}</span>
                        <img src="{{URL::asset('front/images/images-icon.png')}}">
                    </div>
                   @if(Session::has('user'))
                    <?php $response = CarOffer::OfferOnCar(Session::get('user')->id ,  $fav->car->id); ?>

                    @if(count((array)$response) != 0)

                    @switch($response['color'])
                    @case("blue")
                    <div class="dbprice bg-blue" onclick="car_detail({{ $fav->car->id }})" style="cursor: pointer">
                        <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                        <p class="m-0">{{$response['message']}}</p>
                    </div>
                    @break
                    @case("green")
                    <div class="dbprice" onclick="car_detail({{ $fav->car->id }})" style="cursor: pointer; background-color: green">
                        <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                        <p class="m-0">{{$response['message']}}</p>
                    </div>
                    @break
                    @case("red")
                    <div class="dbprice" onclick="car_detail({{ $fav->car->id }})" style="cursor: pointer">
                        <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                        <p class="m-0">{{$response['message']}}</p>
                    </div>
                    @break

                    @default

                    @endswitch
                    @endif
                    @endif
                    {{-- <div class="vdo-icon">
                            <i class="fa fa-play"></i>
                        </div> --}}
                </div>
                <div class="db-body" onclick="car_detail({{ $fav->car->id }})" style="cursor: pointer">
                    <h5 class="m-0 mb-1 mt-2 mt-lg-0">
                        {{$fav->car->brand->name}} {{$fav->car->model->name}} {{$fav->car->car_year}}
                    </h5>
                    <h6 class="mb-2 cityname">{{$fav->car->city->name}}</h6>
                    <div class="m-0 mb-1 sepreator-text d-flex flex-wrap">
                        <div><span class="me-3">{{$fav->car->car_year}}</span> |</div>
                        <div><span class="mx-3 ps-2"> {{$fav->car->mileage}} km </span> </div>
                        <div>|<span class="ms-3">{{$fav->car->engine_type}}</span></div>
                    </div>
                    <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
                        <div></div>
                        <div><span class="me-3">{{$fav->car->engine_capacity}}cc </span> |</div>
                        <div><span class="ms-3">{{$fav->car->transmission}}</span></div>
                    </div>
                    <div class="text-muted">Last Updated: {{date('m/d/Y',
                        strtotime($fav->car->updated_at))}}</div>

                </div>
                <div class="d-flex flex-column h-100 justify-content-lg-between ms-auto width-300">
                    <div class="text-end mb-3 mb-xl-0">
                        <h3 class="m-0 mb-md-2">PKR. {{number_format($fav->car->price_range)}}</h3>
                    </div>
                    <button class="btn btn-danger ms-auto btn-offer mb-2 w-100" onclick="RedirectToURL('{{route('ad-unfavourite',$fav->car->id)}}')"><img class="me-2" src="{{ URL::asset('front/images/unfavourite.svg') }}"> Un Favourite</button>
                    <button id="b_id{{ $fav->car->id }}" @if ($fav->car->showroom != null) value="{{ $fav->car->showroom->user->business_phone_number ? $fav->car->showroom->user->business_phone_number : $fav->car->showroom->user->phone }}" onclick="change_text_to_phone({{ $fav->car->id }})" @endif
                        class="btn btn-success w-100">
                        <i class="fa fa-phone-alt me-2"></i>
                        <span id="change_text{{ $fav->car->id }}">Show Phone No.</span>
                    </button>

                </div>
            </div>
        </div>
        @endforeach


</section>

<section id="getapp" class="section-space">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-7 col-md-8 mb-3 mb-lg-0">
                <div class="get-left">
                    <div class="getinner">
                        <h4 class="get-heading mb-3">Get <span class="clr-primary">GaariLo</span> APP</h4>
                        <h5 class="mb-2">Buy & Sell cars from anywhere in Pakistan</h5>
                        {{-- <h6 class="mb-4">You can Download Gaarilo apps by Scanning below QR Code</h6> <img src="{{ URL::asset('front/images/qr.png') }}" class="img-fluid"> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-4 d-lg-block d-none"> <img src="{{ URL::asset('front/images/appimg.png') }}" class="img-fluid"> </div>
        </div>
    </div>
</section>
@endsection
<script>
    function change_text_to_phone($car_id) {
        var $phone_number = document.getElementById('b_id' + $car_id).value;
        var x = document.getElementById("change_text" + $car_id);
        if (x.innerHTML === "Show Phone No.") {
            x.innerHTML = $phone_number;
        } else {
            x.innerHTML = "Show Phone No.";
        }
    }

    function car_detail($car_id) {
        window.location.href = "{{ 'car-detail/' }}" + $car_id;
    }
</script>
