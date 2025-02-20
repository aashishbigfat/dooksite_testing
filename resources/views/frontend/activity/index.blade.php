@extends('frontend.layouts.master')
@push('title') {{$activity_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$activity_header->meta_keywords}}">
<meta name="description" content="{{$activity_header->meta_description}}">@endpush
@section('content')

  <div class="container">
    <div class="row mt-4 mb-4">
      <!-- heading -->
       <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Activities</p>
       </div>
       <div class="col-md-9">
         <h5 class="text-capitalize">Explore and Choose Activities</h5>
         <p>Customize your tour package based on selected activities</p>

         <div class="row">
          @foreach($activities as $activity)
          <div class="col-md-4">
           @include('frontend.common.activity_card')
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
    <!-- packages -->
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <h5 class="text-capitalize">Chill out and relax around the world</h5>
        <p>Chill out and relax around the world</p>
      </div>
      <div class="col-md-12">
        <div class="row">
          @foreach($departures as $departure)
          <div class="col-md-3 mb-4">
           @include('frontend.common.tourpackage')
         </div>
           @endforeach
         </div>
      </div>
    </div>
    <!-- destination -->
     <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <h5>Explore Experiences of a Lifetime Around the Globe</h5>
            <p>Create memories in every corner of the World</p>
      </div>
      <div class="col-md-12">
        <div class="row">

           @include('frontend.countries.countries_card')

         </div>
      </div>
      <div class="col-md-12 mt-4">{!! $activity_header->description !!}</div>
    </div>
  </div>




@include('frontend.common.testimonial')
@endsection