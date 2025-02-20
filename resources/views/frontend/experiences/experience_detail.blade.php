@extends('frontend.layouts.master')
@push('title') {{$experience->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$experience->meta_keywords}}">
<meta name="description" content="{{$experience->meta_description}}">@endpush
@section('content')
  

  <div class="container">
    <div class="row mt-4 mb-4">
       <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / <a href="">Experiences</a> / {{$experience->experience_name}}</p>
          <div class="row">
              <div class="col-md-9 mb-4">
                <h4>{{$experience->pkg_title}}</h4>
                <p class="color_gray">{{$experience->pkg_sub_title}}</p>
              </div>  
              <div class="col-md-3 mb-4">
                <select class="form-control">
                  <option selected>India</option>
                </select>
              </div>  
               @foreach($departures as $departure)
              <div class="col-md-3 mb-4 mt-4">          
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
          </div>
        </div>       
    </div>
    <!-- Experience -->
    <hr>
     <div class="row mb-4">
        <div class="col-md-12 mb-4">
          <h4>{{$experience->exp_title}}</h4>
          <p class="color_gray">{{$experience->exp_sub_title}}</p>
  
        <div class="row">
          @foreach($activities as $key => $activity)
          <div class="col-md-3">
            @include('frontend/common/activity_card')
          </div>
          @endforeach
        </div>
        </div>
     </div>
    <hr>
     <!-- destination -->
     <div class="row mb-4">
        <div class="col-md-9 mb-4">
          <h4>{{$experience->country_title}}</h4>
          <p class="color_gray">{{$experience->country_sub_title}}</p>
          
        <div class="row"> 
          <div class="containerGridWrapper">
              @foreach($countries as $key => $experience_row)
              <a href="{{url('/')}}/{{$experience_row->slug_url}}" class="thingstodo-picture">
                <div class="wrapperImageContainer">
                  <img src="{{ $experience_row->image }}" />
                  <p> {{ $experience_row->countryName }}</p>
                </div>
              </a>
              @endforeach
            </div>
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
           <hr>
           @if($experience->description != null || $experience->description !="")

              <div class="col-md-12 country_about">
                {!! $experience->description !!}
              </div>
        @endif
      </div>

   
  </div>

  @include('frontend.common.testimonial')
  @endsection