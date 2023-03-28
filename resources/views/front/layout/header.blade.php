@php
    use App\Http\Controllers\CommonController;
    use App\Models\Car;
@endphp
<nav class="navbar navbar-expand-xl navbar-light shadow-sm fixed-top topnav">
    <div class="container">
      <a class="navbar-brand" href="{{route('landing')}}"> <img src="{{URL::asset('front/images/blue-logo.png')}}" width="100px"> </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-0 ms-xl-5">
          <li class="nav-item dropdown"> <a class="nav-link active showroom-megadropdown" href="#">Showroom <i class="fa fa-caret-down"></i></a>
            <div class="shadow-sm showroom-megadropdown-content dropdown-menu">
              <div class="megadropdown">
                <div class="row">
                  <div class="col-sm-6 ps-0">
                    <div class="border-end">
                      <ul class="left-list">
                        <li><a href="{{route('search-showrooms')}}">Find Showrooms</a></li>
                        <li><a href="{{route('search-showrooms', ['verified' => 'YES'])}}">Verified Showrooms</a></li>
                        @if(!Session::has('user'))
                        <li><a  href="javascript:ShowToaster('Error', 'Sign-in First To Proceed'); ">My Showroom</a></li>
                        @elseif(Session::get('user')->is_business == "NO")
                        <li><a href="javascript:ShowToaster('Error', 'Go To Your Profile And Insert Showroom Details'); ">My Showroom</a></li>
                        @else
                        <li><a href="{{url('my-showroom-details').'/'.Session::get('user')->showroom->rid}}">My Showroom</a></li>
                        @endif
                        <li><a href="{{route('search-showrooms', ['condition' => 'USED'])}}">Used Car Dealers</a></li>
                        <li><a href="{{route('search-showrooms', ['condition' => 'NEW'])}}">New Car Dealers</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <h5 class="fw-bold"><img src="{{URL::asset('front/images/megacar.png')}}"> Trending Brands</h5>
                    <ul class="list-right">
                      @foreach ((new CommonController())->GetTrendingBrands() as $brand)
                        <li><a href="{{route('search-showrooms', ['brand_id' => $brand->id])}}">{{$brand->name}}</a></li>
                        @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown"> <a class="nav-link usedcar-megadropdown">Used Cars <i class="fa fa-caret-down"></i>  </a>
            <div class=" shadow-sm usedcar-megadropdown-content dropdown-menu">
              <div class="megadropdown">
                <div class="row">
                  <div class="col-sm-4 ps-0">
                    <div class="border-end">
                      <ul class="left-list">
                        <li><a href="{{route('search-cars',['condition'=>'USED'])}}">Find used car
                            <span>Search from over {{(new CommonController())->FormatNumberIntoK(Car::CountAd())}} options</span></a> </li>
                        <li><a
                            @if(!Session::has('user'))
                            href="javascript:ShowToaster('Error', 'Sign-in First To Proceed');"
                            @else @if(Session::get('user')->is_business == "NO")
                            href="javascript:ShowToaster('Error', 'Enter Business Details to Proceed');"
                            @else
                            href="{{route('post-ad')}}"
                            @endif
                            @endif>Sell your Car
                             <span>Post a free Ad and maintain
                              your complete showroom</span></a> </li>
                        <li><a href="{{route('search-cars',['verified' => 'YES', 'condition'=>'USED'])}}">Verified Dealers
                             <span>Find Gaarilo verified Cars near you</span></a> </li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="border-end">
                      <h5 class="fw-bold"><img src="{{URL::asset('front/images/location.png')}}">Trending Cities</h5>
                      <ul class="list-right">
                        @foreach ((new CommonController())->GetTrendingCities() as $city)
                        <li><a href="{{route('search-cars', ['city' => $city->id, 'condition'=>'USED'])}}">{{strtoupper ($city->name)}}</a></li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <h5 class="fw-bold"><img src="{{URL::asset('front/images/megacar.png')}}"> Trending Models</h5>
                    <ul class="list-right">
                    @foreach ((new CommonController())->GetTrendingModels() as $model)
                    <li><a href="{{route('search-cars', ['model_id' => $model['id'], 'condition'=>'USED'])}}">{{$model['formatted_name']}}</a></li>
                    @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown"> <a class="nav-link newcar-megadropdown" href="#">New Cars <i class="fa fa-caret-down"></i> </a>
            <div class="shadow-sm newcar-megadropdown-content dropdown-menu">
              <div class="megadropdown">
                <div class="row">
                  <div class="col-sm-4 ps-0">
                    <div class="border-end">
                      <ul class="left-list">
                        <li><a href="{{route('search-cars',['condition'=>'NEW'])}}">Find New Car
                            <span>Search new cars in Pakistan</span></a> </li>
                        <li><a
                            @if(!Session::has('user'))
                            href="javascript:ShowToaster('Error', 'Sign-in First To Proceed');"
                            @else @if(Session::get('user')->is_business == "NO")
                            href="javascript:ShowToaster('Error', 'Enter Business Details to Proceed');"
                            @else
                            href="{{route('post-ad')}}"
                            @endif
                            @endif
                            >Sell a Car
                            <span>Post a free Ad and maintain your
                              complete showroom</span></a> </li>
                        <li><a href="{{url('car-review')}}">Reviews
                            <span>Read Reviews of your favorite cars</span></a> </li>
                        <li><a href="{{route('search-cars',['verified' => 'YES', 'condition'=>'NEW'])}}">Verified Dealers
                            <span>Purchase your dream with GaariLo
                              Verified dealers</span></a> </li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="border-end">
                      <h5 class="fw-bold"><img src="{{URL::asset('front/images/megacar.png')}}"> Trending Brands</h5>
                      <ul class="list-right">
                        @foreach ((new CommonController())->GetTrendingBrands() as $brand)
                        <li><a href="{{route('search-cars', ['brand_id' => $brand->id, 'condition'=>'NEW'])}}">{{$brand->name}}</a></li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <h5 class="fw-bold"><img src="{{URL::asset('front/images/megacar.png')}}"> Trending New Cars</h5>
                    <ul class="list-right">
                      @foreach ((new CommonController())->GetTrendingModels() as $model)
                      <li><a
                          href="{{route('search-cars', ['model_id' => $model['id'], 'condition'=>'NEW'])}}">{{$model['formatted_name']}}</a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{route('video_review')}}">Videos & Reviews </a> </li>
          <li class="nav-item"> <a class="nav-link" href="{{route('news_tips')}}">News & Tips </a> </li>
        </ul>
        <div class="d-flex align-items-center nav-right float-sm-end pb-2 pb-xl-0 pe-2 pe-xl-0">
        @if(!Session::has('user'))
        <div class="btn-post-ad"> <a href="javascript:ShowToaster('Error', 'Sign-in First To Proceed');">Post an Ad</a> </div>
        @else
        @if(!str_contains($_SERVER['REQUEST_URI'], 'post-ad'))
        <div class="btn-post-ad"> <a href="{{route('post-ad')}}">Post an Ad</a> </div>
        @else
        <div class="btn-post-ad d-none"> <a href="{{route('post-ad')}}">Post an Ad</a> </div>
        @endif
        @endif

          @if(!Session::has('user'))
          <div class="btn-register"> <a href="{{route('signup')}}">Register</a> </div>
          <div class="btn-signin"> <a href="{{route('login')}}">Sign in</a> </div>
          @else
          <div class="dropdown avatar-dropdown">
            <a class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{URL::asset('front/images/downcaret.png')}}" class="me-2">
                <img src="{{URL::asset('front/images/avatar.png')}}">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="{{route('user-profile')}}">Profile</a></li>
                @if(Session('user')->is_business == 'NO')
                <li><a class="dropdown-item" href="{{route('user-profile')}}">My Showroom</a></li>
                @else
                <li><a class="dropdown-item" href="{{url('my-showroom-details',Session('user')->showroom->id)}}">My Showroom</a></li>
                @endif

                <li><a class="dropdown-item" href="{{route('saved_ads')}}">My Saved Ads</a></li>
                @if(Session('user')->is_business == 'NO')
                <li><a class="dropdown-item" href="{{route('user-profile')}}">Offers</a></li>
                @else
                <li><a class="dropdown-item" href="{{url('offer')}}">Offers</a></li>
                @endif
                <li><a class="dropdown-item" href="{{route('signout')}}">Signout</a></li>
            </ul>
        </div>
          @endif
        </div>
      </div>
    </div>
  </nav>
