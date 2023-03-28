@extends('front.layout.main')
@section('content')
    <?php
    use App\Models\Favourite;
    ?>
    <div id="carforsale">
        <section class="section-space" id="filtersec">
            <div class="container-xl">
                <div class="row mb-4">
                    <div class="align-self-center col-lg-1 col-sm-2 mb-3 mb-lg-0 text-center">
                        <div class="db-top">
                            <img src="{{ $showroom->logo }}" class="review-showroom-thumb">
                        </div>
                    </div>
                    <div class="align-self-center col-lg-4 col-sm-4 pe-0 text-sm-start text-center">
                        <div class="db-body">
                            <h5>{{ $showroom->name }}
                                @if ($showroom->verified == 'YES')
                                    <img src="{{ URL::asset('front/images/green-small-tick.png') }}">
                                @endif
                            </h5>
                            <h5>Owner Name : {{ $showroom->user->first_name .' '. $showroom->user->last_name }}</h5>
                            <h5>Phone Number : {{ $showroom->user->business_phone_number }}</h5>
                            {{-- <span class="me-2">Year of service : <b>{{date('Y', strtotime('now')) -
                                date('Y', strtotime($showroom->updated_at))}}</b></span> --}}
                            <span class="me-2">Year of service : <b>{{ $showroom->service_year }}</b></span>
                            <div class="justify-content-sm-start mb-2 rating justify-content-center">
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i < $showroom->rating)
                                        <span class="fa fa-star checked"></span>
                                    @else
                                        <span class="fa fa-star"></span>
                                    @endif
                                @endfor
                            </div>
                            <div>{{ $showroom->getCountReviewAttribute() }} Reviews</div>
                            <div>{{ $showroom->address }}</div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-sm-6">
                        <div class="align-items-center align-items-md-end d-flex flex-column h-100 justify-content-between">

                            <span class="mb-2 mb-sm-0">Member Since :
                                {{ date('Y', strtotime($showroom->updated_at)) }}</span>
                            <a href="{{ route('showroom-reviews-detail', $showroom->id) }}"
                                class="btn btn-danger d-flex align-items-center justify-content-center flex-column">
                                <b>Add a Review</b>
                                <small class="d-block">(Share your experience)</small>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <div class="filterhead d-flex align-items-center">
                            <h2 class="m-0">Search cars by</h2>
                            <img src="{{ URL::asset('front/images/filticon.png') }}" class="ms-auto">
                        </div>

                        <div class="expandbox">
                            <div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#keywords">
                                <h3 class="m-0">Search by keywords</h3>
                                <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
                            </div>
                            <div class="collapse exbody show" id="keywords">
                                <div class="innerbody">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="e.g. Honda"
                                            name="filter_keyword" id="filter_keyword">
                                        <a href="javascript:Search()">
                                        <div class="input-group-text" id="btnGroupAddon2">GO</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-xl-9 col-lg-8 col-md-7">


                        <div class="sort-header mb-4">
                            <div class="d-flex align-items-center">
                                <label class="me-3">Sort By</label>
                                <div class="postad-select w-50 select-all">
                                    <select>
                                        <option value="price_range|asc" selected>Price: Low to High</option>
                                        <option value="price_range|desc">Price: High to Low</option>
                                        <option value="rating|desc">Rating: High to Low</option>
                                        <option value="rating|asc">Rating: Low to High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="btn-group ms-auto sort-btngroup" role="group"
                                aria-label="Basic radio toggle button group" style="display: none;">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio1"> <i class="fa fa-list me-2"></i>
                                    List</label>
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                                    checked>
                                <label class="btn btn-outline-primary" for="btnradio2"> <i class="fa fa-th-large me-2"></i>
                                    Grid</label>
                            </div>
                        </div>


                        <div class="row mb-5" id="showroom-cars">
                            @include('front.showroom-detail.showroom-detail-cars', [
                                'cars' => $cars,
                            ])
                        </div>

                        <input type="hidden" name="select_car_orders" id="select_car_orders" value="price_range|desc" />

                    </div>
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

        function Search() {
            PreLoader();
            SearchFilterMain();
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
                PreLoader();
                $("#select_car_orders").val($(this).attr('rel'));
                SearchFilterMain();
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

        function car_detail($car_id) {
            window.location.href = "{{ route('car-detail', '') }}/" + $car_id;
        }

        function SearchFilterMain() {
        var search_cars_filter_keword_val = $("#filter_keyword").val();
        var select_car_orders_val = $('#select_car_orders').val();
        var showroom_id = "{{$showroom->id}}";

        $.ajax({
        url: '{{ route('filterapi.showroom-detail-cars-by-view') }}',
        type: 'POST',
        data: {
        search_cars_filter_keword_val: search_cars_filter_keword_val,
        select_car_orders_val: select_car_orders_val,
        showroom_id: showroom_id,

        _token: '{{ csrf_token() }}'
        },
        success: function(data) {
        if ($.isEmptyObject(data.error)) {

        $('#showroom-cars').html(data.html);
        PreLoader("hide");

        }
        },
        error: function(error) {
        console.log(error);
        }
        });
        }

        function FavouriteMethod(id) {
            var selectedElement = $("#heart_" + id);
            if (!selectedElement.hasClass("text-danger")) {
                Favourite(id);
            } else {
                UnFavourite(id);
            }
        }

        function Favourite(id)
        {
            PreLoader();
            $.ajax({
                url: "{{ route('ad-favourite', '') }}/" + id,
                type: 'GET',

                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        if (data.result == 1) {
                            $("#heart_" + data.data).addClass("text-danger");
                            ShowToaster('Success', data.message);
                            PreLoader("hide");
                        } else {
                            ShowToaster('Error', data.message);
                            PreLoader("hide");
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });
        }

        function UnFavourite(id) {
            PreLoader();
            $.ajax({
                url: '{{ route('ad-unfavourite', '') }}/' + id,
                type: 'GET',

                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        if (data.result == 1) {
                            $("#heart_" + data.data).removeClass("text-danger");
                            ShowToaster('Success', data.message);
                            PreLoader("hide");
                        } else {
                            ShowToaster('Error', data.message);
                            PreLoader("hide");
                        }

                    }
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });
        }
    </script>
@endsection
