@extends('front.layout.main')
@section('content')
    <style>
        hr:last-child {
            display: none;
        }
    </style>
    <section class="section-space mt-80px write-review">
        <div class="container-xl">
            <h2 class="mb-5">Write a Review</h2>
            <div class="review-box">
                <h5 class="mb-5 text-center text-lg-start">Car Information</h5>
                <form id="add_car_review" action="{{ action('Web\FrontController@CarAddReviewStore') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="row">


                        <div class="col-sm-8 offset-2">
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Model Year <sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="year" name="year" class="form-control select-simple">
                                        @foreach ($data['years'] as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Make <sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="make" name="make" class="form-control select-simple">
                                        <option value="" selected disabled>Select Make</option>
                                        @foreach ($data['brands'] as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Model <sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="model" name="model" class="form-control select-simple">
                                        {{-- @foreach ($data['models'] as $model)
                                    <option value="{{$model->id}}">{{$model->name}}</option>
                                    @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Version</label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="version" name="version" class="form-control select-simple">
                                        {{-- @foreach ($data['versions'] as $version)
                                    <option value="{{$version->id}}">{{$version->name}}</option>
                                    @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Exterior Color <sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="color" name="color" class="form-control select-simple">
                                        @foreach ($data['colors'] as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-5 text-center text-lg-start">Your Opinion</h5>
                    <div class="row">
                        <div class="col-sm-8 offset-2">
                            <div class="row mb-3">
                                <div class="col-lg-3 text-lg-end">
                                    <label class="mb-3 mb-lg-0">Rating<sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="hidden" name="exterior" id="exterior" value="{{ old('exterior') }}" />
                                    <input type="hidden" name="interior" id="interior" value="{{ old('interior') }}" />
                                    <input type="hidden" name="comfort" id="comfort" value="{{ old('comfort') }}" />
                                    <input type="hidden" name="fuel" id="fuel" value="{{ old('fuel') }}" />
                                    <input type="hidden" name="performance" id="performance"
                                        value="{{ old('performance') }}" />
                                    <input type="hidden" name="parts" id="parts" value="{{ old('parts') }}" />
                                    <div class="row mb-3">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Exterior</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start exterior-rating">
                                                <span class="fa fa-star {{ old('exterior') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('exterior') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('exterior') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('exterior') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('exterior') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Interior</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start interior-rating">
                                                <span class="fa fa-star {{ old('interior') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('interior') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('interior') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('interior') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('interior') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Comfort</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start comfort-rating">
                                                <span class="fa fa-star {{ old('comfort') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('comfort') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('comfort') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('comfort') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('comfort') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Fuel economy</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start fuel-rating">
                                                <span class="fa fa-star {{ old('fuel') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('fuel') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('fuel') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('fuel') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('fuel') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Performance</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start performance-rating">
                                                <span class="fa fa-star {{ old('performance') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('performance') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('performance') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('performance') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('performance') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                                            <span>Parts availability</span>
                                        </div>
                                        <div class="col-xxl-6 col-lg-7">
                                            <div class="rating mb-2 justify-content-start parts-rating">
                                                <span class="fa fa-star {{ old('parts') >= 1 ? 'checked' : '' }}"
                                                    data-value="1"></span>
                                                <span class="fa fa-star {{ old('parts') >= 2 ? 'checked' : '' }}"
                                                    data-value="2"></span>
                                                <span class="fa fa-star {{ old('parts') >= 3 ? 'checked' : '' }}"
                                                    data-value="3"></span>
                                                <span class="fa fa-star {{ old('parts') >= 4 ? 'checked' : '' }}"
                                                    data-value="4"></span>
                                                <span class="fa fa-star {{ old('parts') >= 5 ? 'checked' : '' }}"
                                                    data-value="5"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4 align-items-center">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                                    <label>Review Title<sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="text" id="title" name="title" class="form-control"
                                        value="{{ old('title') }}">
                                    <input hidden type="text" id="user_id" name="user_id" class="form-control"
                                        value="{{ Session('user')->id }}">
                                </div>
                            </div>
                            <div class="row mb-5 ">
                                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0 mt-1">
                                    <label>Description<sup class="required">*</sup></label>
                                </div>
                                <div class="col-lg-9">
                                    <textarea class="form-control" id="description" name="description" value="{{ old('description') }}"
                                        rows="10"></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary submit-btn" onclick="Submit(event)">Submit Review</button>
                            </div>
                        </div>
                    </div>
                </form>
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



        $(document).on('click', '.exterior-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            selectRatingStars(stars, onStar);
            $('#exterior').val(onStar);
            console.log(onStar);

        });

        $(document).on('click', '.interior-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            console.log(stars);
            selectRatingStars(stars, onStar);
            $('#interior').val(onStar);
            console.log(onStar);

        });
        $(document).on('click', '.comfort-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            console.log(stars);
            selectRatingStars(stars, onStar);
            $('#comfort').val(onStar);
            console.log(onStar);

        });

        $(document).on('click', '.fuel-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            console.log(stars);
            selectRatingStars(stars, onStar);
            $('#fuel').val(onStar);
            console.log(onStar);

        });

        $(document).on('click', '.performance-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            console.log(stars);
            selectRatingStars(stars, onStar);
            $('#performance').val(onStar);
            console.log(onStar);

        });

        $(document).on('click', '.parts-rating span', function(e) {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('span.fa-star');
            console.log(stars);
            selectRatingStars(stars, onStar);
            $('#parts').val(onStar);
            console.log(onStar);

        });

        function selectRatingStars(stars, onStar) {
            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('checked');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('checked');
            }
        }

        function Submit(e) {
            e.preventDefault();
            if ($("#year").val() == "") {
                ShowToaster("Error", "Enter Year");
                return;
            }

            if ($("#make").val() == "") {
                ShowToaster("Error", "Enter Make");
                return;
            }

            if ($("#model").val() == "") {
                ShowToaster("Error", "Enter Model");
                return;
            }
            if ($("#version").val() == "") {
                ShowToaster("Error", "Enter Version");
                return;
            }
            if ($("#color").val() == "") {
                ShowToaster("Error", "Enter Color");
                return;
            }
            if ($("#exterior").val() == "") {
                ShowToaster("Error", "Enter Exterior Rating");
                return;
            }
            if ($("#interior").val() == "") {
                ShowToaster("Error", "Enter Interior Rating");
                return;
            }
            if ($("#comfort").val() == "") {
                ShowToaster("Error", "Enter Comfort Rating");
                return;
            }
            if ($("#fuel").val() == "") {
                ShowToaster("Error", "Enter Fuel Economy Rating");
                return;
            }
            if ($("#performance").val() == "") {
                ShowToaster("Error", "Enter Performance Rating");
                return;
            }
            if ($("#parts").val() == "") {
                ShowToaster("Error", "Enter Parts Availabilty Rating");
                return;
            }
            if ($("#title").val() == "") {
                ShowToaster("Error", "Enter Review Title");
                return;
            }
            if ($("#description").val() == "") {
                ShowToaster("Error", "Enter Description");
                return;
            }
            $("#add_car_review").submit();


        }

        $(document).ready(function() {
            $("#make").change(function() {
                var make_id = $("#make").val();
                PreLoader();
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
            var car_year = $("#year").val();
            PreLoader();
            $.ajax({
            url: '{{ route('webapi.versions') }}',
            type: 'POST',
            data: {
            model_id: model_id,
            year_model: car_year,
            _token: '{{csrf_token()}}'
            },
            success: function(data) {
            if (data.result == 1) {
            var html = '';
            html += '<option value="" disabled selected>Select Version</option>';
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
        });
    </script>
@endsection
