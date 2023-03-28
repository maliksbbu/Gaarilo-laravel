@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">

          <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">Sold Cars</h4>
          </div>

          @if(count($cars) > 0)
      @foreach ($cars as $car)
       <div class="pending-ads-box mb-3">
        <div class="row">
          <div class="col-md-9 mb-3 mb-md-0">
            <div class="d-flex align-items-center">
              <div class="pending-ads-avatar me-3">
                <img class="h-100 img-fluid" src="{{$car->image}}">
              </div>
              <div class="pending-ads-detail">
                <h5 class="mb-0 text-primary">{{$car->brand->name}} {{$car->model->name}} {{$car->car_year}}</h5>
                <p class="mb-0 fw-semibold">{{$car->car_year}}</p>
                <p class="mb-0 fw-semibold">
                  Sold Via GaariLo: <b class="text-primary">Yes</b>
                </p>
                <!-- <p class="fw-semibold mb-0">Buyers Name & contact: <b class="text-primary">Tayyab Farhan |  0300-1234567</b></p> -->
                <p class="mb-0 fw-semibold">Sold Date: <b class="text-primary">{{date("d-m-Y", strtotime($car->updated_at))}}</b></p>

              </div>
            </div>
          </div>
          <div class="col-md-3 text-end">
            <div class="d-flex flex-column justify-content-evenly h-100 align-items-end showrooms-card-right">
              <h5 class="mb-0 text-primary">Actual Price:  <b>PKR. {{number_format($car->price_range)}}</b></h5>
              <h5 class="text-warning">Sold Price: <b>PKR. {{number_format($car->sold_price)}} Lac</b></h5>
              <div class="showroom-link d-flex align-items-center">
                <div class="soldcar-avatar me-2">
                @if(!empty($car->showroom))  
                <a href="{{url('showroom-detail').'/'.$car->showroom->id}}"><img  class="img-fluid w-100 h-100" src="@if($car->showroom != null){{$car->showroom->logo}} @endif"></a>
                 @endif
              </div>
                <div>
                @if(!empty($car->showroom->user))  
                <a>{{$car->showroom->name}}</a>
                <p class="m-0">{{$car->showroom->user->phone}}</p>
                @endif
                </div>
              </div>
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


    </div>
@endsection
