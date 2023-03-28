@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">

      <div class="d-flex align-items-center">
        <h4 class="page-heading my-4">Pending Ads</h4>
      </div>

      <form action="{{route('admin.admin-update-pending-ads')}}" method="POST" enctype="multipart/form-data" id="adm_pending_ads_form">
      {{csrf_field()}}
      <input type="hidden" name="adm_pending_ads_action" id="adm_pending_ads_action" value="accept">
      <input type="hidden" name="adm_pending_ads_ids[]" id="adm_pending_ads_ids" value="">
      @if(count($cars) > 0)
      @foreach ($cars as $car)


       <div class="pending-ads-box mb-3">
        <div class="row">
          <div class="col-md-9 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
              <div class="pending-ads-avatar me-3">
                <img class="img-fluid h-100" src="{{$car->image}}" style="rotate:{{$car->rotation}}deg;">
              </div>
              <div class="pending-ads-detail">
                <h5 class="mb-1">{{$car->brand->name}} {{$car->model->name}} {{$car->car_year}}</h5>
                <h6 class="mb-1">PKR. {{number_format($car->price_range)}}</h6>
                <p class="mb-1">
                  <span class="mx-2 ms-0">{{$car->car_year}}</span> | <span class="mx-2">{{$car->mileage}} km</span> | <span class="mx-2">{{$car->engine_type}}</span> | <span class="ms-0 ms-sm-2">{{$car->engine_capacity}}cc</span> |
                  <span class="ms-0 ms-lg-2">{{$car->transmission}}</span>
                </p>
                <a @if(!empty($car->showroom)) href="{{route('showroom-detail', $car->showroom->id)}}" @endif>
                <div class="showroom-link d-flex align-items-center">
                  <div class="showroom-link-avatar me-2">
                  <img class="img-fluid h-100" src="@if($car->showroom != null){{$car->showroom->logo}} @endif">
                  </div>
                  @if(!empty($car->showroom))
                  {{$car->showroom->name}}
                  @endif
                </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-3 text-end">
            <div class="d-flex flex-column justify-content-evenly h-100 align-items-end">
            <input type="hidden" name="adm_pending_ads_id" id="adm_pending_ads_id" value="{{$car->id}}">
            <button class="btn btn-success mb-3 adm-pending-ad-accept"><i class="fa fa-check me-2"></i> Accept</button>
            <button class="btn btn-danger adm-pending-ad-reject"><i class="fa fa-times me-2"></i> Reject</button>
          </div>
          </div>
        </div>
       </div>

       @endforeach
        @else
        <div class="col-lg-3 col-md-6 mb-3">
          No Cars
        </div>
        @endif
        </form>


    </div>
@endsection
@section('scripts')
<script>

$(document).on('click', '.adm-pending-ad-accept', function(e) {

    $("#adm_pending_ads_action").val("accept");

    $val_id = $(this).siblings('#adm_pending_ads_id').val();

    $('#adm_pending_ads_ids').val($val_id);

    let confirmAction = confirm("Are you sure you want to accpet ad?");
        if (confirmAction) {
          $("#adm_pending_ads_form").submit();
        } else {
          return false;
        }

    e.preventDefault();
});

$(document).on('click', '.adm-pending-ad-reject', function(e) {

$("#adm_pending_ads_action").val("reject");

$val_id = $(this).siblings('#adm_pending_ads_id').val();

    $('#adm_pending_ads_ids').val($val_id);

let confirmAction = confirm("Are you sure you want to reject ad?");
    if (confirmAction) {
      $("#adm_pending_ads_form").submit();
    } else {
      return false;
    }

e.preventDefault();
});

</script>
@endsection
