@extends('frontend.layouts.master')
@push('title') {{$package->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$package->meta_keywords}}">
<meta name="description" content="{{$package->meta_description}}">@endpush
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
            <span class="breadcrumb-current">{{$package->name}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$package->header_title}}</h1>
          <p class="page-subtitle">
          {!! $package->header_sub_title !!}
          </p>
        </div>
      </div>
    </section>
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="tour topheading" id="Top">
                    <div class="tours-grid" id="tourPackages">
                    @include('frontend.common.tourpackage')
                </div>
            
           {{-- @if($departures->hasMorePages())
                <div class="col-md-12 mt-4 text-center">
                    <div id="loader" class="loader">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Packages</button>
                </div>
            @endif --}}

                  
                </div>
            </div>
              <hr>

          <div class="col-12 wraptextWithImg country_about mt-5">
           @if($package->banner_image)             
                <picture class="wrap_img">
                     <img src="{{ generateSignedUrl($package->banner_image) }}" class="img-fluid mb-3" alt="{{ $package->name }}" loading="lazy" style="border-radius: 12px;">
                </picture>
            @endif

                <!-- Full content (hidden initially) -->
                <p class="full-content" id="full-content-1">
                   {!! $package->description !!}
                </p>

            </div>
    </div>

@endsection
