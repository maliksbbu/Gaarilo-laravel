
<?php
use App\Models\CarOffer;
    ?>
@foreach ($cars as $car)
    @if ($view_type == 'Received')
        <a href="{{ route('offer.details', $car->id) }}">
            <div class="detail-box listview mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="db-top">
                            <img src="{{ $car->image }}" class="w-100 h-100" style="rotate:{{$car->rotation}}deg;">
                            <div class="imgcounter">
                                <span class="me-2">{{ $car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count()  }}</span>
                                <img src="{{ URL::asset('front/images/images-icon.png') }}">
                            </div>
                            {{-- <div class="dbprice">
                                <h5 class="m-0">PKR. 50,00000</h5>
                                <p class="m-0">Showroom offer</p>
                            </div> --}}
                            {{-- <div class="vdo-icon">
                    <i class="fa fa-play"></i>
                </div> --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="db-body">
                            <h5 class="m-0 mb-1 mt-2 mt-lg-0">
                                {{ $car->brand->name }} {{ $car->model->name }} {{ $car->car_year }}</h5>
                            <h6 class="mb-2 cityname">{{ $car->city->name }}</h6>
                            <div class="m-0 mb-1 sepreator-text d-flex flex-wrap">
                                <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                                <div><span class="mx-3 ps-2"> {{ $car->mileage }} km </span> </div>
                                <div>|<span class="ms-3">{{ $car->engine_type }} </span></div>
                            </div>
                            <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
                                <div></div>
                                <div><span class="me-3">{{ $car->engine_capacity }} cc </span> |</div>
                                <div><span class="ms-3">{{ $car->transmission }}</span></div>
                            </div>
                            <div class="text-muted">Last Updated:
                                {{ date('m/d/Y', strtotime($car->updated_at)) }}</div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex flex-column h-100 justify-content-lg-between">
                            <div class="text-end mb-2 mb-xl-0">
                                <h3 class="m-0 mb-2">PKR. {{ number_format($car->price_range) }}</h3>
                            </div>
                            <button class="btn btn-danger ms-auto btn-offer"><i
                                    class="fa fa-exclamation-circle fa-lg"></i>
                                {{ $car->CountPendingOffer() }} New Offers</button>

                        </div>
                    </div>
                </div>

            </div>
        </a>
    @endif

    @if ($view_type == 'Sent')
        <div class="detail-box listview mb-4">
            <div class="d-flex align-items-center flex-wrap">
                <div class="db-top me-2">
                    <img src="{{ $car->image }}" class="w-100 h-100" style="rotate:{{$car->rotation}}deg;">
                    <div class="imgcounter">
                        <span class="me-2">{{ $car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count()  }}</span>
                        <img src="{{ URL::asset('front/images/images-icon.png') }}">
                    </div>
                    {{-- <div class="dbprice">
                        <h5 class="m-0">PKR. 50,00000</h5>
                        <p class="m-0">Showroom offer</p>
                    </div> --}}
                    {{-- <div class="vdo-icon">
                        <i class="fa fa-play"></i>
                    </div> --}}
                </div>
                <div class="db-body">
                    <h5 class="m-0 mb-1 mt-2 mt-lg-0">
                        {{ $car->brand->name }} {{ $car->model->name }} {{ $car->car_year }}</h5>
                    <h6 class="mb-2 cityname">{{ $car->city->name }}</h6>
                    <div class="m-0 mb-1 sepreator-text d-flex flex-wrap">
                        <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                        <div><span class="mx-3 ps-2"> {{$car->mileage}}</span> </div>
                        <div>|<span class="ms-3">{{$car->engine_type}}</span></div>
                    </div>
                    <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
                        <div></div>
                        <div><span class="me-3">{{$car->engine_capacity}}cc </span> |</div>
                        <div><span class="ms-3">{{$car->transmission}}</span></div>
                    </div>
                    <div class="text-muted">Last Updated: {{ date('m/d/Y', strtotime($car->updated_at)); }}</div>

                </div>
                <div class="d-flex flex-column h-100 justify-content-lg-between ms-auto">
                    <div class="text-end mb-3">
                        <h3 class="mb-1 text-primary">PKR. {{ number_format($car->price_range) }}</h3>

                        <?php
                            $carOffer = CarOffer::GetUserOfferOnCar($car->id, Session::get('user')->id);
                        ?>
                        <h5 class="mb-1 text-danger">Your offer PKR. {{ number_format($carOffer->amount) }}</h5>
                        @if($carOffer->counter_amount != "")  <h6 class="m-0 text-primary fw-semibold">Counter Offer: PKR  {{number_format($carOffer->counter_amount)}}  </h6>@endif
                    </div>

                    <div class="d-flex align-items-center justify-content-end">
                        <div class="offers-rounded-img me-2">
                            <img src="{{$car->showroom->logo}}" class="h-100">
                        </div>
                        <div>
                            <a class="fw-semibold text-black text-decoration-underline" href="{{route('showroom-detail', $car->showroom->id)}}">{{$car->showroom->name}}</a>
                            <p class="m-0">{{$car->showroom->phone}}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach
