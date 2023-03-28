@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">

          <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">User</h4>
            <div class="ms-auto">
              <div class="d-flex align-items-center">
            <button class="btn btn-danger ms-2" onclick="Delete()"><i class="fa fa-trash-alt"></i> Delete</button>
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
                <input type="checkbox" name="user_id" value="{{$user->id}}" id="user_id">
                <span class="checkmark"></span>
              </label>

              <div class="user-avatar me-3">
                <img class="img-fluid" src="{{$user->image}}" style="border-radius:10px; width:85px;height:85px;">
              </div>
              <div class="pending-ads-detail">
                <h5 class="mb-1">{{$user->first_name}} {{$user->last_name}}</h5>

                <p class="mb-0 fw-semibold">
                  Registration Date: <span class="text-primary">{{date('m/d/y', strtotime($user->created_at))}}</span>
                </p>
                <p class="mb-0 fw-semibold">
                  Contact No: <span class="text-primary"> {{$user->phone}}</span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-xl-3 text-end">
            <div class="d-flex flex-column justify-content-evenly h-100 align-items-end showrooms-card-right">
            @if($user->is_business == 'YES')
              <div class="showroom-link d-flex align-items-center">
                <div class="soldcar-avatar me-2">
                @if(!empty($user->showroom))
                <a href="{{url('showroom-detail') .'/'. $user->showroom->id}}"><img class="img-fluid h-100 w-100" src="@if($user->showroom != null){{$user->showroom->logo}} @endif"></a>
                @endif
                </div>
                <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 98px;">
                @if(!empty($user->showroom))
                <a >{{$user->showroom->name}}</a>
                @endif
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

function Delete ()
{
    var user_id = $("input[name=user_id]:checked").val();
    if(user_id != null)
    {
        if(confirm("Do you want to delete?"))
        {
            //DELETE HERE
            RedirectToURL("{{route('admin.delete-user','')}}/"+user_id);
        }
    }
}
</script>
@endsection
