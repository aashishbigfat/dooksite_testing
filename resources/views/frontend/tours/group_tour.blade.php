@extends('frontend.layouts.master')
@push('title') {{$departure_header->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure_header->meta_keywords}}">
<meta name="description" content="{{$departure_header->meta_description}}">@endpush
@section('content')

<!-- home section -->
<div class="container">
  <div class="row mb-4">
    <div class="col-md-12 mt-4">
      <p class="color_gray"><a href="/" class="text-danger">Home</a> / Group Tour</p>
    </div>
    <div class="col-md-3">
      @include('frontend.common.package_filter')
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-6">
          <p class="color_gray"> Tours Found</p>
        </div>
      </div>
      <div class="row">
      
         @foreach($departures as $departure)
        <div class="col-md-4 mb-4">          
            @include('frontend.common.tourpackage')
          </div>
         @endforeach
         
  


      </div>
    </div>

  </div>

</div>


@include('frontend.common.testimonial')
@endsection