@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="inner-content">
    <div class="d-flex align-items-center">
        <h4 class="page-heading my-4">Target Tracker</h4>
    </div>

    @foreach ($admins as $admin)
        <div class="pending-ads-box mb-3">
            <div class="row">
                <div class="col-md-9 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="goshowroom-avatar me-3">
                            <img class="img-fluid" src="{{$admin->image}}" />
                        </div>
                        <div class="pending-ads-detail">
                            <h6 class="text-black mb-0">{{$admin->name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <div class="d-flex flex-column justify-content-evenly h-100 align-items-end">
                        <b>
                            Total Showroom Verified:
                            <span class="clr-primary">{{$admin->total_approved}}</span>
                        </b>
                        <b>
                            Total Showroom Verified Today:
                            <span class="clr-primary">{{$admin->today_approved}}</span>
                        </b>
                    </div>
                </div>
            </div>
        </div>
    @endforeach




</div>
@endsection
