@extends('frontend.layouts.master') @push('title')
{{$departure_header->meta_title}} @endpush @push('meta_tag')
<meta name="keywords" content="{{$departure_header->meta_keywords}}" />
<meta name="description" content="{{$departure_header->meta_description}}" />
<meta property="og:description" content="{{$departure_header->meta_description}}">
<meta name="twitter:description" content="{{$departure_header->meta_description}}">
@endpush
@push('head_script')
<link rel='stylesheet' href="{{asset('assets/ion.rangeSlider.min.css')}}" >
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
            <span class="breadcrumb-current">International Tours</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$departure_header->title}}</h1>
          <p class="page-subtitle">
          {{$departure_header->sub_title}}
          </p>
        </div>
      </div>
    </section>
<div class="container">
  <div class="row mb-4">
   <!--  <div class="col-md-12 mt-4">
      <p class="color_gray">
        <a href="/" class="text-danger">Home</a> / International Tours
      </p>
    </div> -->
  <!--   <div class="topheading">
      <h1 class="text-capitalize my-1">{{$departure_header->title}}</h1>
      <p>{{$departure_header->sub_title}}</p>
    </div> -->
    <div class="col-md-3">@include('frontend.common.package_filter')</div>
    <div class="col-md-9">
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
  <hr />
  <div class="row">
    <div class="col-md-12 desc">{!! $departure_header->description !!}</div>
  </div>
</div>

@include('frontend.common.testimonial')

<script>
  let page = 2;
  $("#loadMoreBtn").click(function () {
    $("#loader").show();
    $("#loadMoreBtn").hide();

    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set("page", page);

    let url = "{{ url()->current() }}?" + urlParams.toString();

    $.ajax({
      url: url,
      type: "GET",
      success: function (data) {
        $("#tourPackages").append(data.view);
        page++;
        if (!data.hasMorePages) {
          $("#loadMoreBtn").hide();
        }
        $("#loader").hide();
        if (data.hasMorePages) {
          $("#loadMoreBtn").show();
        }
      },
      error: function () {
        alert("Error loading more packages");
        $("#loader").hide();
        $("#loadMoreBtn").show();
      },
    });
  });
</script>

@endsection
