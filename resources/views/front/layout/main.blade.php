<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{URL::asset('front/scss/main.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
  <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
  <title>Gaarilo</title>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="{{URL::asset('front/images/white-logo.png')}}"> </head>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
<body id="gaarilo">

    @include('front.layout.preloader')

    @include('front.layout.toaster')

    @include('front.layout.notification')

    @include('front.layout.header')

    @yield('content')

    @include('front.layout.footer')

  <a href="#" id="scroll" style="display: none;" title="Back to Top"><span></span></a>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  <!-- <script src="scss/js/dropdown.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Owl Carousel -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- custom JS code after importing jquery and owl -->
  <script src="{{URL::asset('front/scss/js/custom.js')}}"></script>
  <script src="{{URL::asset('common/common_functions.js')}}"></script>
  <script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
  });
  $(document).ready(function() {
    $(window).scroll(function() {
      if($(this).scrollTop() > 100) {
        $('#scroll').fadeIn();
      } else {
        $('#scroll').fadeOut();
      }
    });
    $('#scroll').click(function() {
      $("html, body").animate({
        scrollTop: 0
      }, 600);
      return false;
    });
  });
  </script>
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

  window.onload = (event) => {
    PreLoader("hide");
    $("#toaster-message-notify").fadeOut(7000);
    };

  </script>

  @yield('scripts')
</body>

</html>
