@extends('frontend.layouts.master')
@push('title') Search Result @endpush
@push('meta_tag')
    <meta name="keywords" content="Search Result">
    <meta name="description" content="Search Result">
@endpush
@section('content')

<main class="mt-5">

    <div class="container">
      <div class="row">
        <div class="col-12 d-md-flex align-items-center justify-content-between mb-3">
          <div class="sectionHeading ">
            <h2 class="text-capitalize">{{$keyword}}</h2>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach($packages as $key => $tour)
         <div class="col-md-3 mb-4"> 
          <div class="card">
                  <a href="{{route('frontend.departure_details',['country'=>$tour['slug1'],'slug'=>$tour['slug2'],'dook_ref_id'=>$tour['slug3']])}}">
                  <img src="{{$tour['image']}}" class="card-img-top" alt="{{ ucwords(strtolower($tour['title']) )}}">

                @if($tour['featured'] != "")
                  <div class="best_selling_pack">
                      <img src="{{ asset('assets/images/icons/Rectangle19435.png') }}" class="w-auto" alt="Best Selling">
                      <p class="best_sell_pack">{{$tour['featured']}}</p>
                  </div>
                  @endif

                  <div class="card-body">
                      <h6>{{ ucwords(strtolower($tour['title']) )}}</h6>
                      <div class="row">
                          <div class="col-md-6">
                              <p>{{$tour['no_of_nights']}}N</p>
                          </div>

                          <div class="col-md-6">
                              <p>
                                 @if($tour['price'] != "" || $tour['price'] != null)
                                      <span style="color: green;">₹ {{ number_format($tour['price']) }}</span>
                                      @else
                                      <span></span>
                                  @endif
                              </p>
                          </div>
                      </div>
                  </div>
              </a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  <section class="section_widget">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="sectionHeading">
            <h2>Countries</h2>
          </div>
        </div>
      </div>
      <div class="row">
         <div class="containerGridWrapper">
            @foreach($countries as $key => $country)
            <a href="{{url('/')}}/{{$country['slug_url']}}" class="thingstodo-picture">
              <div class="wrapperImageContainer">
                <img src="{{$country['image']}}" alt="{{$country['countryName']}}" />
                <p>{{$country['countryName']}}</p>
              </div>
            </a>
            @endforeach
          </div>
      </div>
    </div>
  </section>
  <section class="section_widget">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="sectionHeading">
            <h2>Destinations</h2>
          </div>
        </div>
      </div>
      <div class="row mt-3s">        
         @foreach($destinations as $key => $destination)
         <div class="col-md-2">
            <div class="destination-card">
              <a href="{{url('destinations')}}/{{$destination['slug_url']}}" class="thingstodo-picture">
                <!-- <div class="wrapperImageContainer"> -->
                  <img class="destination-image" src="{{$destination['image']}}" alt="{{$destination['dest_name']}}" />
                  <div class="destination-overlay">{{$destination['dest_name']}}</div>
                <!-- </div> -->
              </a>
            </div>
          </div>
          @endforeach         
      </div>
    </div>
  </section>

@include('frontend.common.testimonial')

@endsection