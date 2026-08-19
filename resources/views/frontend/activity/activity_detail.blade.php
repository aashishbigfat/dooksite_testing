@extends('frontend.layouts.master')
@push('title') {{$activity->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$activity->meta_keywords}}">
<meta name="description" content="{{$activity->meta_description}}">
<meta property="og:description" content="{{$activity->meta_description}}">
<meta name="twitter:description" content="{{$activity->meta_description}}">
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
            <a href="{{route('frontend.activities')}}">Activities</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$activity->activity_name}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">Bestselling {{$activity->activity_name}} Packages</h1>
         
        </div>
      </div>
    </section>


  <div class="container">
    <div class="row mt-4 mb-4">
      <!-- heading -->
  
        <div class="col-md-9 ">

           <div class="tours-grid" id="tourPackages">
                @include('frontend.common.tourpackage') <!-- First batch of departures -->
            </div>
            <div id="departuresLoadMoreBtn">
                @if($departures->hasMorePages())
                    <div class="col-md-12 mt-4 text-center">
                        <div id="loader1" class="loader" style="display:none;">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <button id="loadMoreDeparturesBtn" class="btn btn-danger">Load More</button>
                    </div>
                @endif
            </div>
       </div>
       <div class="col-md-3">
          @include('frontend.common.bookwithconfidence')
       </div>

     </div>
     <hr>
     <!-- destination -->
      <div class="row mt-4 mb-4">
      <div class="col-md-12 mb-4">
         <div class="sectionHeading mt-5 heading">
        <h2 class="text-capitalize my-1">Top Destinations for {{$activity->activity_name}} Activities Around the World</h2>
      </div>
      </div>
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
                        <button id="loadMoreCountriesBtn" class="btn btn-danger">Load More</button>
                    </div>
                @endif
            </div>
      </div>
    </div>
  </div>

@include('frontend.common.testimonial')
<script>
  let departurePage = 2;
  $('#loadMoreDeparturesBtn').click(function() {
      $('#loader1').show();
      $('#loadMoreDeparturesBtn').hide();
      
      $.ajax({
          url: "{{ url()->current() }}?page=" + departurePage,
          type: "GET",
          success: function(data) {
              $('#tourPackages').append(data.departures);
              departurePage++;

              // Check if there are more departures
              if (!data.hasMoreDepartures) {
                  $('#loadMoreDeparturesBtn').hide();
                  $('#noMoreDeparturesMsg').show(); // Show the "No more packages" message
              } else {
                  $('#loadMoreDeparturesBtn').show();
              }

              // Hide the loader
              $('#loader1').hide();
          },
          error: function() {
              alert('Error loading more packages');
              $('#loader1').hide();
              $('#loadMoreDeparturesBtn').show();
          }
      });
  });

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