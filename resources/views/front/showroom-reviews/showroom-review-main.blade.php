@extends('front.layout.main')
@section('content')
    <style>
        hr:last-child {
            display: none;
        }
    </style>
    <?php
    use App\Models\CarOffer;
    ?>

    <section class="section-space py-5 review-header">
        <div class="container-xl">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <img src="{{ $showroom->logo }}" class="review-showroom-thumb">
                <h3 class="mb-0">{{ $showroom->name }}</h3>
                <p class="mb-0">Year of service : {{ $showroom->service_year }}</p>
                <p class="mb-0">Member since :
                    {{ date('Y', strtotime($showroom->updated_at)) }}</p>
                <div class="rating mb-2 justify-content-start my-3">
                    <?php echo return_rating_html($showroom->rating); ?>
                </div>
                <span class="reviews mb-5">{{ $showroom->getCountReviewAttribute() }} Reviews</span>

                @if (!Session::has('user'))
                    <a class="btn btn-danger d-flex align-items-center justify-content-center"
                        onclick="ShowToaster('Error', 'Sign-in First to place review');">
                        <b>Add a Review</b>
                        <small class="d-block">(Share your experience)</small>
                    </a>
                @elseif(user_id_in_showroom($showroom->id, Session::get('user')->id))
                    <a class="btn btn-danger d-flex align-items-center justify-content-center"
                        onclick="ShowToaster('Error', 'You have already reviewed this showroom');">
                        <b>Add a Review</b>
                        <small class="d-block">(Share your experience)</small>
                    </a>
                @elseif(Session::get('user')->id == $showroom->user_id)
                    <a></a>
                @else
                    <a href="{{ route('showroom-review', $showroom->id) }}"
                        class="btn btn-danger d-flex align-items-center justify-content-center">
                        <b>Add a Review</b>
                        <small class="d-block">(Share your experience)</small>
                    </a>
                @endif

            </div>
        </div>
    </section>
    <section id="top-pics" class="section-space pb-3">
        <div class="container-xl">
            <h2 class="reviewheading mb-4">Cars For Sale by {{ $showroom->name }}</h2>

            <div class="showroom-scroll">
                <div class="row">
                    @foreach ($cars as $car)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="detail-box">
                                <div class="db-top" onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                    <img src="{{ $car->image }}" class="w-100" style="rotate:{{$car->rotation}}deg;">
                                    <div class="imgcounter">
                                        <span
                                            class="me-2">{{ $car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count() }}</span>
                                        <img src="{{ URL::asset('front/images/images-icon.png') }}">
                                    </div>
                                    @if (Session::has('user'))
                                        <?php $response = CarOffer::OfferOnCar(Session::get('user')->id, $car->id); ?>

                                        @if (count((array) $response) != 0)
                                            @switch($response['color'])
                                                @case('blue')
                                                    <div class="dbprice bg-blue" onclick="car_detail({{ $car->id }})"
                                                        style="cursor: pointer">
                                                        <h5 class="m-0">PKR. {{ number_format($response['price']) }}</h5>
                                                        <p class="m-0">{{ $response['message'] }}</p>
                                                    </div>
                                                @break

                                                @case('green')
                                                    <div class="dbprice" onclick="car_detail({{ $car->id }})"
                                                        style="cursor: pointer; background-color: green">
                                                        <h5 class="m-0">PKR. {{ number_format($response['price']) }}</h5>
                                                        <p class="m-0">{{ $response['message'] }}</p>
                                                    </div>
                                                @break

                                                @case('red')
                                                    <div class="dbprice" onclick="car_detail({{ $car->id }})"
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


                                <div style="overflow: hidden; text-overflow:ellipsis; white-space:nowrap; width:238px">
                                    <h5 class="m-0 mb-3" onclick="car_detail({{ $car->id }})"
                                        style="cursor: pointer;">
                                        {{ car_name($car->id) }}
                                    </h5>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <h3 class="m-0" onclick="car_detail({{ $car->id }})"
                                            style="cursor: pointer;">PKR. {{ number_format($car->price_range) }}</h3>
                                        <a href="{{ url('showroom-detail/' . $car->showroom_id) }}" style="cursor: pointer;"
                                            class="ms-auto"><img
                                                src="@if ($car->showroom != null) {{ $car->showroom->logo }} @endif"
                                                class="ms-auto pkrimg"></a>
                                    </div>
                                    <h6 class="mb-3" onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                        {{ $car->city->name }}</h6>

                                    <div class="m-0 mb-2 sepreator-text d-flex flex-wrap"
                                        onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                        <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                                        <div><span class="mx-3 ps-2"> {{ $car->mileage }} km </span> </div>
                                        <div>|<span class="ms-3">{{ $car->engine_type }}</span></div>
                                    </div>
                                    <div class="m-0 mb-3 sepreator-text d-flex flex-wrap"
                                        onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                        <div></div>
                                        <div><span class="me-3">{{ $car->engine_capacity }}cc </span> |</div>
                                        <div><span class="ms-3">{{ $car->transmission }}</span></div>
                                    </div>
                                    <div class="mb-3 text-muted mb-4">Last Updated:
                                        {{ date('m/d/Y', strtotime($car->updated_at)) }}
                                    </div>

                                    <button id="b_id{{ $car->id }}"
                                        @if ($car->showroom != null) value="{{ $car->showroom->user->business_phone_number ? $car->showroom->user->business_phone_number : $car->showroom->user->phone }}" onclick="change_text_to_phone({{ $car->id }})" @endif
                                        class="btn btn-success w-100">
                                        <i class="fa fa-phone-alt me-2"></i>
                                        <span id="change_text{{ $car->id }}">Show Phone No.</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container-xl">
            <div class="recent-reviews">

                <h3 class="mb-4 reviewheading">Recent Reviews</h3>
                @if (count($showroom->showroomreviews) > 0)
                    @foreach ($showroom->showroomreviews as $reviews)
                        <div class="d-flex align-items-center mb-3">
                            <div class="reviewer-img me-3">
                                <img src="{{ URL::asset('front/images/logavatar.png') }}">
                            </div>
                            <div class="reviewer-detail">
                                <h3 class="m-0">{{ $reviews->user->first_name }} {{ $reviews->user->last_name }}</h3>
                                <p class="m-0"><b>Member since:
                                        {{ date('Y', strtotime($reviews->user->created_at)) }}</b>
                                </p>
                                <p class="m-0">Posted on
                                    {{ date('M d, Y', strtotime($reviews->created_at)) }}
                                </p>
                            </div>
                        </div>
                        <div class="rating mb-3 justify-content-start">
                            <?php echo return_rating_html(round($reviews->review_rating)); ?>
                        </div>

                        <h4>{{ $reviews->review_title }}</h4>
                        <p>{{ $reviews->review_description }}</p>

                        <div class="d-flex align-items-center flex-wrap">
                            @if (count($reviews->reviewsratings) > 0)
                                @foreach ($reviews->reviewsratings as $ratings)
                                    <div class="d-flex align-items-center me-5 mb-3">
                                        <h4 class="m-0 me-2"><?php echo showroom_rating_name($ratings->rating_type); ?></h4>
                                        <div class="rating justify-content-start">
                                            <?php echo return_rating_html(round($ratings->rating_value)); ?>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>


                        <hr class="my-4">
                    @endforeach
                @else
                    <h6>No Reviews Yet</h6>
                @endif

            </div>
        </div>
    </section>

    <script>
        function car_detail($car_id) {
            window.location.href = "{{ '/car-detail/' }}" + $car_id;
        }

        function change_text_to_phone($car_id) {
            var $phone_number = document.getElementById('b_id' + $car_id).value;
            var x = document.getElementById("change_text" + $car_id);
            if (x.innerHTML === "Show Phone No.") {
                x.innerHTML = $phone_number;
            } else {
                x.innerHTML = "Show Phone No.";
            }
        }

        // function showroom_button_click($car_id) {
        //     window.location.href = "{{ '/car-detail/' }}" + $car_id;
        // }
    </script>

@endsection
