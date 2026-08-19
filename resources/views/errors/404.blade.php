@extends('frontend.layouts.master')
@push('title') 404: Page Not Found! @endpush
@push('meta_tag')
    <meta name="keywords" content="404, Page Not Found!">
    <meta name="description" content="Page Not Found!">
@endpush
@section('content')

<!-- home section -->
<div class="container">
    <div class="row mb-4 mt-4 justify-content-center">
    	<div class="col-md-9">
    		<img src="{{asset('assets/images/404-Page.png')}}" class="w-100 h-100">
    	</div>
    	<div class="col-md-6 text-center mt-4">
        <h1>Thanks For Exploring With Us!</h1>
       <h5>Whoops! Page not found</h5>

		<p>Sorry, Your request could not be processed. It's looking like you may have taken a wrong turn.</p>
		<a href="/" class="btn btn-danger">Home</a>
		</div>
    </div>
</div>

@include('frontend.common.testimonial')
@endsection
