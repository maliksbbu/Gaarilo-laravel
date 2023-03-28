@foreach ($car_reviews as $car_review)
<div class="d-flex align-items-center mb-3">
    <div class="reviewer-img me-3">
        <img src="{{ $car_review->makes->logo }}">
    </div>
    <div class="reviewer-detail">
        <h3 class="mb-1">{{ $car_review->makes->name . ' ' . $car_review->models->name }}</h3>
        <p class="mb-1">
            {{ $car_review->year . ' ' . $car_review->makes->name . ' ' . $car_review->models->name }}</p>
        <div class="rating justify-content-start mb-1">
            <?php echo return_rating_html(round($car_review->exterior + $car_review->interior + $car_review->comfort + $car_review->fuel + $car_review->performance + $car_review->parts)); ?>
        </div>
        <p class="m-0 fw-light">Posted by
            {{ $car_review->user->first_name . ' ' . $car_review->user->last_name }} on
            {{ date('M d Y', strtotime($car_review->created_at)) }}
        </p>
    </div>
</div>
<p>{{ $car_review->description }}</p>
<div class="d-flex align-items-center flex-wrap mb-3">
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Exterior</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->exterior >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->exterior >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->exterior >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->exterior >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->exterior >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Interior</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->interior >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->interior >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->interior >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->interior >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->interior >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Comfort</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->comfort >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->comfort >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->comfort >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->comfort >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->comfort >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
</div>

<div class="d-flex align-items-center flex-wrap mb-3">
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Fuel Economy</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->fuel >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->fuel >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->fuel >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->fuel >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->fuel >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Performance</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->performance >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->performance >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->performance >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->performance >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->performance >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
    <div class="d-flex align-items-center me-5 mb-3">
        <h4 class="m-0 me-2 fw-semibold">Part Availability</h4>
        <div class="rating justify-content-start">
            <span class="fa fa-star {{ $car_review->parts >= 1 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->parts >= 2 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->parts >= 3 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->parts >= 4 ? 'checked' : '' }}"></span>
            <span class="fa fa-star  {{ $car_review->parts >= 5 ? 'checked' : '' }}"></span>
        </div>
    </div>
</div>
<hr>
@endforeach
