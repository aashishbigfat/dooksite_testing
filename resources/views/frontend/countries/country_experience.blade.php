@extends('frontend.layouts.master')
@push('title') {{$countries->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$countries->meta_keywords}}">
<meta name="description" content="{{$countries->meta_description}}">
<meta property="og:description" content="{{$countries->meta_description}}">
<meta name="twitter:description" content="{{$countries->meta_description}}">
@endpush
@section('content')
    <!-- home section -->
    <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <a href="{{route('frontend.countries')}}">Countries</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$countries->countryName}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$countries->experience_title}}</h1>
          <p class="page-subtitle">
          {{$countries->subTitle}}
          </p>
        </div>
      </div>
    </section>

    <div class="container mb-4">
        <div class="row">
           <!--  <div class="col-md-12 mt-4">
                <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{url('')}}/nightlife-tours">Nightlife Tour </a> / {{$countries->countryName}}</p>
            </div> -->
            <div class="col-md-12">
		             <!--  <div class="tour topheading" id="Top">
				      <h1 class="text-capitalize my-1">{{$countries->experience_title}}</h1>
				      <p>{{$countries->subTitle}}</p>
				  </div> -->
		              <div class="row">
					    <div class="col-12 text-justify">
					      <span>{!! $countries->experience_description !!}</span>
					    </div>
					  </div>
	
						<hr>
                         <div class="sectionHeading mt-5 heading">
		           <h2 class="text-capitalize">Most Popular {{$countries->experience_title}} Packages</h2>
                     <p>Enjoy the Crazy {{$countries->experience_title}}</p>
                 </div>
		          <!-- </div> -->
                <div class="tour" id="Top">                      
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
                </div>
            </div>

    </div>
</div>
    <!-- testimonial -->
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
