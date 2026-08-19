@foreach($pointOfInterest as $key => $poi)
	 <div class="col-md-3 mt-3">
	 	<a href="{{route('frontend.poi_details',['slug'=>$poi->poi_url, 'id'=>$poi->poiId])}}" target="_blank">
        <div class="card shadow-sm rounded">
            <img src="{{$poi->image}}" alt="{{$poi->poi_name}}" >
                <div class="card-body">
                    <h6>{{$poi->poi_name}}</h6>
                    <img src="{{asset('assets/images/icons/dot.svg')}}" alt="1" class="px-2"><span class="text-danger">{{$poi->total_departures}} Tours</span>
                    <img src="{{asset('assets/images/icons/dot.svg')}}" alt="2" class="px-2"><span class="text-danger">{{$poi->featured_departure}} Featured</span>
                  </div>
        </div>
       </a>
      </div>
@endforeach