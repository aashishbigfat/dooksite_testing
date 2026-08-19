@extends('frontend.layouts.master')
@push('title') {{$region->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$region->meta_keywords}}">
<meta name="description" content="{{$region->meta_description}}">
<meta property="og:description" content="{{$region->meta_description}}">
<meta name="twitter:description" content="{{$region->meta_description}}">
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
            <a href="{{route('frontend.regions')}}">Regions</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$region->region_name}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$region->title}}</h1>
          <p class="page-subtitle">
          {{$region->sub_title}}
          </p>
        </div>
      </div>
    </section>

<div class="container">
    <div class="row mt-4 mb-4">
        <!-- heading -->
       <!--  <div class="col-md-12 mt-4">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> / <a href="{{route('frontend.regions')}}" class="text-danger">Regions</a> /{{$region->region_name}}</p>
        </div> -->
        <div class="col-md-9">
             <!-- <div class="tour topheading" id="Top">
                <h1 class="text-capitalize my-1">{{$region->title}}</h1>
                <p>{{$region->sub_title}}</p>
            </div> -->
            <div class="tours-grid" id="tourPackages">
                @include('frontend.common.tourpackage')
            </div>
            <div id="departuresLoadMoreBtn">
                @if($departures->count() > 0 && $departures->hasMorePages())
                    <div class="col-md-12 mt-4 text-center">
                        <div id="loader1" class="loader" style="display:none;">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <button id="loadMoreDeparturesBtn" class="btn btn-danger">Load More</button>
                    </div>
                @elseif($departures->count() == 0)
                    <p>No packages available in this region.</p>  <!-- Optional: Show a message if no packages are available -->
                @endif
            </div>        
        </div>
        <div class="col-md-3">
            @include('frontend.common.bookwithconfidence')
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="sectionHeading mt-5 heading">
                <h2 class="text-capitalize my-0">{{$region->region_name}} Countries to Explore</h2>
                <p>Seek new roads in {{$region->region_name}} with Dook</p>
            </div>
            <div class="row" id="countriesList">
                @include('frontend.countries.countries_card')
            </div>
           {{-- <div id="countriesLoadMoreBtn">
                @if($countries->count() > 0 && $countries->hasMorePages())
                    <div class="col-md-12 mt-4 text-center">
                        <div id="loader2" class="loader" style="display:none;">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <button id="loadMoreCountriesBtn" class="btn btn-danger">Load More</button>
                    </div>
                @elseif($countries->count() == 0)
                    <p>No countries available in this region.</p>  <!-- Optional: Show a message if no countries are available -->
                @endif
            </div> --}}
        </div>
        <hr>
        <div class="col-md-12 desc">
            {!! $region->description !!}
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
