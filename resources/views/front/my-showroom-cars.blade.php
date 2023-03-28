@extends('front.layout.main')
@section('content')
<section class="section-space py-5 review-header">
        <div class="container-xl">
          <div class="d-flex justify-content-center align-items-center flex-column">
          <img src="{{$data['showroom']->logo}}" class="review-showroom-thumb">
          <h3 class="mb-0">{{$data['showroom']->name}}</h3>
          <p class="mb-0 d-flex">Year of service : {{$data['showroom']->service_year}}
          @php
              if(empty(Session::get('user')->id))
                $user_id =  0;
              else
                $user_id =  Session::get('user')->id;
          @endphp
          @if($data['showroom']->user_id ==  $user_id)
          <a class="edit-prof" href="javascript:OpenEditModal()"><span class="icon-edit ms-2"><img src="{{URL::asset('front/images/edit.svg')}}"></span></a>
          @endif
          </p>
          <p class="mb-0">Member since : {{date('Y', strtotime($data['showroom']->updated_at))}}</p>
          <div class="rating mb-2 justify-content-start my-3">
          <?php echo return_rating_html($data['showroom']->rating); ?>
          </div>
          <span class="reviews mb-5">{{$data['showroom']->getCountReviewAttribute()}} Reviews</span>

          <a href="{{url('user-profile')}}" class="edit-prof d-flex align-items-center">Edit Profile <span class="icon-edit ms-2"><img src="{{URL::asset('front/images/edit.svg')}}"></span></a>
        </div>
      </div>
      </section>



      <section id="top-pics" class="section-space pb-3">
        <div class="container-xl">

          <div class="d-flex align-items-center mb-3">
            <h2 class="reviewheading mb-4">My Cars</h2>
            <a href="{{route('offer')}}" class="button btn btn-danger ms-auto btn-offer"><i class="fa fa-exclamation-circle fa-lg me-1"></i> {{ $data['showroom_car_offers']}} New Offers</a>

          </div>

          <div class="tabs-box mb-5">
            <a class="active showroom-active-tab">Active Cars ({{count( $data['active_cars'])}})</a>
            <a class="showroom-pending-tab">Pending Cars ({{count($data['pending_cars'] )}})</a>
            <a class="showroom-sold-tab">Sold Cars ({{count($data['sold_cars'])}})</a>
          </div>


          <div class="row showroom-active-cars">

          @include('front.my-showroom-cars-grid', [
              'cars' =>$data['active_cars']
          ])
          </div>

          <div class="row showroom-pending-cars" style="display:none">

          @include('front.my-showroom-cars-grid', [
              'cars' => $data['pending_cars']
          ])
          </div>

          <div class="row showroom-sold-cars" style="display:none">

          @include('front.my-showroom-cars-grid', [
              'cars' => $data['sold_cars']
          ])
          </div>
        </div>
      </section>


      <div class="modal fade show popup-small"  id="edit-vtype-popup" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit year of service</h5>
              <button type="button" onclick="closemodel()" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form action="{{route('my-showroom-edit_year')}}" method="POST">
            {{csrf_field()}}
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Year of service</label>
                <input type="number" class="form-control" id="year_of_service" name="year_of_service" placeholder="Enter year of service">
                <input hidden type="number" class="form-control" id="id" name="id" value="{{$data['showroom']->id}}">

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="closemodel()" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
<script>
$(document).on('click', '.showroom-active-tab', function(e) {

  $(this).siblings('a').removeClass('active');

  $(this).addClass('active');

  $('.showroom-pending-cars').hide();
  $('.showroom-sold-cars').hide();
  $('.showroom-active-cars').show();

  e.preventDefault();
});


$(document).on('click', '.showroom-pending-tab', function(e) {

  $(this).siblings('a').removeClass('active');

  $(this).addClass('active');

  $('.showroom-active-cars').hide();
  $('.showroom-sold-cars').hide();
  $('.showroom-pending-cars').show();

  e.preventDefault();
});

$(document).on('click', '.showroom-sold-tab', function(e) {

  $(this).siblings('a').removeClass('active');

  $(this).addClass('active');

  $('.showroom-active-cars').hide();
  $('.showroom-pending-cars').hide();
  $('.showroom-sold-cars').show();

  e.preventDefault();
});

function OpenEditModal() {

$("#edit-vtype-popup").modal("show");
}
function closemodel() {
    $("#edit-vtype-popup").modal("hide");
}

function car_detail(car_id) {

    window.location.href = "{{url('car-detail')}}"+"/"+car_id;
}
function car_offer(car_id) {

window.location.href = "{{url('offer-details')}}"+"/"+car_id;
}
</script>
@endsection
