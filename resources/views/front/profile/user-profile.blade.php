@extends('front.layout.main')
@section('content')
<style>
    input:read-only {
        background-color: gainsboro;
    }
</style>
<?php
use App\Http\Controllers\CommonController; ?>
<script src="https://maps.googleapis.com/maps/api/js?key={{(new CommonController())->GetSetting('GOOGLE_API_KEY')}}&libraries=places"></script>

<section class="section-space mt-80px write-review">
    <div class="container-xl">
        <form id="signup_form" action="{{route('user-profile-store')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="review-box mb-3">
                <div class="d-flex align-items-center mb-5 flex-wrap">
                    <h5 class="text-center text-lg-start me-3 mb-0">My Profile</h5>
                    @if(Session('user')->is_business == 'NO')
                    <h6 class="m-0 ms-md-auto text-danger enter-details fw-normal"><span class="fw-semibold">Showroom Details</span> missing</h6>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-md-2">
                        <div class="row mb-4 align-items-center">
                            @if($user->image)
                            <div class="col-md-9 offset-lg-3">
                                <div class="myprofile-avatar mb-4">
                                    <img id="blah" src="{{$user->image}}">
                                    <input id="imgInp" name="image" type="file" class="profile-browse-file d-block">
                                </div>
                            </div>
                            @else
                            <div class="col-md-9 offset-lg-3">
                                <div class="myprofile-avatar mb-4">
                                    <img id="blah" src="{{URL::asset('front/images/logavatar.png')}}">
                                    <input id="imgInp" name="image" type="file" class="profile-browse-file d-block">
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>First Name <sup class="required">*</sup></label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{$user->first_name}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Last Name <sup class="required">*</sup></label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="{{$user->last_name}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Phone Number <sup class="required">*</sup></label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" readonly class="form-control" id="phone" name="phone" placeholder="+923000000000" value="{{$user->phone}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Email <sup class="required">*</sup></label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="email" name="email" placeholder="abcd@gmail.com" value="{{$user->email}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="review-box mb-3">
                <div class="d-flex align-items-center mb-5 flex-wrap">
                    <h5 class="text-center text-lg-start me-3 mb-0">Showroom Details</h5>
                    <h6 class="m-0 ms-md-auto text-danger enter-details fw-normal">To post an Ad you must enter <span class="fw-semibold">Showroom Details</span></h6>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-md-2">
                        <div class="row mb-4 align-items-center">
                            @if($user->business_image)
                            <div class="col-md-9 offset-lg-3">
                                <div class="myprofile-avatar mb-4">
                                    <img id="b_img_preview" src="{{$user->business_image}}">
                                    <input id="b_img" name="b_img" type="file" class="profile-browse-file d-block">
                                </div>
                            </div>
                            @else
                            <div class="col-md-9 offset-lg-3">
                                <div class="myprofile-avatar mb-4">
                                    <img id="b_img_preview" src="{{URL::asset('front/images/logavatar.png')}}">
                                    <input id="b_img" name="b_img" type="file" class="profile-browse-file d-block">
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Showroom Name </label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="showroom_name" placeholder="Showroom Name" value="{{$user->business_name}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Showroom Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="showroom_email" placeholder="Business Email" value="{{$user->business_email}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Showroom Number</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="tel" maxlength="13" class="form-control" id="showroom_phone" name="showroom_phone" placeholder="+923000000000" value="{{$user->business_phone_number}}">
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center">
                            <div class="col-lg-4 text-lg-end mb-3 mb-lg-0">
                                <label>Address</label>
                            </div>
                            <div class="col-lg-8 text-lg-end mb-3 mb-lg-0">
                                <div class="position-relative" data-bs-toggle="modal" data-bs-target="#signup-address-popup">
                                    <input type="text" class="form-control" placeholder="Address" id="main_address" name="main_address" value="{{$user->address}}">
                                    <input type="hidden" value="" name="latitude" id="latitude" />
                                    <input type="hidden" value="" name="longitude" id="longitude" />
                                    <input type="hidden" value="" name="address" id="address" />
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <button class="btn btn-primary" type="submit" onclick="SignUp(event)">save</button>
            </div>

        </form>

    </div>
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
</section>
<script>
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
    }
    b_img.onchange = evt => {
        const [file] = b_img.files
        if (file) {
            b_img_preview.src = URL.createObjectURL(file)
        }
    }

    function CloseDialog(e) {
        e.preventDefault();
        $("#signup-address-popup").modal("hide");
    }

    function SignUp(e) {
        e.preventDefault();
        var phone = $('#phone').val();
        if (phone.length != 13 || phone.includes('_')) {
            ShowToaster("Phone Number Incomplete", "Kindly add valid phone number.");
            return;
        }
        if ($("#first_name").val() == "") {
            ShowToaster("First Name", "Add First Name");
            return;
        }
        if ($("#last_name").val() == "") {
            ShowToaster("Last Name", "Add Last Name");
            return;
        }
        if ($("#email").val() == "") {
            ShowToaster("Email", "Add Email");
            return;
        }
        if (!$("#business_detail_checkbox").is(':checked')) {
            $("#signup_form").submit();
        } else {
            var business_phone = $('#business_phone_number').val();
            if (business_phone.length != 13 || business_phone.includes('_')) {
                ShowToaster("Business Phone Number Incomplete", "Kindly add valid business phone number.");
                return;
            }
            if ($("#city").val() == "") {
                ShowToaster("City", "Kindly Select City");
                return;
            }
            if ($("#main_address").val() == "") {
                ShowToaster("Enter Address", "Kindly add valid business address.");
                return;
            }
            if ($("#business_name").val() == "") {
                ShowToaster("Business Name", "Add Business Name");
                return;
            }
            if ($("#business_email").val() == "") {
                ShowToaster("Business Email", "Add Business Email");
                return;
            }
            $("#signup_form").submit();
        }
    }
</script>
<script type="text/javascript">
    var geocoder = new google.maps.Geocoder();
    var marker = null;
    var map = null;
    var latitude = 33.6844;
    var longitude = 73.0479;

    function initialize() {

        var options = {
            componentRestrictions: {
                country: "pak"
            }
        };

        var input = document.getElementById('location');
        var latlng = new google.maps.LatLng(latitude, longitude);
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            map.setCenter(place.geometry.location);
            map.setZoom(14);
            marker.setPosition(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
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
            geocoder.geocode({
                'latLng': latLng
            }, (results, status) => {
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
@endsection
