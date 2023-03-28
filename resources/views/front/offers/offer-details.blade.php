@extends('front.layout.main')
@section('content')
<section class="section-space py-5 review-header">
    <div class="container-xl">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3 class="mb-0">Recieved Offers</h3>
        </div>
    </div>
</section>
<section id="top-pics" class="section-space pb-3">
    <div class="container-xl">
        <div class="recieved-offer-box">
            @foreach ($offers as $offer)
            <div class="row align-items-center mb-4">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="recieved-offer-avatar me-2">
                            <img src="{{$offer->user->image}}" class="w-100 h-100">
                        </div>
                        <div class="db-body">
                            <h5 class="m-0 mb-1 mt-2 mt-lg-0">
                                {{$offer->user->first_name}} {{$offer->user->last_name}}</h5>
                            <p class="mb-0 text-secondary fw-semibold">Contact No.: {{$offer->user->phone}}</p>
                            <p class="mb-0 text-secondary ">Offered on : {{date('F d. y, h:i a', strtotime($offer->created_at))}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-column h-100 justify-content-lg-between align-items-start align-items-lg-end">
                        <p class="text-danger text-end">Offered Price PKR. {{$offer->amount}}</p>
                        @if($offer->status == 'PENDING')
                        @if($offer->counter_amount != "")
                        <p class="text-primary text-end">Counter Offer: PKR {{$offer->counter_amount}}</p>
                        @else
                        <div class="d-flex mb-2 justify-content-end">
                            <form method="POST" action="{{route('counter.offer', $offer->id)}}" class="d-flex">
                            {{csrf_field()}}
                            <input type="number" class="form-control mx-2" placeholder="Price" name="counter_amount" min="1" oninput="check(this)">
                            <button class="btn btn-primary">Counter offer</button>
                            </form>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-success mx-2" href="{{route('change.offer.status',[$offer->id,'ACCEPTED'])}}">Accept Offer</a>
                            <a class="btn btn-danger" href="{{route('change.offer.status',[$offer->id,'REJECTED'])}}">Reject offer</a>
                        </div>
                        @endif
                        @else
                        @if ($offer->status == "ACCEPTED")
                            <p class="text-success text-end">Offer accepted</p>
                        @else
                            <p class="text-danger text-end">Offer rejected</p>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
</section>
@endsection
<script>
    function check(input) {
     var price = "<?php echo $offer->amount ?>";  
    if (Number(input.value) < price ) {
      input.setCustomValidity('your offer must be equel or greater then car price.');
    } 
    else {
      input.setCustomValidity('');
    }
  }
</script>
