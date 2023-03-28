@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">

          <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">Admin</h4>
            <div class="ms-auto">
              <div class="d-flex align-items-center">
            <button class="btn btn-danger ms-2"><i class="fa fa-trash-alt"></i> Delete</button>
              </div>
            </div>
          </div>

          @if(count($users) > 0)
      @foreach ($users as $user)
       <div class="pending-ads-box mb-3">
        <div class="row">
          <div class="col-xl-9 mb-3 mb-md-0">
            <div class="d-flex align-items-center">

              <label class="custom-checkbox me-3 ms-2 mtm-18">
                <input type="checkbox" checked="checked" name="user_id" value="{{$user->id}}">
                <span class="checkmark"></span>
              </label>

              <div class="user-avatar me-3">
                <img class="img-fluid" src="{{$user->image}}" style="width:85px;height:85px;">
              </div>
              <div class="pending-ads-detail">
                <h4 class="mb-1">{{$user->name}}</h4>
                <h5 class="mb-1">{{$user->email}}</h5>
                <p class="mb-0 fw-semibold">
                  Registration Date: <span class="text-primary">{{date('m/d/y', strtotime($user->created_at))}}</span>
                </p>

              </div>
            </div>
          </div>
          <div class="col-xl-3 text-end">
            <div class="d-flex flex-column justify-content-evenly h-100 align-items-end showrooms-card-right">
            @if($user->is_business == 'YES')
              <div class="showroom-link d-flex align-items-center">
                <div class="soldcar-avatar me-2">
                <img class="img-fluid" src="@if($user->showroom != null){{$user->showroom->logo}} @endif">
                </div>
                <div>

                <a>{{$user->showroom->name}}</a>

                </div>
              </div>
              @endif
          </div>
          </div>
        </div>
       </div>
       @endforeach
        @else
        <div class="col-lg-3 col-md-6 mb-3">
          No Users
        </div>
        @endif

    </div>
@endsection

@section("scripts")
<script>
$(document).ready(function() {
    $("input[name=user_id]").change(function() {
        if ($(this).is(":checked")) {
            $("input[name=user_id]").prop('checked', false);
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    });
});
</script>
@endsection
