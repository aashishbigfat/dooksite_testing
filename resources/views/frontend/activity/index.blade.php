@extends('frontend.layouts.master')
@push('title') {{$activity_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$activity_header->meta_keywords}}">
<meta name="description" content="{{$activity_header->meta_description}}">
<meta property="og:description" content="{{$activity_header->meta_description}}">
<meta name="twitter:description" content="{{$activity_header->meta_description}}">
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
            <span class="breadcrumb-current">Activities</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$activity_header->title}}</h1>
          <p class="page-subtitle">
        {{$activity_header->sub_title}}
          </p>
        </div>
      </div>
    </section>


  <div class="container">
    <div class="row mt-4 mb-4">
      <!-- heading -->
       <!-- <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Activities</p>
       </div> -->
       <div class="col-md-9">
       <!--   <div class="tour topheading" id="Top">
         <h1 class="text-capitalize my-1">{{$activity_header->title}}</h1>
         <p>{{$activity_header->sub_title}}</p>
     </div> -->
         <div class="row" id="activities-container">
                    @include('frontend.common.activity_card')
            
            </div>

            @if($activities->hasMorePages())
                <div class="col-md-12 mt-4 text-center">
                    <button id="loadMoreActivities" class="load_more_btn"><i class="fas fa-plus me-2"></i> View More Activities</button>
                    <div id="loaderActivities" class="loader" style="display: none;">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            @endif
       </div>
       <div class="col-md-3">
        @include('frontend.common.bookwithconfidence')
       </div>
    </div>
    <hr>
    <!-- packages -->
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
         <div class="sectionHeading mt-5 heading">
        <h2 class="text-capitalize my-1">Chill out and relax around the world</h2>
        <p>Chill out and relax around the world</p>
    </div>
      </div>
      <div class="col-md-12">
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
                        <button id="loadMoreDeparturesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Packages</button>
                    </div>
                @endif
            </div>
      </div>
    </div>
    <!-- destination -->
     <div class="row mt-4 mb-4">
      <div class="col-md-12">
         <div class="sectionHeading mt-5 heading">
        <h2 class="text-capitalize my-1">Explore Experiences of a Lifetime Around the Globe</h2>
            <p>Create memories in every corner of the World</p>
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
                        <button id="loadMoreCountriesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Countries</button>
                    </div>
                @endif
            </div>
      </div>
      <div class="col-md-12 mt-4 desc">{!! $activity_header->description !!}</div>
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
  let pageActivities  =2;
 $('#loadMoreActivities').click(function() {
        $('#loaderActivities').show();
        $('#loadMoreActivities').hide();

        $.ajax({
            url: "{{ url()->current() }}?page=" + pageActivities + "&type=activities",
            type: "GET",
            success: function(data) {
                $('#activities-container').append(data.activities);
                pageActivities++;

                if (!data.hasMoreActivities) {
                    $('#loadMoreActivities').hide();
                }

                $('#loaderActivities').hide();
                if (data.hasMoreActivities) {
                    $('#loadMoreActivities').show();
                }
            },
            error: function() {
                alert('Error loading more activities');
                $('#loaderActivities').hide();
                $('#loadMoreActivities').show();
            }
        });
    });
</script>
@endsection