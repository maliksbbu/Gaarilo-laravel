<div class="modal fade" id="more-detail-popup-cities" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-body p-0">
          <div class="custom-popup-header">
            <h2 class="m-0">Select Cities</h2>
          </div>
          <div class="custom-popup-body">

        <div class="row">
          <div class="col-md-6">
          @foreach($cities as $city)
            <div class="d-flex align-items-center mb-3">
              <label class="custom-checkbox">
                {{strtoupper($city->name)}}
                {{-- <input type="checkbox" name="search_cars_filter_city[]" id="search_cars_filter_city_{{$city->id}}" value="{{$city->id}}" onclick="select_filter_from_home();" /> --}}
                <input type="checkbox" name="filter_city" id="filter_city_{{$city->id}}" value="{{$city->id}}" />
                <span class="checkmark"></span>
              </label>
              <div class="salecounter ms-auto">
                @if ($for == "cars")
                {{car_in_city($city->id)}}
                @else
                {{showroom_in_city($city->id)}}
                @endif

              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" onclick="ClosingCityDialog(false);">Close</button>
          {{-- <button class="btn btn-primary" onclick="search_by_left_filters();">Submit</button> --}}
          <button class="btn btn-primary" onclick="ClosingCityDialog(true);">Submit</button>
        </div>
      </div>
    </div>
  </div>
