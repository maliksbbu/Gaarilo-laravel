
  <div class="row mb-5">
  @foreach($showrooms as $showroom)
  <div class="col-xl-4 col-lg-6 mb-4">

  <div class="showroom-box" onclick="RedirectToShowroom('{{$showroom->id}}')">
      <img src="{{$showroom->logo}}" class="img-fluid showroom-logo overflow-hidden rounded-circle">
      <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 260px;">
      <h5>{{$showroom->name}} @if($showroom->verified == 'YES')<img src="{{URL::asset('front/images/green-small-tick.png')}}">@endif</h5></div>
      <div class="rating mb-2">
        <span class="me-3">{{number_format($showroom->rating,1)}}</span>
          @for ($i = 0; $i < 5; $i++)
              @if($i < $showroom->rating)
              <span class="fa fa-star checked"></span>
              @else
              <span class="fa fa-star"></span>
              @endif
          @endfor
      </div>
      <h3 class="mb-2">{{city_name($showroom->city_id)}}</h3>
      <span class="d-block text-center">{{$showroom->count_review}} Reviews</span>
      <hr class="my-2">
      <p class="mb-4">
        {{$showroom->address}}
      </p>
      <button style="margin-bottom: 5px;" id="b_id{{ $showroom->id }}" @if ($showroom != null)
        value="{{ $showroom->user->business_phone_number ?
          $showroom->user->business_phone_number : $showroom->user->phone }}"
           onclick="change_text_to_phone({{ $showroom->id }})" @endif
          class="btn btn-success w-100 Pause-Redirect">
          <i class="fa fa-phone-alt me-2"></i>
          <span id="change_text{{ $showroom->id }}">Show Phone No.</span>
      </button>

      <a class="btn btn-primary w-100 d-flex align-items-center justify-content-center" href="{{route('showroom-detail', $showroom->id)}}">Visit Showroom</a>
    </div>

</div>
@endforeach
</div>
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
