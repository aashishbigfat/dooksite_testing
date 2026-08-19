@extends('frontend.layouts.master')
@push('title') {{$country_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country_header->meta_keywords}}">
<meta name="description" content="{{$country_header->meta_description}}">
<meta property="og:description" content="{{$country_header->meta_description}}">
<meta name="twitter:description" content="{{$country_header->meta_description}}">
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
            <span class="breadcrumb-current">Countries</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$country_header->title}}</h1>
          <p class="page-subtitle">
          {{$country_header->sub_title}}
          </p>
        </div>
      </div>
    </section>
    
   <div class="container">
    <div class="row mt-4 mb-4">       
         <div class="col-md-12">
           <div class="row" id="countriesList">
                    @include('frontend.countries.countries_card')
                </div>
                <div id="countriesLoadMoreBtn">
                    @if($countries->hasMorePages())
                        <div class="col-md-12 mt-4 text-center">
                            <div id="loader2" class="loader" style="display:none;">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <button id="loadMoreCountriesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Countries</button>
                        </div>
                    @endif
                </div>
        </div>
    </div>
     <hr>
          <div class="row">
           
            <div class="col-md-12 color_gray desc">             
             {!! $country_header->description !!}
            </div>
           
          </div>
</div>
    <!-- testimonial -->
  @include('frontend.common.testimonial')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     // For Countries (similar behavior)
  let countryPage = 2;
  $('#loadMoreCountriesBtn').click(function() {
      $('#loader2').show();
      $('#loadMoreCountriesBtn').hide();
      
      $.ajax({
          url: "{{ url()->current() }}?page=" + countryPage,
          type: "GET",
          success: function(data) {
              $('#countriesList').append(data.countries);
              countryPage++;

              // Check if there are more countries
              if (!data.hasMoreCountries) {
                  $('#loadMoreCountriesBtn').hide();
                  $('#noMoreCountriesMsg').show(); // Show the "No more countries" message
              } else {
                  $('#loadMoreCountriesBtn').show();
              }

              // Hide the loader
              $('#loader2').hide();
          },
          error: function() {
              alert('Error loading more countries');
              $('#loader2').hide();
              $('#loadMoreCountriesBtn').show();
          }
      });
  });
</script>
@endsection
