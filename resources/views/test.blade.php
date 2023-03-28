<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNMBmn_fMVZ9U6RAvCbucOZA0mMEoki5c&libraries=places"></script>

<input type="text" id="location" name="location">
<input  type="text"  id="f_latitude" name="f_latitude">
<input  type="text" id="f_longitude" name="f_longitude">
<input  type="text" id="main_address" name="main_address">

<div class="col-md-12 col-12">                                   
<div id="map" style="width:100%; height:300px"></div> 
</div>  
<!-- <script type="text/javascript" src="/test/wp-content/themes/child/script/jquery.jcarousel.min.js"></script> -->
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>


<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNMBmn_fMVZ9U6RAvCbucOZA0mMEoki5c&libraries=places" type="text/javascript"></script> -->
    
    <script type="text/javascript">
        var geocoder = new google.maps.Geocoder();
        var marker = null;
        var map = null;
        function updateMarkerAddress(str) {
            document.getElementById('location').value = str;
        }
        function initialize() {

            var options = {
              componentRestrictions: {country: "pak"}
            };

            var input = document.getElementById('location');
            var latlng = new google.maps.LatLng(33.6844, 73.0479);
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                map.setCenter(place.geometry.location);
                map.setZoom(14);
                marker.setPosition( new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
                document.getElementById('location').value = place.name;
                document.getElementById('f_latitude').value = place.geometry.location.lat();
                document.getElementById('f_longitude').value = place.geometry.location.lng();
                document.getElementById('main_address').value = place.geometry.location;
                // console.log(place.geometry.location);
                var geocoder = new google.maps.Geocoder();
                
            });
            if( jQuery("#f_latitude").val() !== "" && jQuery("#f_longitude").val() !== "" ) {
              var latitude = jQuery("#f_latitude").val();
              var longitude = jQuery("#f_longitude").val();
            }
            else {
              var latitude = 33.6844;
              var longitude = 73.0479;
            }
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
                document.getElementById('f_latitude').value = latLng.lat();
                document.getElementById('f_longitude').value = latLng.lng();
                //console.log(latLng.lat() +'---------'+ latLng.lng());

                geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
                    if (status !== google.maps.GeocoderStatus.OK) {
                        // alert(status);
                        console.log(status);
                        address = "Address not available";
                    }
                    // This is checking to see if the Geoeode Status is OK before proceeding
                    if (status == google.maps.GeocoderStatus.OK) {
                        console.log(';;;'+results);
                        address = (results[0].formatted_address);
                    }
                });
                
                
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>