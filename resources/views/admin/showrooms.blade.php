@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">
  <!-- <div class="d-flex align-items-center">
    <h4 class="page-heading my-4">Showrooms</h4>
    <div class="ms-auto">
      <div class="d-flex align-items-center">
        <button class="btn btn-primary adm-mark-verified"> Mark as verified</button>
        <button class="btn btn-danger ms-2 adm-mark-delete"><i class="fa fa-trash-alt"></i> Delete</button>
      </div>
    </div>
  </div> -->
  <form action="{{route('admin.admin-showroom-verified')}}" method="POST" enctype="multipart/form-data" id="adm_showroom_form">
    <input type="hidden" name="adm_showroom_mark_action" id="adm_showroom_mark_action" value="verify">
    {{csrf_field()}}
    @foreach($showrooms as $showroom)
    <div class="pending-ads-box mb-3" onclick="showroom_detail({{$showroom->id}})" style="cursor: pointer;">
      <div class="row">
        <div class="col-xl-9 mb-3 mb-md-0 mt-30">
          <div class="d-flex align-items-center">

            <!-- <label class="custom-checkbox me-3 ms-2 mtm-18">
              @if($showroom->verified != 'YES')
              <input type="checkbox" name="adm_showroom_mark[]" value="{{$showroom->id}}">
              <span class="checkmark"></span>
              @endif
            </label> -->

            <div class="showrooms-avatar me-3">
              <img class="img-fluid h-100" src="{{$showroom->logo}}">
            </div>
            <div class="pending-ads-detail">
            <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 265px;">
              <h5 >{{$showroom->name}} @if($showroom->verified == 'YES')<img src="{{URL::asset('front/images/green-small-tick.png')}}">@endif</h5></div>
              <div class="rating mb-2 justify-content-start">

                <span class="me-3">{{number_format($showroom->rating,1)}}</span>
                @for ($i = 0; $i < 5; $i++) @if($i < $showroom->rating)
                  <span class="fa fa-star checked"></span>
                  @else
                  <span class="fa fa-star"></span>
                  @endif
                  @endfor
                  <span class="ms-2 fs-12">{{$showroom->count_review}} Reviews</span>
              </div>
              <p class="mb-1">
                <span class="mx-2 ms-0">{{city_name($showroom->city_id)}}</span></span>
              </p>

            </div>
          </div>
        </div>
        <div class="col-xl-3 text-end">
          <div class="d-flex flex-column justify-content-evenly h-100 align-items-end showrooms-card-right align-items-center">
            <h4 class="mb-3 fw-semibold text-primary mt-3">Total Cars: {{$showroom->count_all_cars}}</h4>
            <h5 class="fw-semibold mb-1">Joined Date: <span class="text-primary">{{date('d M, Y', strtotime($showroom->created_at))}}</span></h5>
            <h5 class="fw-semibold">Years of Service: <span class="text-primary">{{$showroom->service_year}}</span></h5>
            <div class="row mb-3">
          <div class="col-lg-12">
            <div class="text-end d-flex justify-content-center align-items-center">
              @if($showroom->verified != 'YES')
              <a href="{{url('admin/verified-showroom').'/'. $showroom->id}}" class="btn btn-primary d-flex justify-content-center align-items-center" >Verified</a>
              @endif
              <a  href="{{url('admin/delete-showroom').'/'. $showroom->id}}" class="btn btn-danger ms-2"><i class="fa fa-trash-alt m-1"></i>Delete</a>
            </div>
          </div>
        </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </form>




</div>
@endsection
@section('scripts')
<script>
  $(document).on('click', '.adm-mark-verified', function(e) {

    $("#adm_showroom_mark_action").val("verify");

    let confirmAction = confirm("Are you sure you want to mark showrooms as verified?");
    if (confirmAction) {
      $("#adm_showroom_form").submit();
    } else {
      return false;
    }

    e.preventDefault();
  });

  $(document).on('click', '.adm-mark-delete', function(e) {

    $("#adm_showroom_mark_action").val("delete");

    let confirmAction = confirm("Are you sure you want to mark showrooms as verified?");
    if (confirmAction) {
      $("#adm_showroom_form").submit();
    } else {
      return false;
    }

    e.preventDefault();
  });

  function showroom_detail($id) {
    window.location.href = "{{route('admin.admin-showroom-detail','')}}/" + $id;
  }
</script>
@endsection
