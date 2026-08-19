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
          <h1 class="page-title mt-0">{{$countries->group_title}}</h1>
          <p class="page-subtitle">
          Plan a Joyful {{$countries->countryName}} Group Tour with Dook
          </p>
        </div>
      </div>
    </section>

    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
		             
					  <div class="sectionHeading heading">
			            <h2 class="text-capitalize">Spend a Fulfilling Time in {{$countries->countryName}} with Dook!</h2>
			            <p>Explore the Unexplored with our Bespoke {{$countries->countryName}} Group Tour Packages</p>
			          </div>
		          
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
		                    <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More</button>
		                </div>
		            @endif
                </div>
            </div>


    </div>
          <hr>
      <div class="row">
        <div class="col-12 text-justify desc">
          <span>{!! $countries->group_description !!}</span>
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
