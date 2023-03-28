@extends('admin.layout.main')
@section('title', 'Dashboard ')
@section('content')


<div class="inner-content">


<div class="row">
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="counter-box shadow-sm">
            <div class="d-flex align-items-center uppersec">
                <h3 class="m-0 text-warning me-4">Pending Ads</h3>
                @if($data['pendingCount'] > 0)
                <img src="{{URL::asset('admin-panel/images/redcrc.png')}}" id="animate_dot">
                @endif
                <span class="counter ms-auto">{{$data['pendingCount']}}</span>
            </div>
            <hr class="mb-2 mt-3">
            <div class="d-flex">
                <a href="{{route('admin.pending-ads')}}">View Details</a>
                <i class="fa fa-chevron-right ms-auto"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="counter-box shadow-sm">
            <div class="d-flex align-items-center uppersec">
                <h3 class="m-0">Offers</h3>
                <div class="ms-auto d-flex align-items-center">
                    <div class="par-counter pending">
                        <span class="txt">Pending</span>
                        <span class="countr">{{$data['offersPending']}}</span>
                    </div>
                    <div class="par-counter accepted ms-2">
                        <span class="txt">Accepted</span>
                        <span class="countr">{{$data['offersAccepted']}}</span>
                    </div>
                    <div class="par-counter rejected ms-2">
                        <span class="txt">Rejected</span>
                        <span class="countr">{{$data['offersRejected']}}</span>
                    </div>
                </div>
            </div>
            <hr class="mb-2 mt-3">
            <div class="d-flex">
                <a href="{{route('admin.admin-car-offers')}}">View Details</a>
                <i class="fa fa-chevron-right ms-auto"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="counter-box shadow-sm">
            <div class="align-items-center uppersec">
                <h3 class="m-0">Showrooms</h3>
                <span class="counter ms-auto">{{$data['showRoomCount']}}</span>
            </div>
            <hr class="mb-2 mt-3">
            <div class="d-flex">
                <a href="{{route('admin.admin-showrooms')}}">View Details</a>
                <i class="fa fa-chevron-right ms-auto"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="counter-box shadow-sm">
            <div class="align-items-center uppersec">
                <h3 class="m-0">Sold Cars</h3>
                <span class="counter ms-auto">{{$data['soldCount']}}</span>
            </div>
            <hr class="mb-2 mt-3">
            <div class="d-flex">
                <a href="{{route('admin.admin-sold-cars')}}">View Details</a>
                <i class="fa fa-chevron-right ms-auto"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="counter-box shadow-sm">
            <div class="align-items-center uppersec">
                <h3 class="m-0">User</h3>
                <span class="counter ms-auto">{{$data['userCount']}}</span>
            </div>
            <hr class="mb-2 mt-3">
            <div class="d-flex">
                <a href="{{route('admin.admin-web-users')}}">View Details</a>
                <i class="fa fa-chevron-right ms-auto"></i>
            </div>
        </div>
    </div>
</div>




</div>
@endsection


@section('scripts')

<script>
    $(document).ready(function() {
        setInterval(function ()
        {
            $("#animate_dot").fadeOut(500);
            $("#animate_dot").fadeIn(500);
        }, 1000);

    });
</script>

@endsection
