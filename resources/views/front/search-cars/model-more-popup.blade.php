<div class="modal fade" id="more-detail-popup-model" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-body p-0">
          <div class="custom-popup-header">
            <h2 class="m-0">Select Model</h2>
          </div>
          <div class="custom-popup-body">

        <div class="row">
          <div class="col-md-6">
          @foreach($models as $model)
            <div class="d-flex align-items-center mb-3">
              <label class="custom-checkbox">
              {{$model->name}}
              <input type="checkbox" name="filter_model" id="filter_model_{{$model->id}}" value="{{$model->id}}"/>
              <span class="checkmark"></span>
              </label>
              <div class="salecounter ms-auto">
              {{car_model_count($model->id)}}
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" onclick="ClosingModelDialog(false);">Close</button>
          <button class="btn btn-primary" onclick="ClosingModelDialog(true);">Submit</button>
        </div>
      </div>
    </div>
  </div>
