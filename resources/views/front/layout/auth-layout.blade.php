<!doctype html>
<html lang="en">
<style>
.pac-container {
    z-index: 10000 !important;

}
</style>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::asset('front/scss/main.css')}}">
    <link rel="stylesheet" href="{{URL::asset('front/scss/login.css')}}">
    <title>Gaarilo</title>
    <link rel="icon" type="image/png" href="{{URL::asset('front/images/blue-logo.png')}}">
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('front/build/css/countrySelect.css')}}">
</head>
<style type="text/css">
    #map {
        width: 100%;
        height: 400px;
    }
</style>
<?php use App\Http\Controllers\CommonController; ?>
<body id="gaarilo" class="login-body">

    @include('front.layout.preloader')
    <!-- Address Modal -->
    <div class="modal fade" id="signup-address-popup" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Select Location</h4>
                    <input class="form-control" type="text" id="location" name="location" style="text-align: center">
                    <div class="confaddress mb-4">
                    <div id="map" style="width:100%; height:300px"></div>
                    <br>
                    </div>
                    <div class="selecaddr mb-3 d-flex align-items-center">
                        <img src="{{URL::asset('front/images/selecaddr.png')}}" class="me-2">
                        <p class="m-0" id="address_show">
                            No address selected
                        </p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-small" onclick="CloseDialog(event)">Confirm Location</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <section id="login">

        @include('front.layout.toaster')
        @include('front.layout.notification')

        <div class="row">
            <div class="col-lg-5 order-2 order-lg-1">
                <div class="login-left">
                    <div class="banner-logo">
                        <a href="{{route('landing')}}"><img src="{{URL::asset('front/images/blue-logo.png')}}" width="100px" class="img-fluid"></a>
                    </div>
                    <div class="banner-heading">
                        <h2>Find the right Vehicle
                            for you</h2>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{URL::asset('front/build/js/countrySelect.js')}}"></script>
    <script src="{{URL::asset('common/common_functions.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key={{(new CommonController())->GetSetting('GOOGLE_API_KEY')}}&libraries=places">
</script>
    <script>
        $("#country_selector").countrySelect({
            defaultCountry: "pk",
            onlyCountries: ['pk'],
        });

        $(document).ready(function () {
            $("#toaster-message-notify").fadeOut(7000);
        });

        function CloseDialog(e) {
            e.preventDefault();
            $("#signup-address-popup").modal("hide");
        }

        window.onload = (event) => {
        PreLoader("hide");
        };

    </script>
  <script type="text/javascript">
        var geocoder = new google.maps.Geocoder();
        var marker = null;
        var map = null;
        var latitude = 33.6844;
        var longitude = 73.0479;

        function initialize() {

            var options = {
              componentRestrictions: {country: "pak"}
            };

            var input = document.getElementById('location');
            var latlng = new google.maps.LatLng(latitude, longitude);
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                map.setCenter(place.geometry.location);
                map.setZoom(14);
                marker.setPosition( new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
                document.getElementById('location').value = place.name;
                document.getElementById('main_address').value = place.name;
                document.getElementById('address').value = place.name;
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
                latitude = place.geometry.location.lat();
                longitude = place.geometry.location.lng();
                $("#address_show").html(place.name);


            });
            var zoom = 14;
            var LatLng = new google.maps.LatLng(latitude, longitude);

            var mapOptions = {
                zoom: zoom,
                center: LatLng,
                panControl: false,
                zoomControl: false,
                scaleControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            if (marker && marker.getMap) marker.setMap(map);
            marker = new google.maps.Marker({
                position: LatLng,
                map: map,
                title: 'Drag Me!',
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function(marker) {
                var latLng = marker.latLng;
                latitude = latLng.lat();
                longitude = latLng.lng();

                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latLng },  (results, status) =>{
                    if (status !== google.maps.GeocoderStatus.OK) {
                        console.log(status);
                        address = "Address not available";
                    }
                    // This is checking to see if the Geoeode Status is OK before proceeding
                    if (status == google.maps.GeocoderStatus.OK) {
                        address = (results[0].formatted_address);
                    }
                });

                $("#address_show").html(address);
                document.getElementById('main_address').value = address;
                document.getElementById('address').value = address;
                document.getElementById('latitude').value = latLng.lat();
                document.getElementById('longitude').value = latLng.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    @yield('scripts')
</body>

</html>
