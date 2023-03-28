@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')

<div class="inner-content">

          <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">Car Offers</h4>
          </div>

          @if(count($offers) > 0)
      @foreach ($offers as $offer)
       <div class="offers-box mb-3">
        <div class="row">
          <div class="col-lg-6 mb-3 mb-md-0">
              <div class="offer-detail d-flex flex-column justify-content-evenly h-100">

                <div class="d-flex align-items-center mb-0">
                  <h5 class="me-2 mb-0">{{$offer->car->brand->name}} {{$offer->car->model->name}} {{$offer->car->car_year}}</h5>
                  @if($offer->status == 'PENDING')
                  <span class="badge badge-warning">{{$offer->status}}</span>
                  @elseif($offer->status == 'REJECTED')
                  <span class="badge badge-danger">{{$offer->status}}</span>
                  @else
                  <span class="badge badge-success">{{$offer->status}}</span>
                  @endif
                </div>
                @if($offer->user->is_business == 'YES')
                <div class="showroom-link d-flex align-items-center">
                  <div class="soldcar-avatar me-2">
                  @if(!empty($offer->user->showroom))
                  <a href="{{url('showroom-detail') .'/'. $offer->user->showroom->id}}"><img class="img-fluid h-100 w-50" src="@if($offer->user->showroom != null){{$offer->user->showroom->logo}} @endif"></a>
                  @endif
                  </div>
                  <div>
                  @if(!empty($offer->user->showroom))
                  <a>{{$offer->user->showroom->name}}</a>
                  @endif
                  </div>
                </div>
                @endif  
              <h6 class="mb-1">{{$offer->user->first_name}} {{$offer->user->last_name}}</h6>
              <p class="mb-0 offers-contact">{{$offer->user->phone}}</p>
              </div>
              </div>
          <div class="col-lg-6 text-end">
            <div class="d-flex flex-column justify-content-evenly h-100 align-items-end">
           <p class="mb-0 text-primary">Actuall Price: <b>PKR. {{number_format($offer->car->price_range)}}</b></p>
           @if($offer->amount != 0)
           <p class="mb-0 text-danger">Offer Price: <b>PKR. {{number_format($offer->amount)}}</b></p>
           @endif
           @if($offer->counter_amount != NULL)
           <p class="mb-0 text-primary">Counter Price: <b>PKR. {{number_format($offer->counter_amount)}}</b></p>
           @endif
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
