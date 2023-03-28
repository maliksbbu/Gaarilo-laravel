<section class="section-space pb-0">
    <style>
     a{
      color: white;
     }
    </style>
    <footer id="footer">
      <div class="container-xl">
        <div class="row">

    <!-- Gaarilo-logo -->
        <!-- <div class="col-lg-3 col-md-6"> <img src="{{URL::asset('front/images/logowhite.png')}}" class="mb-3 mt-3 img-fluid"> <img src="{{URL::asset('front/images/storimg.png')}}" class="img-fluid"> </div> -->
        <div class="col-lg-3 col-md-6"> <img src="{{URL::asset('front/images/white-logo.png')}}" width="150px" class="img-fluid mb-3 mt-2"> </div>
    <!-- Gaarilo-logo -->

          <div class="col-lg-3 col-md-6">
            <ul class="p-0">
              <li><a href="{{url('about')}}">About Us</a></li>
              <li><a  href="{{route('search-showrooms')}}">Showrooms</a></li>
              <li><a href="{{url('support-contact')}}">Contact Us & Support</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6">
            <h5>Used Cars</h5>
            <ul class="p-0">
              <li><a href="{{route('search-cars', ['condition' => 'USED'])}}">Find Used Cars</a></li>
              <li><a
                @if(!Session::has('user'))
                href="javascript:ShowToaster('Error', 'Sign-in First To Proceed');"
                @else @if(Session::get('user')->is_business == "NO")
                href="javascript:ShowToaster('Error', 'Enter Business Details to Proceed');"
                @else
                href="{{route('post-ad')}}"
                @endif
                @endif
                >Sell Your Car</a></li>
              <li><a href="{{route('search-cars',['verified' => 'YES', 'condition'=>'USED'])}}">Verified Dealers</a></li>
              <li><a href="{{route('search-cars',[ 'condition'=>'USED'])}}">Used Car Dealers</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6">
            <h5>New Cars</h5>
            <ul class="p-0">
              <li><a href="{{route('search-cars',[ 'condition'=>'NEW'])}}">Find New Cars</a></li>
              <li><a
                @if(!Session::has('user'))
                href="javascript:ShowToaster('Error', 'Sign-in First To Proceed');"
                @else @if(Session::get('user')->is_business == "NO")
                href="javascript:ShowToaster('Error', 'Enter Business Details to Proceed');"
                @else
                href="{{route('post-ad')}}"
                @endif
                @endif
                >Sell A Car</a></li>
              <li><a href="{{url('video-reviews')}}">Reviews</a></li>
              <li><a href="{{route('search-cars',['verified' => 'YES', 'condition'=>'NEW'])}}">Verified Dealers</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="fheight"></div>
      <div class="container">
        <div class="footer-bottom">
          <div class="row">
            <div class="col-sm-8">
              <ul class="p-0 m-0">
                <li class="me-4"><a href="{{route('terms')}}">Terms of Service</a></li>
                <li><a href="{{route('privacy')}}">Privacy Policy</a></li>
              </ul>
            </div>
            <div class="col-sm-4 text-start text-lg-end">
              <ul class="p-0 social m-0">
                <li><a class="tw" target="_blank" href="https://twitter.com/gaari_lo"><i class="fa-twitter fab"></i></a></li>
                <li><a class="fb"  target="_blank" href="https://www.facebook.com/GaariLo.com.official"><i class="fa-facebook-f fab"></i></a></li>
                <li><a class="insta"  target="_blank" href="https://www.youtube.com/@gaarilo567"><i class="fa-youtube fab"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </section>
