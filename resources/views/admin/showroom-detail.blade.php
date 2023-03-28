@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')

    <div class="inner-content">

        <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">{{$showroom->name}}</h4>
        </div>


        @foreach ($cars as $car)
        <div class="pending-ads-box mb-3">
            <div class="row">
                <div class="col-md-9 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="goshowroom-avatar me-3">
                            <img class="img-fluid" src="{{$car->image}}">
                        </div>
                        <div class="pending-ads-detail">
                            <h5 class="mb-1">{{$car->brand->name}} {{$car->model->name}} @if($car->version != null) {{$car->version->name}} @endif</h5>
                            <h6 class="text-black">{{$car->car_year}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <div class="d-flex flex-column justify-content-evenly h-100 align-items-end">
                        @switch($car->status)
                            @case("SOLD")
                                <button class="btn btn-warning me-2">SOLD</button>
                                @break
                            @case("APPROVED")
                                <button class="btn btn-success me-2">Active</button>
                                @break
                            @case("PENDING")
                                <button class="btn btn-danger me-2">Pending</button>
                                @break
                            @case("REJECTED")
                                <button class="btn btn-danger me-2">Rejected</button>
                                @break


                            @default

                        @endswitch
                    </div>
                </div>
            </div>
        </div>
        @endforeach




    </div>
@endsection
