<div class="modal fade" id="more-detail-popup-brands" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-body p-0">
          <div class="custom-popup-header">
            <h2 class="m-0">Select Brands</h2>
          </div>
          <div class="custom-popup-body">

        <div class="row">
          <div class="col-md-6">
          @foreach($brands as $brand)
            <div class="d-flex align-items-center mb-3">
              <label class="custom-checkbox">
              {{$brand->name}}
              <input type="checkbox" name="filter_brand" id="filter_brand_{{$brand->id}}" value="{{$brand->id}}"/>
                <span class="checkmark"></span>
              </label>
              <div class="salecounter ms-auto">
              {{car_make_count($brand->id)}}
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" onclick="ClosingBrandDialog(false);">Close</button>
          <button class="btn btn-primary" onclick="ClosingBrandDialog(true);">Submit</button>
        </div>
      </div>
    </div>
  </div>
