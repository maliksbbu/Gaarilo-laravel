@if(count($cars) > 0)
@foreach ($cars as $car)
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="detail-box">
      <div class="db-top">
        <img src="{{$car->image}}" class="w-100" onclick="car_detail({{$car->id}})"  style="cursor: pointer; rotate:{{$car->rotation}}deg;">
        <div class="imgcounter"  onclick="car_detail({{$car->id}})"  style="cursor: pointer;">
          <span class="me-2">{{$car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count() }}</span>
          <img src="{{URL::asset('front/images/images-icon.png')}}">
        </div>
        @if($car->status == 'APPROVED')
        <div class="dbprice review-dbprice">
          @if($car->CountPendingOffer() > 0)
          <a href="{{route('offer.details', $car->id)}}"><p style="cursor: pointer;" class="m-0">{{$car->CountPendingOffer()}} New Offers</p></a>
          @endif
        </div>
        @endif

        @if($car->status == 'SOLD')
        <div class="dbprice review-dbprice" onclick="car_detail({{$car->id}})">
          <p class="m-0">Sold in PKR. {{number_format($car->sold_price)}}</p>
        </div>
        @endif

        @if($car->status == 'REJECTED')
        <div class="ad-rejected-btn po-link">
          <i class="fa fa-exclamation-circle fa-lg popover-trigger me-2"></i>
          Ad Rejected
        </div>
        <div class="po-markup hidden">
          <div class="po-content">
              <div class="po-body">
                <h4 class="text-danger mb-1">Ad Rejected</h4>
                  <p class="mb-3">We have found issues in this Ad. Please review and submit again</p>
                      <a type="button" href="{{route('edit-ad', $car->id)}}" class="btn btn-lg btn-primary w-100">Review Ad</a>

              </div><!-- ./po-body -->
          </div>  <!-- ./po-content -->
        </div><!-- ./po-markup -->
        @endif
      </div>
      <div class="db-body" onclick="car_detail({{$car->id}})">
        <h5 class="m-0 mb-3">
        {{$car->brand->name}} {{$car->model->name}} {{$car->car_year}}
        </h5>
        <div class="d-flex align-items-center mb-3">
          <h3 class="m-0">PKR. {{number_format($car->price_range)}}</h3>
        </div>
        <h6 class="mb-3">{{$car->city->name}}</h6>

        <div class="m-0 mb-2 sepreator-text d-flex flex-wrap">
          <div><span class="me-3">{{$car->car_year}}</span>  |</div>  <div><span class="mx-3 ps-2"> {{$car->mileage}} km  </span>  </div> <div>|<span class="ms-3">{{$car->engine_type}}</span></div>
        </div>
        <div class="m-0 mb-3 sepreator-text d-flex flex-wrap">
            <div></div>  <div><span class="me-3">{{$car->engine_capacity}}cc </span>   |</div>   <div><span class="ms-3">{{$car->transmission}}</span></div>
        </div>
        <div class="mb-3 text-muted mb-3">Last Updated: {{date('m/d/Y',
                                            strtotime($car->updated_at))}}</div>

        <div class="d-flex align-items-center">
          <span class="text-muted me-3">
            <i class="fa fa-eye me-2"></i>
            {{$car->ad_views}} Views
          </span>
          <span class="text-muted">
            <i class="fa fa-phone-alt me-2"></i>
            {{$car->phone_views}} Views
          </span>
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
