@extends('frontend.layouts.master')
@push('title') {{$experience_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$experience_header->meta_keywords}}">
<meta name="description" content="{{$experience_header->meta_description}}">
<meta property="og:description" content="{{$experience_header->meta_description}}">
<meta name="twitter:description" content="{{$experience_header->meta_description}}">
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
            <span class="breadcrumb-current">Experiences</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$experience_header->title}}</h1>
          <p class="page-subtitle">
        {{$experience_header->sub_title}}
          </p>
        </div>
      </div>
    </section>

  <div class="container">
    <div class="row mt-4 mb-4">
       <div class="col-md-12 mt-4">
          <!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> / Experiences</p> -->
          <div class="row">
              <div class="col-md-9 mb-4">
                 <!-- <div class="tour topheading" id="Top">
                  <h1 class="text-capitalize my-1">{{$experience_header->title}}</h1>
                 <p class="color_gray">{{$experience_header->sub_title}}</p>
                </div> -->
                <div class="row" id="experiencesList">
                    @include('frontend.experiences.experience_card')
                </div>

                <div id="loadMoreExperiencesContainer">
                    @if($experiences->hasMorePages())
                        <div class="col-md-12 mt-4 text-center">
                            <div id="experienceLoader" class="loader" style="display: none;">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <button id="loadMoreExperiencesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More </button>
                        </div>
                    @endif
                </div>
            </div>
             <div class="col-md-3">
                @include('frontend.common.bookwithconfidence')
               </div>         
          </div>
        </div>       
    </div>
   <hr> 
      <div class="row">
        <div class="col-md-12 mb-2">
           <div class="sectionHeading heading mt-3">
            <h2 class="text-capitalize my-1">Explore chilled out experiences with laid back vibes</h2>
            <p>Chill out and relax around the world</p>
          </div>
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
          <hr>
          <div class="col-md-9 mb-4 mt-3">
             <div class="sectionHeading heading mt-3">
             <h2 class="text-capitalize my-1">Explore Experiences of a Lifetime Around the Globe</h2>
            <p>Create memories in every corner of the World</p>
          </div>
          <div class="row"> 
            <div class="containerGridWrappercountry">
                @foreach($countries as $key => $experience_row)
                <a href="{{url('/')}}/{{$experience_row->slug_url}}" class="thingstodo-picture">
                  <div class="wrapperImageContainer">
                    <img src="{{ $experience_row->image }}" alt="{{ $experience_row->countryName }}" />
                    <p> {{ $experience_row->countryName }}</p>
                  </div>
                </a>
                @endforeach
              </div>
          </div>
        </div>
          <hr>
           <div class="row">
          <div class="col-md-12 desc">{!! $experience_header->description !!}</div>
        </div>   
  </div>
</div>

  @include('frontend.common.testimonial')
  <script>
 let experiencePage = 2;
let packagePage = 2;

// Load More Experiences
$('#loadMoreExperiencesBtn').click(function () {
    $('#experienceLoader').show();
    $('#loadMoreExperiencesBtn').hide();

    $.ajax({
        url: "{{ url()->current() }}?experience_page=" + experiencePage,  // Change `page` to `experience_page`
        type: "GET",
        success: function (data) {
            $('#experiencesList').append(data.experienceView);
            experiencePage++;

            if (!data.hasMoreExperiences) {
                $('#loadMoreExperiencesBtn').remove();
            }

            $('#experienceLoader').hide();
            if (data.hasMoreExperiences) {
                $('#loadMoreExperiencesBtn').show();
            }
        },
        error: function () {
            alert('Error loading more experiences');
            $('#experienceLoader').hide();
            $('#loadMoreExperiencesBtn').show();
        }
    });
});

// Load More Packages
$('#loadMoreBtn').click(function () {
    $('#loader').show();
    $('#loadMoreBtn').hide();

    $.ajax({
        url: "{{ url()->current() }}?package_page=" + packagePage,  // Change `page` to `package_page`
        type: "GET",
        success: function (data) {
            $('#tourPackages').append(data.packageView);
            packagePage++;

            if (!data.hasMorePackages) {
                $('#loadMoreBtn').remove();
            }

            $('#loader').hide();
            if (data.hasMorePackages) {
                $('#loadMoreBtn').show();
            }
        },
        error: function () {
            alert('Error loading more packages');
            $('#loader').hide();
            $('#loadMoreBtn').show();
        }
    });
});

</script>
  @endsection