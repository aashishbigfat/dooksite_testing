@foreach($reviews as $review)
  <div class="col-md-6 mb-3">      
      <div class="review-body card px-3 pt-2 ">
        <p class="review-body-content">
          {!! $review->description !!}
        </p>
      
      <div class="row border-top p-2">
          <div class="col-md-2">
              <img src="{{asset('assets/images/avatar5.png')}}" width="40" alt="{{$review->name}}" class="rounded-circle">
          </div>
          <div class="col-md-10" >
          {{$review->name}}
          <p id="full-stars-example" class="mb-0 rating-group">
            @for($i = 1; $i <= $review->rating; $i++)
            <span style="float: left;">
              <label aria-label="1 star" for="rating3-1" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            </span>
            @endfor
          </p>
        </div>
      </div>
      </div>
  </div>
@endforeach