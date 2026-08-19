@extends('frontend.layouts.master')
@push('title') {{$career_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$career_header->meta_keywords}}">
<meta name="description" content="{{$career_header->meta_description}}">@endpush 

@section('content')
 <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Careers</span>
          </div>
        </div>
      </div>
    </section>
      <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$career_header->title}}</h1>
         
        </div>
      </div>
    </section>

<section class="section_widget p-4">
 <div class="container mb-4">
      <div class="row">
	       <div class="row mt-3">
        @foreach($careers as $key => $career)
        <div class="col-md-6 mb-5">
          <div class="jobs-section__boxwrap d-flex card p-3">
            <div class="jobs-section__box d-flex flex-column w-100">
              <a href="{{route('frontend.careers_detail',$career->slug_url)}}" class="full-link">
              <div class="jobs-section__box__head"><h6><strong>{{$career->title}}</strong></h6></div>
              <p class="border-bottom pb-2">{{$career->role}}</p>
              <p><img src="{{asset('assets/images/icons/map-pin2.png')}}"> Location: <strong>{{$career->location}}</strong></p>
              <p><img src="{{asset('assets/images/icons/users1.png')}}"> No. of Positions : {{$career->position}}</p>
              <p ><img src="{{asset('assets/images/icons/briefcase1.png')}}"> Experience : Minimum {{$career->exp}} Years</p> <p class="border-top text-center pt-2 mb-0 text-danger"> Apply Now <img src="{{asset('assets/images/icons/arrow-down-right.png')}}"></p></div>
              </a>
          </div>
        </div>
        @endforeach
      </div>
	    </div>
	</div>
</section>
   @include('frontend.common.testimonial')

@endsection
