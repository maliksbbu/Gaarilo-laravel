@extends('front.layout.main')
@section('content')
    <style>
        hr:last-child {
            display: none;
        }
    </style>

    <?php use App\Models\CarOffer; ?>

    <section class="section-space py-5 review-header">
        <div class="container-xl">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <h3 class="mb-2">Car Reviews</h3>
                <p class="mb-5">Explore user ratings and read opinions</p>

                <div class="row w-100">
                    <div class="col-sm-3">
                        <select class="form-control select-simple" id="make" name="make">
                            <option value="" selected disabled>Select Make</option>
                            @foreach ($data['brands'] as $make)
                                <option value="{{ $make->id }}">{{ $make->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control select-simple" id="model" name="model">
                            {{-- <option value="" selected disabled>Modal</option>
              @foreach ($data['models'] as $model)
              <option value="{{$model->id}}">{{$model->name}}</option>
              @endforeach --}}
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-success w-100" onclick="Search()">
                            <b>Search</b>
                        </button>
                    </div>
                    <div class="col-sm-3">
                        @if (Session::has('user'))
                            <a href="{{ route('car-add-review') }}" class="btn btn-danger w-100">
                            @else
                                <a href="javascript:ShowToaster('Error', 'Login to proceed');" class="btn btn-danger w-100">
                        @endif
                        <b>Write Review</b>
                        <small class="d-block">(Share your experience)</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="top-pics" class="section-space pb-3">
        <div class="container-xl">
            <h2 class="reviewheading mb-4">Cars For Sale In Pakistan</h2>

            <div class="row">
                @foreach ($data['cars'] as $car)
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="detail-box">
                            <div class="db-top">
                                <img src="{{ $car->image }}" class="w-100" onclick="car_detail({{ $car->id }})"
                                    style="cursor: pointer;rotate:{{$car->rotation}}deg;">
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
                                @if ($car->video)
                                    <a href="javascript:OpenEditModal({{ $car->id }})">
                                        <div class="vdo-icon">
                                            <i class="fa fa-play"></i>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="db-body">
                                <h5 class="m-0 mb-3" style="cursor: pointer;" title="{{car_name($car->id)}}">
                                    <div style="overflow:hidden; text-overflow:ellipsis; width:275px; white-space:nowrap;">
                                        {{ car_name($car->id) }}
                                    </div>

                                </h5>
                                <div class="d-flex align-items-center mb-3" onclick="car_detail({{ $car->id }})"
                                    style="cursor: pointer;">
                                    <h3 class="m-0">PKR. {{ number_format($car->price_range) }}</h3>
                                    <a target="_blank" href="{{ url('showroom-detail/' . $car->showroom_id) }}"
                                        style="cursor: pointer;" class="ms-auto"><img
                                            src="@if ($car->showroom != null) {{ $car->showroom->logo }} @endif"
                                            class="pkrimg"></a>
                                </div>
                                <h6 class="mb-3" onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                    {{ city_name($car->city_id) }}</h6>
                                <div class="m-0 mb-2 sepreator-text d-flex flex-wrap"
                                    onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                    <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                                    <div><span class="mx-3 ps-2">{{ $car->mileage }} km </span> </div>
                                    <div>|<span class="ms-3">{{ $car->engine_type }}</span></div>
                                </div>
                                <div class="m-0 mb-3 sepreator-text d-flex flex-wrap"
                                    onclick="car_detail({{ $car->id }})" style="cursor: pointer;">
                                    <div></div>
                                    <div><span class="me-3">{{ $car->engine_capacity }} cc </span> |</div>
                                    <div><span class="ms-3">{{ $car->transmission }}</span></div>
                                </div>
                                <?php $new_date = strtotime($car->updated_at); ?>
                                <div class="mb-3 text-muted mb-4" onclick="car_detail({{ $car->id }})"
                                    style="cursor: pointer;">Last Updated: {{ date('d-m-Y', $new_date) }}</div>
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
    </section>
    <section class="recent-car-reviews section-space">
        <div class="container-xl">

            <div class="recent-reviews">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mb-4 reviewheading">Recent Car Reviews</h3>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex ms-auto align-items-center">
                            <label class="me-3 white-space-nowrap">Select Variant:</label>
                            <select class="form-control" id="version">
                                <option value="" selected>All Versions</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="reviews">
                    @include('front.search-cars.car-review-inner-list', [
                        'car_reviews' => $data['car_reviews'],
                    ])
                </div>

            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <!-- <script src="scss/js/dropdown.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="scss/js/custom.js"></script>

    <script>
        /*
        Reference: http://jsfiddle.net/BB3JK/47/
        */


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

        function OpenEditModal(id) {

            $("#edit-vtype-popup_" + id).modal("show");
        }

        function closemodel(id) {
            $("#edit-vtype-popup_" + id).modal("hide");
        }

        $(document).ready(function() {
            $("#make").change(function() {
                PreLoader();
                var make_id = $("#make").val();
                $.ajax({
                    url: '{{ route('webapi.models') }}',
                    type: 'POST',
                    data: {
                        brand_id: make_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.result == 1) {
                            var html = '';
                            html += '<option value="" disabled selected>Select Model</option>';
                            data.data.forEach(element => {
                                html += '<option value="' + element.id + '">' + element
                                    .name + '</option>'
                            });
                            $("#model").html('');
                            $("#version").html('');
                            $("#model").html(html);
                            PreLoader("hide");

                        } else {
                            ShowToaster("Error", data.message);
                            PreLoader("hide");
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        PreLoader("hide");
                    }
                });
            });

            $("#model").change(function() {
                var model_id = $("#model").val();
                PreLoader();
                $.ajax({
                    url: '{{ route('webapi.versions.all') }}',
                    type: 'POST',
                    data: {
                        model_id: model_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.result == 1) {
                            var html = '';
                            html +=
                                '<option value="" selected>All Versions</option>';
                            data.data.forEach(element => {
                                html += '<option value="' + element.id + '">' + element
                                    .name + '</option>'
                            });
                            $("#version").html('');
                            $("#version").html(html);
                            PreLoader("hide");

                        } else {
                            ShowToaster("Error", data.message);
                            PreLoader("hide");
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        PreLoader("hide");
                    }
                });
            });

            $("#version").change(function() {
                var brand_id = $("#make").val();
                var model_id = $("#model").val();
                var version_id = $("#version").val();

                if (brand_id == null || model_id == null) {
                    ShowToaster("Error", "select both make and model");
                    return;
                }

                PreLoader();
                $.ajax({
                    url: '{{ route('webapi.dynamic.reviews') }}',
                    type: 'POST',
                    data: {
                        brand_id: brand_id,
                        model_id: model_id,
                        version_id: version_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $("#reviews").html(data.reviews);
                        PreLoader("hide");
                    },
                    error: function(error) {
                        console.log(error);
                        PreLoader("hide");
                    }
                });
            });
        });

        function Search() {
            var brand_id = $("#make").val();
            var model_id = $("#model").val();
            var version_id = $("#version").val();

            if (brand_id == null || model_id == null) {
                ShowToaster("Error", "select both make and model");
                return;
            }

            PreLoader();
            $.ajax({
                url: '{{ route('webapi.dynamic.reviews') }}',
                type: 'POST',
                data: {
                    brand_id: brand_id,
                    model_id: model_id,
                    version_id: version_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $("#reviews").html(data.reviews);
                    PreLoader("hide");
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });

        }
    </script>
@endsection
