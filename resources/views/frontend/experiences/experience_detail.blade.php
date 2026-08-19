@extends('frontend.layouts.master')
@push('title') {{$experience->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$experience->meta_keywords}}">
<meta name="description" content="{{$experience->meta_description}}">
<meta property="og:description" content="{{$experience->meta_description}}">
<meta name="twitter:description" content="{{$experience->meta_description}}">
@endpush
@section('content')
<style type="text/css">
    .country_about b a {
  color: #dc3545 !important;
}
</style>  
 <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <a href="{{route('frontend.experiences')}}">Experiences</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$experience->experience_name}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$experience->edit_Header_title}}</h1>
          <p class="page-subtitle">
           {{$experience->header_sub_title}} 
          </p>
        </div>
      </div>
    </section>

  <div class="container">
    @if($departures->count())
    <div class="row mt-4 mb-4">
       <div class="col-md-12 mt-4">
          <div class="row">
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
                    <button id="loadMoreBtn" class="btn btn-danger">Load More</button>
                </div>
            @endif           
          </div>
        </div>       
    </div>
    <!-- Experience -->
    <hr>
    @endif
    @if(isset($activities) && $activities->count())
     <div class="row mb-4">
        <div class="col-md-12 mb-4">
          <div class="sectionHeading heading mt-3">
            <h2 class="text-capitalize my-1">{{$experience->exp_title}}</h2>
            <p class="color_gray">{{$experience->exp_sub_title}}</p>
          </div>
        <div class="row">
            @include('frontend/common/activity_card')        
        </div>
        </div>
     </div>
    <hr>
    @endif
     <!-- destination -->
     @if(isset($countries) && $countries->count())
     <div class="row mb-4">
          <div class="col-md-9 mb-4">
            <div class="sectionHeading heading mt-3">
             <h2 class="text-capitalize my-1">{{$experience->country_title}}</h2>
             <p class="color_gray">{{$experience->country_sub_title}}</p>
            </div>
          <div class="row" id="countriesList">
                @include('frontend.experiences.experience_country')
            </div>
           <div id="countriesLoadMoreBtn">
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
                    <p>No countries available in this region.</p> 
                @endif
            </div> 

        </div>
         
         <div class="col-md-3">
          @include('frontend.common.bookwithconfidence')
         </div>
           <hr>
            @endif
           @if($experience->description != null || $experience->description !="")

              <div class="col-md-12 color_gray desc">
                {!! $experience->description !!}
              </div>
        @endif
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