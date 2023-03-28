<div class="modal fade" id="more-detail-popup-version" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-body p-0">
          <div class="custom-popup-header">
            <h2 class="m-0">Select Version</h2>
          </div>
          <div class="custom-popup-body">

        <div class="row">
          <div class="col-md-6">
          @foreach($versions as $version)
            <div class="d-flex align-items-center mb-3">
              <label class="custom-checkbox">
              {{$version->name}}
              <input type="checkbox" name="filter_version" id="filter_version_{{$version->id}}" value="{{$version->id}}"/>

              <span class="checkmark"></span>
              </label>
              <div class="salecounter ms-auto">
              {{car_version_count($version->id)}}
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" onclick="ClosingVersionDialog(false);">Close</button>
          <button class="btn btn-primary" onclick="ClosingVersionDialog(true);">Submit</button>
        </div>
      </div>
    </div>
  </div>
