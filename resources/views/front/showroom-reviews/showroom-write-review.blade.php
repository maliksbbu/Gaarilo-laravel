@extends('front.layout.main')
@section('content')
<section class="section-space mt-80px write-review" >
        <div class="container-xl">
        <h2 class="mb-5">Write a Review</h2>

        <div class="review-box">
          <h5 class="mb-5 text-center text-lg-start">Your Opinion</h5>

          <div class="row">
            <div class="col-sm-8 offset-2">
            <form action="{{ action('Web\ShowroomReviewsController@store') }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('POST') }}

            <input type="hidden" name="showroom_id" value="{{$showroom_id}}" />
            <input type="hidden" name="dealing_rating" id="dealing_rating" value="{{old('dealing_rating')}}" />
            <input type="hidden" name="selection_rating" id="selection_rating" value="{{old('selection_rating')}}" />
            <input type="hidden" name="service_rating" id="service_rating" value="{{old('service_rating')}}" />

              <div class="row mb-3">
                <div class="col-lg-3 text-lg-end">
                  <label class="mb-3 mb-lg-0">Rating<sup class="required">*</sup></label>
                </div>
                <div class="col-lg-9">
    
                  <div class="row mb-3">
                    <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                      <span>Dealing</span>
                    </div>
                    <div class="col-xxl-6 col-lg-7">
                      <div class="rating mb-2 justify-content-start dealing-rating">
                        <span class="fa fa-star {{ old('dealing_rating') >= 1 ? 'checked' : '' }}" data-value="1"></span>
                        <span class="fa fa-star {{ old('dealing_rating') >= 2 ? 'checked' : '' }}" data-value="2"></span>
                        <span class="fa fa-star {{ old('dealing_rating') >= 3 ? 'checked' : '' }}" data-value="3"></span>
                        <span class="fa fa-star {{ old('dealing_rating') >= 4 ? 'checked' : '' }}" data-value="4"></span>
                        <span class="fa fa-star {{ old('dealing_rating') >= 5 ? 'checked' : '' }}" data-value="5"></span>
                      </div>
                    </div>
                  </div>
    
                  <div class="row mb-3">
                    <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                      <span>Vehicle Selection</span>
                    </div>
                    <div class="col-xxl-6 col-lg-7">
                    <div class="rating mb-2 justify-content-start selection-rating">
                        <span class="fa fa-star {{ old('selection_rating') >= 1 ? 'checked' : '' }}" data-value="1"></span>
                        <span class="fa fa-star {{ old('selection_rating') >= 2 ? 'checked' : '' }}" data-value="2"></span>
                        <span class="fa fa-star {{ old('selection_rating') >= 3 ? 'checked' : '' }}" data-value="3"></span>
                        <span class="fa fa-star {{ old('selection_rating') >= 4 ? 'checked' : '' }}" data-value="4"></span>
                        <span class="fa fa-star {{ old('selection_rating') >= 5 ? 'checked' : '' }}" data-value="5"></span>
                      </div>
                    </div>
                  </div>
    
                  <div class="row">
                    <div class="col-xxl-4 col-lg-5 text-lg-end mb-2 mb-lg-0">
                      <span>Quality Of Service</span>
                    </div>
                    <div class="col-xxl-6 col-lg-7">
                    <div class="rating mb-2 justify-content-start service-rating">
                        <span class="fa fa-star {{ old('service_rating') >= 1 ? 'checked' : '' }}" data-value="1"></span>
                        <span class="fa fa-star {{ old('service_rating') >= 2 ? 'checked' : '' }}" data-value="2"></span>
                        <span class="fa fa-star {{ old('service_rating') >= 3 ? 'checked' : '' }}" data-value="3"></span>
                        <span class="fa fa-star {{ old('service_rating') >= 4 ? 'checked' : '' }}" data-value="4"></span>
                        <span class="fa fa-star {{ old('service_rating') >= 5 ? 'checked' : '' }}" data-value="5"></span>
                      </div>
                    </div>
                  </div>
    
    
                </div>
                </div>
                <div class="row mb-4">
                <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                  <label>Review Title<sup class="required">*</sup></label>
                </div>
                <div class="col-lg-9">
    
                  <input type="text" class="form-control" maxlength="75" name="review_title" id="review_title" value="{{old('review_title')}}">
                  </div>
                  </div>
                  <div class="row mb-5">             
                     <div class="col-lg-3 text-lg-end mb-3 mb-lg-0">
                    <label>Description<sup class="required">*</sup></label>
                  </div>
                  <div class="col-lg-9">
                    <textarea class="form-control" rows="10" name="review_description" id="review_description">{{old('review_description')}}</textarea>
                    </div>
              </div>
    
    
              <div class="d-flex justify-content-end">
              <button class="btn btn-primary submit-btn">Submit Review</button>
            </div>
            </div>
            </form>
          </div>

        

        </div>

      </div>
      </section>
@endsection
@section('scripts')
<script>
$(document).on('click', '.dealing-rating span', function(e) {
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('span.fa-star');
    selectRatingStars(stars, onStar);
    $('#dealing_rating').val(onStar);
    console.log(onStar);
    
});

$(document).on('click', '.selection-rating span', function(e) {
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('span.fa-star');
    console.log(stars);
    selectRatingStars(stars, onStar);
    $('#selection_rating').val(onStar);
    console.log(onStar);
    
});


$(document).on('click', '.service-rating span', function(e) {
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('span.fa-star');
    console.log(stars);
    selectRatingStars(stars, onStar);
    $('#service_rating').val(onStar);
    console.log(onStar);
    
});

function selectRatingStars(stars, onStar){
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('checked');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('checked');
    }
}


</script>
@endsection