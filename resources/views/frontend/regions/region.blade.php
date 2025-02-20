@extends('frontend.layouts.master')
@push('title') {{$region_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$region_header->meta_keywords}}">
<meta name="description" content="{{$region_header->meta_description}}">@endpush
@section('content')


  <div class="container">
    <div class="row mt-4 mb-4">
      <!-- heading -->
       <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Regions</p>
       </div>
        <div class="col-md-9">
         <h5 class="text-capitalize">Create your own path to adventure!</h5>
           <p>What a wonderful world! Let’s go exploring!</p>

         <div class="region_container">
              @foreach($regions as $key => $region)
                <div class="region_card">
                    <div class=" shadow-sm rounded  position-relative">
                         <div class="destination-sec-country ml-2">                                      
                            <div class="destination">
                               <ul class="list-inline p-0 m-0">
                                @foreach($region->experiences as $key => $experience)
                                <li class="list-inline-item px-2"><a href="{{url('/')}}/{{$experience->slug_url}}"
                                    class=""><span>{{$experience->experience_name}}</span></a></li>
                                @endforeach
                              </ul>
                            </div>                                
                        </div> 
                        <img class="img-fluid" src="{{$region->image}}" alt="Card image cap">
                        <div class=" dest_card">
                           <div class="row test align-items-center dest">
                            <div class="col-md-12">
                             <h5 class="m-0"><a href="{{url('/')}}/{{$region->slug_url}}" style="color:#fff;" class="">{{$region->region_name}}</a></h5> 
                             <ul class="list-inline mb-0 country-list">
                                @foreach($region->countries as $key => $country)
                                <li class="list-inline-item m-0"><a href="{{url('/')}}/{{$country->slug_url}}"
                                    class=""><span>{{$country->country_name}}</span></a></li>
                                @endforeach
                              </ul>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
       </div>
       <div class="col-md-3">
          <div class="shadow p-3 mb-3 bg-white rounded">
              <h5 class="px-2">Book With Confidence</h5>
              <p class="color_gray"><img src="{{asset('assets/images/icons/thumbs-up.png')}}" alt="" class="px-2"> No-hassle best price guarantee</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> Customer care available 24/7</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/star.png')}}" alt="" class="px-2"> Hand-picked Tours & Activities</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/crosshair.png')}}" alt="" class="px-2"> Free Travel Insureance</p>
  
            </div>
  
            <div class="shadow p-3  bg-white rounded">
              <h5 class="px-2">Need Help?</h5>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> +911140001000</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/mailbox.png')}}" alt="" class="px-2"> sales@dooktravels.com</p>
              <p class="color_gray"><img src="{{asset('assets/images/icons/chat.png')}}" alt="" class="px-2"> +918368513675</p>
            </div>
       </div>

   </div>
   <hr>
   <div class="row">
    <div class="col-md-12">
         <h5 class="text-capitalize">Leave only footprints and take only memories.</h5>
           <p>It is about the journey not the destination. Start your journey with a single click!</p>

         <div class="row">
          @foreach($departures as $departure)
          <div class="col-md-3 mb-4">
           @include('frontend.common.tourpackage')
         </div>
           @endforeach
         </div>
       </div>
       <hr>
       <div class="col-md-12">
         {!! $region_header->description !!}
       </div>
   </div>
</div>



@include('frontend.common.testimonial')
@endsection