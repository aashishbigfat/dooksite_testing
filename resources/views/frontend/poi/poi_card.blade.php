

   <div class="col-md-4 col-lg-3 mb-3">
    <a href="{{route('frontend.poi_details',['slug'=>$pointOfInterest->poi_url, 'id'=>$pointOfInterest->poiId])}}" target="_blank">
    <div class="card attraction_card">
       <img src="{{ $pointOfInterest['image'] ?? url('images/poi-no-image.jpg') }}"
            alt="{{ $pointOfInterest['poi_name'] }}" />
      <div class="attraction_card_body">
         <h6 class="m-0 p-0">{{ $pointOfInterest['poi_name'] }}</h6>
          <p class="p-0 m-0"><span class="text-white">{{$pointOfInterest['total_departures']}} Tours</span> <span class="text-white"> {{$pointOfInterest['featured_departure']}} Featured</span></p>
      </div>
    </div>
    </a>
  </div>