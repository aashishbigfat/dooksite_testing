@extends('frontend.layouts.master')
@push('title') {{$country_poi_detail->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country_poi_detail->meta_keywords}}">
<meta name="description" content="{{$country_poi_detail->meta_description}}">
<meta property="og:description" content="{{$country_poi_detail->meta_description}}">
<meta name="twitter:description" content="{{$country_poi_detail->meta_description}}">
@endpush 

@section('content')
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
            <span class="breadcrumb-current">{{$country_poi_detail->countryName}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$country_poi_detail->title}}</h1>
          <p class="page-subtitle">
          {{$country_poi_detail->subTitle}}
          </p>
        </div>
      </div>
    </section>

 <div class="container mb-4">
      <div class="row mt-4 mb-4">          
          <div class="col-md-12 mt-3 mb-4 attract_desc">
            <p>{!! $country_poi_detail->attraction_description !!}</p>
              <hr>
              <div class="heading">
            <h2> {{$country_poi_detail->attraction_heading}}</h2>
            </div>
           <div class="row mt-4" >
              <div class="masonry-container" id="tourPackages">
                @include('frontend.poi.partial_card')
            </div>
            </div>           
               {{--<div class="col-md-12 mt-4 text-center">
                    <div id="loader" class="loader">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More</button>
                </div>    --}}        
          </div>
          <hr>
          <div class="col-md-12 mt-4 heading">
            <h2>Things to Do in {{$country_poi_detail->countryName}}</h2>
            <p>Do what makes you happy</p>
             
            <div class="containerGridWrapper">
              @foreach($experience_row as $key => $experience_row)
              <a href="{{url('/')}}/{{$experience_row->slug}}" class="thingstodo-picture">
                <div class="wrapperImageContainer">
                  <img src="{{ $experience_row->image }}" alt="{{ $experience_row->name }}" />
                  <p> {{ $experience_row->name }}</p>
                </div>
              </a>
              @endforeach
            </div>                     
          </div>
    </div>
</div>

   @include('frontend.common.testimonial')

<script>
   let page = 2;

$('#loadMoreBtn').click(function() {
    // Show the loader and hide the button
    $('#loader').show();
    $('#loadMoreBtn').hide();

    $.ajax({
        url: "{{ url()->current() }}?page=" + page,  // Add the page query parameter to the URL
        type: "GET",
        success: function(data) {
            // Append the new tour packages to the existing ones
            $('#tourPackages').append(data.view);

            // Increment the page number
            page++;

            // If there are no more pages, hide the "Load More" button
            if (!data.hasMorePages) {
                $('#loadMoreBtn').hide();
            }

            // Hide the loader
            $('#loader').hide();

            // If there are more pages, show the "Load More" button
            if (data.hasMorePages) {
                $('#loadMoreBtn').show();
            }
        },
        error: function() {
            // Handle error
            alert('Error loading more packages');
            $('#loader').hide();
            $('#loadMoreBtn').show();
        }
    });
});
</script>

@endsection
