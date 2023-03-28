<div class="modal fade" id="more-detail-popup-engine-type" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
       
        <div class="modal-body p-0">
          <div class="custom-popup-header">
            <h2 class="m-0">Select Version</h2>
          </div>
          <div class="custom-popup-body">

        <div class="row">
          <div class="col-md-6">
          @foreach($engine_types as $engine_type)
            <div class="d-flex align-items-center mb-3">
              <label class="custom-checkbox">
              {{$engine_type}}
              <input type="checkbox" name="search_cars_filter_engine_type[]" id="search_cars_filter_engine_type_{{$engine_type}}" value="{{$engine_type}}" onclick="select_filter_from_home_engine_type();"/>
              <span class="checkmark"></span>
              </label>
              <div class="salecounter ms-auto">
              {{car_engine_type_count($engine_type)}}
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
        </div>
        <div class="modal-footer">
          <button data-bs-dismiss="modal" class="btn btn-default">Close</button>
          <button class="btn btn-primary" onclick="search_by_left_filters();">Submit</button>
        </div>
      </div>
    </div>
  </div>