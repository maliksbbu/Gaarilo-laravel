@foreach ($showrooms as $showroom)
<div class="gslistview-box listview mb-4" onclick="RedirectToShowroom('{{$showroom->id}}')">
  <div class="row">
    <div class="align-self-center col-lg-3 col-xl-2 text-center mb-3 mb-lg-0">
      <div class="db-top">
       <a target="_blank" href="{{url('showroom-detail/'.$showroom->id)}}"><img src="{{$showroom->logo}}" class="overflow-hidden rounded-circle" style="width:115px; height:115px; object-fit:cover;"></a>
      </div>
    </div>
    <div class="col-xl-6 col-lg-9 align-self-center text-center text-lg-start">
      <div class="db-body">
      <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 427px;">
        <h5>{{$showroom->name}}
          @if($showroom->verified == 'YES')
          <img src="{{URL::asset('front/images/green-small-tick.png')}}">
          @endif
        </h5>
      </div>
        <div class="rating mb-2 justify-content-center justify-content-lg-start align-items-center">
          <span class="me-2">{{number_format($showroom->rating,1)}}</span>
          @for ($i = 0; $i < 5; $i++)
              @if($i < $showroom->rating)
              <span class="fa fa-star checked"></span>
              @else
              <span class="fa fa-star"></span>
              @endif
          @endfor
          <span class="ms-2">{{$showroom->count_review}} Reviews</span>
        </div>
        <h3 class="mb-2">{{city_name($showroom->city_id)}}</h3>
        <p class="mb-0">
         {{$showroom->address}}
        </p>
      </div>
    </div>
    <div class="col-xl-4 mt-4 mt-xl-0">
      <div class="d-flex flex-row flex-xl-column justify-content-around h-100">
 <button id="b_id{{ $showroom->id }}" @if ($showroom !=null)
        value="{{ $showroom->user->business_phone_number ?
          $showroom->user->business_phone_number : $showroom->user->phone }}"
           onclick="change_text_to_phone({{ $showroom->id }})" @endif
          class="btn btn-success w-100 Pause-Redirect">
          <i class="fa fa-phone-alt me-2"></i>
          <span id="change_text{{ $showroom->id }}">Show Phone No.</span>
      </button>
        <a class="btn btn-primary w-100 mx-1 mx-xl-0 d-flex align-items-center justify-content-center" href="{{route('showroom-detail', $showroom->id)}}">Visit Showroom</a>
      </div>
    </div>
  </div>
</div>
@endforeach
<script>
    $(document).ready(function(){
        $(".Pause-Redirect").data('click', false);
        $(".Pause-Redirect").on('click', function()
        {
            $(".Pause-Redirect").data('click', true);
        });
    });
   function change_text_to_phone($car_id) {
            var $phone_number = document.getElementById('b_id' + $car_id).value;
            var x = document.getElementById("change_text" + $car_id);
            if (x.innerHTML === "Show Phone No.") {
                x.innerHTML = $phone_number;
            } else {
                x.innerHTML = "Show Phone No.";
            }
        }

    function RedirectToShowroom(id)
    {
    if($(".Pause-Redirect").data('click') == false)
    {
    window.location.href = "{{ route('showroom-detail','') }}/" + id;
    }
    $(".Pause-Redirect").data('click', false);
    }
</script>
