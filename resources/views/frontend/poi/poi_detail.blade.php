@extends('frontend.layouts.master')
@push('title') {{$poiID->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$poiID->meta_keywords}}">
<meta name="description" content="{{$poiID->meta_description}}">@endpush
@section('content')
  
  <div class="container">
    <div class="row mt-4 mb-4">
       <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Point of Interests</p>
        </div>
       <div class="col-md-9 mt-3" style="position: relative;">
        <div>
            <img src="{{$poiID->image}}"
                alt="{{$poiID->poi_name}}" style="width:100%;height: 350px;">
            <div class="text-block px-2 py-2 w-100 position-relative" style="bottom: 56px;">
                <h6 class="m-0 p-0">{{$poiID->poi_name}}, {{$poiID->destination_name}} ({{$poiID->country_name}})</h6>
                 @if($poiID->address != null || $poiID->address != "")
                   <p class="p-0 m-0">{{$poiID->address}}</p>              
                 @endif                
            </div> 
          </div>
          <h4>Description:</h4>
            <hr>
            <h6>{{$poiID->poi_name}}</h6>
              @if($poiID->description != null || $poiID->description != "")
                <p class="mb-2">{{$poiID->description}}</p>
              @endif
              @if($poiID->latitude != null || $poiID->latitude != "")

                    <span>Latitude: {{$poiID->latitude}}</span>
                    <span>Longitude :{{$poiID->longitude}}</span>

              @endif
              @if($poiID->openhours != null || $poiID->openhours != "")     
                <p>{{$poiID->openhours}}</p>
              @endif
              @if($poiID->rating != null || $poiID->rating != "")
              <div class="d-flex align-items-center rating">
                <i class="mdi mdi-star"></i>
                @for($i = 1 ; $i<=$poiID->rating; $i++)
                <i class="mdi mdi-star-outline starPrint"></i>
                @endfor
              </div>
              @endif

          <div class="row">
              <div class="col-md-12 mb-4">
                <h4>Top {{$poiID->destination_name}} Tour Packages</h4>
                <p class="color_gray">Top tours featuring  {{$poiID->poi_name}}</p>
              </div>   
               @foreach($departures as $departure)
              <div class="col-md-4 mb-4">          
                @include('frontend.common.tourpackage')
              </div>
              @endforeach
              <div class="col-md-12 mt-4 d-flex">
                <ul style="list-style-type: none;" class="p-0 d-flex justify-content-center" id="pagination">
                    <!-- Previous Button -->
                    @if ($departures->onFirstPage())
                        <li><a href="javascript:void(0)" class="border p-2 text-dark rounded text-white bg-secondary" id="prev" disabled>&lt;</a></li>
                    @else
                        <li><a href="{{ $departures->previousPageUrl() }}" class="border p-2 text-dark rounded text-white bg-danger" id="prev">&lt;</a></li>
                    @endif

                    <!-- Next Button -->
                    @if ($departures->hasMorePages())
                        <li><a href="{{ $departures->nextPageUrl() }}" class="border p-2 text-dark rounded mx-2 text-white bg-danger" id="next">&gt;</a></li>
                    @else
                        <li><a href="javascript:void(0)" class="border p-2 text-dark rounded mx-2 text-white bg-secondary" id="next" disabled>&gt;</a></li>
                    @endif
                </ul>
               </div>
              
               <div class="col-md-12 mb-4 mt-4">
                <h4>Top {{$poiID->destination_name}} Tourist Attractions</h4>
              </div> 
                @foreach($related_pois as $pointOfInterest)
                 @include('frontend.poi.poi_card')
                    @endforeach
          </div>
        </div>
        <div class="col-md-3">
          <div class="shadow p-3 mb-3 bg-white rounded">
              <h6 class="px-2">Book With Confidence</h6>
              <p class="color_gray"><img src="{{asset('assets/images/icons/thumbs-up.png')}}" alt="" class="px-2"> No-hassle best price guarantee</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> Customer care available 24/7</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/star.png')}}" alt="" class="px-2"> Hand-picked Tours & Activities</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/crosshair.png')}}" alt="" class="px-2"> Free Travel Insureance</p>
  
            </div>
  
            <div class="shadow p-3  bg-white rounded">
              <h6 class="px-2">Need Help?</h6>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> +911140001000</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mailbox.png')}}" alt="" class="px-2"> sales@dooktravels.com</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/chat.png')}}" alt="" class="px-2"> +918368513675</p>
            </div>
          </div>
      </div>
  </div>

   @include('frontend.common.testimonial')

   <script type="text/javascript">
$(document).ready(function() {
    // Handle Previous button click
    $('#prev').on('click', function() {
        var prevPage = "{{ $departures->previousPageUrl() }}";
        if (prevPage) {
            loadDepartures(prevPage);
        }
    });

    // Handle Next button click
    $('#next').on('click', function() {
        var nextPage = "{{ $departures->nextPageUrl() }}";
        if (nextPage) {
            loadDepartures(nextPage);
        }
    });

    function loadDepartures(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                // Update the content with the new data (you would need to adjust this)
                $('#departures-container').html(response);
            }
        });
    }
});


   </script>
@endsection
