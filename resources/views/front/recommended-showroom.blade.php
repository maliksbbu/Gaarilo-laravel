<div class="container-xl">
    <h2 class="heading mb-5">Recommended <span>Showrooms</span></h2>
    <div class="owl-carousel owl-theme ps-carausel mb-4">
        @foreach ($showrooms as $showroom)
        <div class="item">
            <div class="showroom-box"> @if($showroom->logo) <img src="{{ $showroom->logo }}" class="img-fluid overflow-hidden rounded-circle"> @else  <img src="{{ URL::asset('front/images/sr-img1.png') }}" class="img-fluid overflow-hidden rounded-circle"> @endif
                <h5>{{strtoupper($showroom->name)}}</h5>
                <div class="rating mb-2"> <span class="me-3">{{number_format($showroom->rating,1)}}</span>
                    @for ($i = 0; $i < 5; $i++) @if($i < $showroom->rating)
                        <span class="fa fa-star checked"></span>
                        @else
                        <span class="fa fa-star"></span>
                        @endif
                        @endfor
                </div>
                <h3 class="mb-3">{{$showroom->city->name}}</h3>
                <hr>
                <p class="mb-2"> {{$showroom->address}} </p>
                <h4>
                    {{$showroom->GetNumberSoldCars()}} Sold Cars
                </h4>
                <button class="btn btn-primary w-100"
                    onclick="RedirectToURL('{{route('showroom-detail', $showroom->id)}}')">Visit Showroom</button>
            </div>
        </div>
        @endforeach
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

    $(".owl-carousel").owlCarousel();
    });
  $('.tn-carausel').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    autoplay: true,
    dots: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      1000: {
        items: 4
      }
    }
  });
  $('.ps-carausel').owlCarousel({
    //loop: true,
    margin: 30,
    nav: true,
    navText: ["<div class='nav-button owl-prev'><img src='{{URL::asset('front/images/leftarrow.png')}}'></div>", "<div class='nav-button owl-next'><img src='{{URL::asset('front/images/rightarrow.png')}}'></div>"],
    autoplay: false,
    dots: true,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      800: {
        items: 3
      },
      1000: {
        items: 4
      }
    }
  });
</script>
