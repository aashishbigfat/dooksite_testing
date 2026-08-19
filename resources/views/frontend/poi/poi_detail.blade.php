@extends('frontend.layouts.master')
@push('title') {{$poiID->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$poiID->meta_keywords}}">
<meta name="description" content="{{$poiID->meta_description}}">
<meta property="og:description" content="{{$poiID->meta_description}}">
<meta name="twitter:description" content="{{$poiID->meta_description}}">
@endpush
@section('content')
  
  <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Point of Interests</span>
          </div>
        </div>
      </div>
    </section>
    
  <div class="container">
    <div class="row mt-4 mb-4">
    <!--    <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Point of Interests</p>
        </div> -->
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
             <div class="sectionHeading heading">
                   <h2>{{$poiID->poi_name}}</h2>
                   </div>
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
        <hr>
          <div class="row">
              <div class="col-md-12 mb-4">
                 <div class="sectionHeading mt-5 heading">
                   <h2>Top {{$poiID->destination_name}} Tour Packages</h2>
               </div>
                <p class="color_gray">Top tours featuring  {{$poiID->poi_name}}</p>
              </div>   
              <div class="tours-grid" id="tourPackages">
                @include('frontend.common.tourpackage')
            </div>
            
            @if($departures->hasMorePages())
                <div class="col-md-12 mt-4 text-center">
                    <div id="loader" class="loader">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Packages</button>
                </div>
            @endif
              
               <div class="col-md-12 mb-4 mt-4">
               <div class="sectionHeading mt-5 heading">
                   <h2>Top {{$poiID->destination_name}} Tourist Attractions</h2>
               </div>
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

 <script>
        let page = 2;
        $('#loadMoreBtn').click(function() {
            $('#loader').show();
            $('#loadMoreBtn').hide();
            
            let urlParams = new URLSearchParams(window.location.search);
            urlParams.set("page", page);
    
            let url = "{{ url()->current() }}?" + urlParams.toString();
    
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#tourPackages').append(data.view);
                    page++;
                    if (!data.hasMorePages) {
                        $('#loadMoreBtn').hide();
                    }
                    $('#loader').hide();
                    if (data.hasMorePages) {
                        $('#loadMoreBtn').show();
                    }
                },
                error: function() {
                    alert('Error loading more packages');
                    $('#loader').hide();
                    $('#loadMoreBtn').show();
                }
            });
        });
    </script>
@endsection
