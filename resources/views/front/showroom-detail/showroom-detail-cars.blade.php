<?php
use App\Models\Favourite;
use App\Models\CarOffer;
?>
@foreach ($cars as $car)
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="detail-box">
            <div class="db-top">
                <img src="{{ $car->image }}" class="w-100" onclick="car_detail({{ $car->id }})"
                    style="cursor: pointer; rotate:{{$car->rotation}}deg;">
                <div class="imgcounter" onclick="car_detail({{ $car->id }})" style="cursor: pointer">
                    <span
                        class="me-2">{{ $car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count() }}</span>
                    <img src="{{ URL::asset('front/images/images-icon.png') }}">
                </div>
                @if(Session::has('user'))
                <?php $response = CarOffer::OfferOnCar(Session::get('user')->id ,  $car->id); ?>

                @if(count((array)$response) != 0)

                @switch($response['color'])
                @case("blue")
                <div class="dbprice bg-blue" onclick="car_detail({{ $car->id }})" style="cursor: pointer">
                    <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                    <p class="m-0">{{$response['message']}}</p>
                </div>
                @break
                @case("green")
                <div class="dbprice" onclick="car_detail({{ $car->id }})" style="cursor: pointer; background-color: green">
                    <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                    <p class="m-0">{{$response['message']}}</p>
                </div>
                @break
                @case("red")
                <div class="dbprice" onclick="car_detail({{ $car->id }})" style="cursor: pointer">
                    <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                    <p class="m-0">{{$response['message']}}</p>
                </div>
                @break

                @default

                @endswitch
                @endif
                @endif
            </div>
            <div class="db-body">
                <div style="overflow: hidden; text-overflow:ellipsis; white-space:nowrap; width:250px;" >
                                <h5 class="m-0 mb-3" onclick="car_detail({{ $car->id }})" style="cursor: pointer">
                    {{ car_name($car->id) }}
                </h5>
                </div>
                <div class="d-flex align-items-center mb-3" onclick="car_detail({{ $car->id }})"
                    style="cursor: pointer">
                    <h3 class="m-0">PKR. {{ $car->price_range }}</h3>
                    <img src="@if ($car->showroom != null) {{ $car->showroom->logo }} @endif"
                        class="ms-auto pkrimg">
                </div>
                <h6 class="mb-3" onclick="car_detail({{ $car->id }})" style="cursor: pointer">
                    {{ $car->city->name }}</h6>

                <div class="m-0 mb-2 sepreator-text d-flex flex-wrap" onclick="car_detail({{ $car->id }})"
                    style="cursor: pointer">
                    <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                    <div><span class="mx-3 ps-2"> {{ $car->mileage }} km </span> </div>
                    <div>|<span class="ms-3">{{ $car->engine_type }}</span></div>
                </div>
                <div class="m-0 mb-3 sepreator-text d-flex flex-wrap" onclick="car_detail({{ $car->id }})">
                    <div></div>
                    <div><span class="me-3">{{ $car->engine_capacity }}cc </span> |</div>
                    <div><span class="ms-3">{{ $car->transmission }}</span></div>
                </div>

                <?php $new_date = strtotime($car->updated_at); ?>
                <div class="align-items-center d-flex mb-3 mb-4 text-muted" style="cursor: pointer;">Last Updated:
                    {{ date('d-m-Y', $new_date) }}
                    @if (!Session::has('user'))
                        <i class="fa fa-heart fs-18 ms-auto" onclick="ShowToaster('Error', 'Sign To Proceed')"></i>
                    @else
                        @if (Favourite::CheckFavourite(Session('user')->id, $car->id) == false)
                            <i class="fa fa-heart fs-18 ms-auto" id="heart_{{ $car->id }}"
                                onclick="FavouriteMethod('{{ $car->id }}')"></i>
                        @else
                            <i class="fa fa-heart fs-18 ms-auto text-danger" id="heart_{{ $car->id }}"
                                onclick="FavouriteMethod('{{ $car->id }}')"></i>
                        @endif
                    @endif
                </div>
                <button id="b_id{{ $car->id }}"
                    @if ($car->showroom != null) value="{{ $car->showroom->user->business_phone_number
                        ? $car->showroom->user->business_phone_number
                        : $car->showroom->user->phone }}" onclick="change_text_to_phone({{ $car->id }})" @endif
                    class="btn btn-success w-100">
                    <i class="fa fa-phone-alt me-2"></i>
                    <span id="change_text{{ $car->id }}">Show Phone No.</span>
                </button>
            </div>
        </div>
    </div>
@endforeach
