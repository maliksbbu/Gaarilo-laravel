@extends('front.layout.main')
@section('content')
    <form action="{{ route('search-cars') }}" method="GET">
        <section id="banner" class="d-flex align-items-center">
            <div class="container-xl">
                <div class="banner-content text-center">
                    <h1>ACCESS YOUR NEARBY SHOWROOM AND FIND YOUR FAVOURITE CAR </h1>
                    <p class="mb-4 mb-lg-5">A better way to BUY and SELL car </p>
                    <div class="banner-fields">
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <div class="custom-select">
                                    <select class="js-example-basic-single" name="showroom" id="showroom">
                                        <option value="0" selected>All Showrooms</option>
                                        @foreach ($showrooms as $showroom)
                                            <option value="{{ $showroom->id }}">{{ strtoupper($showroom->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-img"> <img src="{{ URL::asset('front/images/showroom.png') }}"> </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <div class="custom-select">
                                    <select class="js-example-basic-single" name="vehicle_type">
                                        <option value="0" selected>All Vehicle Type</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-img"> <img src="{{ URL::asset('front/images/vtype.png') }}"> </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <div class="custom-select">
                                    <select class="js-example-basic-single" name="city">
                                        <option value="0" selected>All Cities</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ strtoupper($city->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-img"> <img src="{{ URL::asset('front/images/city.png') }}"> </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mb-lg-5">
                                <div class="custom-select">
                                    <select class="js-example-basic-single" name="price_range">
                                        <option value="" selected disabled>Price Range</option>
                                        <option value="0">All Prices</option>
                                        <option value="1000000">10 Lac</option>
                                        <option value="2000000">20 Lac</option>
                                        <option value="3000000">30 Lac</option>
                                        <option value="4000000">40 Lac</option>
                                        <option value="5000000">50 Lac</option>
                                        <option value="6000000">60 Lac</option>
                                        <option value="7000000">70 Lac</option>
                                        <option value="8000000">80 Lac</option>
                                        <option value="9000000">90 Lac</option>
                                        <option value="10000000">1 Cr</option>
                                        <option value="15000000">1.5 Cr</option>
                                        <option value="20000000">2 Cr</option>
                                        <option value="25000000">2.5 Cr</option>
                                        <option value="30000000">3 Cr</option>
                                        <option value="35000000">3.5 Cr</option>
                                        <option value="40000000">4 Cr</option>
                                        <option value="45000000">4.5 Cr</option>
                                        <option value="50000000">5 Cr</option>
                                        <option value="500000000">5 Cr Above</option>
                                    </select>
                                    <div class="field-img"> <img src="{{ URL::asset('front/images/prange.png') }}"> </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 mb-lg-0">
                                <button class="btn btn-primary w-100" type="submit">Search</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-more w-100"
                                    onclick="RedirectToURL('{{ route('search-cars') }}');">More Filters &nbsp;<img
                                        src="{{ URL::asset('front/images/long-arrow.png') }}" class="ml-2"> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    <section id="sellbuy-section" class="section-space">
        <div class="container-xl">
            <div class="inner-content">
                <h1 class="sellbuy-heading">NOW WITH GAARILO</h1>
                <div class="row">
                    <div class="col-lg-6 border-r mb-4 mb-lg-0">
                        <h4>SELL LIKE A PRO</h4>
                        <ul class="p-0">
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> MAKE YOUR PERSONAL VIRTUAL
                                SHOWROOM</li>
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> GET OFFERS FROM MILLIONS OF
                                VERIFIED BUYERS</li>
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> A FASTER WAY TO SELL YOUR CAR AT
                                BEST PRICE </li>
                        </ul>
                        @if (empty($user))
                            <div class="text-center">
                                <button onclick="window.location='signup';" class="btn btn-primary">REGISTER NOW</button>
                            </div>
                        @else
                            <div class="text-center">
                                <a href="{{ url('my-showroom-details', Session('user')->showroom->id) }}"><button
                                        class="btn btn-primary">My Showroom</button></a>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6 ps-0 ps-lg-5">
                        <h4>BUY WITH SATISFACTION</h4>
                        <ul class="p-0">
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> FIND TRUSTED SHOWROOMS NEAR YOU
                            </li>
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> SEND OFFER ON YOUR FAVOURITE CAR
                            </li>
                            <li><img src="{{ URL::asset('front/images/smallcar.png') }}"> BUY YOUR DREAM CAR WITH
                                SATISFACTION </li>
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('search-showrooms', ['nearby' => 'yes']) }}"><button
                                    class="btn btn-primary">NEARBY SHOWROOMS</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="trending-news" class="section-space mb-5">
        <div class="container-xl  position-relative">
            <h2 class="mb-5">Trending News</h2>
            <div class="owl-carousel owl-theme tn-carausel">
                @foreach ($news as $new)
                    <div class="item">
                        <h5>{{ date('h:i A, M d', strtotime($new->created_at)) }}</h5>
                        <p>{{ $new->text }}</p>
                    </div>
                @endforeach
            </div>
            <div class="treding-car"> <img src="{{ URL::asset('front/images/tcar.png') }}"> </div>
        </div>
    </section>
    <section id="popular-showrooms" class="section-space">
        <div class="container-xl">
            <h2 class="heading mb-5">Popular <span>Showrooms</span></h2>
            <div class="owl-carousel owl-theme ps-carausel mb-4">
                @foreach ($showrooms as $showroom)
                    <div class="item">
                        <div class="showroom-box">  @if($showroom->logo) <img src="{{ $showroom->logo }}" class="img-fluid overflow-hidden rounded-circle"> @else  <img src="{{ URL::asset('front/images/sr-img1.png') }}" class="img-fluid overflow-hidden rounded-circle"> @endif
                             <div style="overflow: hidden;text-overflow: ellipsis;width: 250px;white-space: nowrap;">
                            <h5>{{strtoupper($showroom->name) }}</h5> </div>
                            <div class="rating mb-2"> <span
                                    class="me-3">{{ number_format($showroom->rating, 1) }}</span>
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i < $showroom->rating)
                                        <span class="fa fa-star checked"></span>
                                    @else
                                        <span class="fa fa-star"></span>
                                    @endif
                                @endfor
                            </div>
                            <h3 class="mb-3">{{ $showroom->city->name }}</h3>
                            <hr>
                            <p class="mb-2"> {{ $showroom->address }} </p>
                            <h4>
                                {{ $showroom->GetNumberSoldCars() }} Sold Cars
                            </h4>
                            <button class="btn btn-primary w-100"
                                onclick="RedirectToURL('{{ route('showroom-detail', $showroom->id) }}')">Visit
                                Showroom</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                <button class="btn btn-default" onclick="RedirectToURL('{{ route('search-showrooms') }}')">View
                    More</button>
            </div>
        </div>
    </section>

    <section id="recommanded-showrooms" class="section-space">
        @include('front.recommended-showroom', [
            'showrooms' => $showrooms,
        ])
    </section>
    <?php /*
<section id="top-pics" class="section-space">
  <div class="container-xl">
    <h2 class="heading mb-5">Top <span>Picks</span></h2>
    <div class="row mb-4">
      @foreach ($cars as $car)
      <div class="col-lg-3 col-md-6 mb-3">
        <a href="{{route('car-detail', $car->id)}}">
        <div class="tp-box"> <img src="{{$car->image}}" class="w-100">
          <div class="p-3 tp-box-body">
            <h3>{{$car->brand->name}} {{$car->model->name}}</h3>
            <h4>{{number_format($car->price_range)}} Pkr</h4>
            <div class="rating mb-2 justify-content-start">
              @for ($i = 0; $i < 5; $i++) @if($i < $car->rating)
                <span class="fa fa-star checked"></span>
                @else
                <span class="fa fa-star"></span>
                @endif
                @endfor
            </div>
            <span class="reviews">{{$car->count_review}} Reviews</span>
          </div>
        </div>
        </a>
      </div>
      @endforeach
    </div>
    <div class="text-center">
      <button class="btn btn-default" onclick="RedirectToURL('{{route('search-cars')}}');">View More</button>
    </div>
  </div>
</section>
*/
    ?>
    <section id="searchby-brands" class="section-space">
        <div class="container-xl">
            <h2 class="heading mb-5">Search By <span>Brands</span></h2>
            <div class="row showroom-scroll max-height" style="max-height:585px";>
                @foreach ($brands as $brand)
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('search-cars', ['brand_id' => $brand->id]) }}">
                            <div class="b-box"> <img src="{{ $brand->logo }}" class="img-fluid"> </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="getapp" {{--class="section-space"--}}>
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-7 col-md-8 mb-3 mb-lg-0">
                    <div class="get-left">
                        <div class="getinner">
                            <h4 class="get-heading mb-3">Get <span class="clr-primary">GaariLo</span> APP</h4>
                            <h5 class="mb-2">Buy & Sell cars from anywhere in Pakistan</h5>
                            <h6 class=" hidden mb-4">You can Download Gaarilo apps by Scanning below QR Code</h6> <img
                                src="{{ URL::asset('front/images/qr.png') }}" class="img-fluid hidden">
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

        var latitude = "";
        var longitude = "";

        $(document).ready(function() {
            GetLocation();
            AjaxRecommendedShowrooms(latitude, longitude);
        });

        function GetLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(SetPosition);
                return true;
            } else {
                console.log("Geolocation is not supported by this browser.");
                return false;
            }
        }

        function SetPosition(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            AjaxRecommendedShowrooms(latitude, longitude);
        }

        function AjaxRecommendedShowrooms(lat, long)
        {
            $.ajax({
            url: '{{ route('filterapi.recommended-showrooms') }}',
            type: 'POST',
            data: {
            latitude: lat,
            longitude: long,
            _token: '{{ csrf_token() }}'
            },
            success: function(data) {
            if (data.result == 1) {
                $("#recommanded-showrooms").html(data.html);
            }
            },
            error: function(error) {
            console.log(error);
            }
            });
        }

    </script>
@endsection
