@extends('frontend.layouts.master')
@push('title') {{$privacy_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$privacy_header->meta_keywords}}">
<meta name="description" content="{{$privacy_header->meta_description}}">@endpush 

@section('content')
<section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Privacy Policy</span>
          </div>
        </div>
      </div>
    </section>
          <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$privacy_header->title}}</h1>
         
        </div>
      </div>
    </section>
<section class="section_widget p-4">
 <div class="container mb-4">
      <div class="row">
          
	      <div class="row">
	        <div class="col-md-12">
	          {!! $privacy_header->description !!}
	        </div>
	      </div>
	    </div>
	</div>
</section>
   @include('frontend.common.testimonial')

@endsection
