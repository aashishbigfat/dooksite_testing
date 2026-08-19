@extends('frontend.layouts.master')
@push('title') {{$destination_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$destination_header->meta_keywords}}">
<meta name="description" content="{{$destination_header->meta_description}}">
<meta property="og:description" content="{{$destination_header->meta_description}}">
<meta name="twitter:description" content="{{$destination_header->meta_description}}">
@endpush 

@section('content')
<style type="text/css">
    .destination-image{
        width: 100% !important;
        height: 100% !important;
        border-radius: 0% !important;
    }
</style>
    <!-- home section -->
     <!-- home section -->
    <div class="overflow-hidden">
     <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Destinations</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">Fascinating Holiday Destinations</h1>
          <p class="page-subtitle">
         Dream, explore, and discover with DOOK!
          </p>
        </div>
      </div>
    </section>

   <div class="container">
<!--     <div class="row mt-4">
        <div class="col-md-12 mb-3"> -->
        
            <div class="row mt-4" id="tourPackages">
              <!-- Destination Card 1 -->
               @include('frontend.destination.destination_card', ['top_destinations' => $top_destinations])
            </div>
            @if($top_destinations->hasMorePages())
                    <div class="col-md-12 mt-4 text-center">
                        <div id="loader" class="loader">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <button id="loadMoreBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Destinations</button>
                    </div>
                @endif 

          <!-- </div> -->
          <hr>
          <div class="row">
           <div class="col-md-12 mb-3">
            <div class="sectionHeading heading mt-3">
                 <h2 class="text-capitalize my-1">Destinations Around The World with Dook</h2>
                <p>If it is a place worth visiting at least once in a lifetime, we surely cover it.</p>
            </div>
                <div class="row" id="desti">
                @include('frontend.destination.destinationdata', ['destinationData' => $destinations])
                </div>
                 @if($destinations->hasMorePages())
                <div class="col-md-12 mt-4 text-center">
                    <div id="destinationloader" class="loader">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button id="loadMoreBtndestination" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Destinations</button>
                </div>
            @endif             
          </div>
        </div>
          <hr>
          <div class="row">
           
            <div class="col-md-12 color_gray desc">             
              {!! $destination_header->description !!}
            </div>
           
          </div>
   <!--  </div>
</div> -->
</div>
    <!-- testimonial -->
  @include('frontend.common.testimonial')

<script>
$(document).ready(function () {
    let page = 2;
    let destinationPage = 2;

    $('#loadMoreBtn').click(function () {
        $('#loader').show();
        $('#loadMoreBtn').hide();

        $.ajax({
            url: "{{ url()->current() }}",
            type: "GET",
            data: { page: page },
            success: function (data) {
                console.log("Top Destinations Response:", data);
                $('#tourPackages').append(data.top_destinations);
                page++;

                if (!data.topHasMorePages) {
                    $('#loadMoreBtn').hide();
                } else {
                    $('#loadMoreBtn').show();
                }
                $('#loader').hide();
            },
            error: function () {
                alert('Error loading more destinations');
                $('#loader').hide();
                $('#loadMoreBtn').show();
            }
        });
    });

    $('#loadMoreBtndestination').click(function () {
        $('#destinationloader').show();
        $('#loadMoreBtndestination').hide();

        $.ajax({
            url: "{{ url()->current() }}",
            type: "GET",
            data: { page: destinationPage },
            success: function (data) {
                console.log("Destination Data Response:", data);
                $('#desti').append(data.destinationData);
                destinationPage++;

                if (!data.destinationHasMorePages) {
                    $('#loadMoreBtndestination').hide();
                } else {
                    $('#loadMoreBtndestination').show();
                }
                $('#destinationloader').hide();
            },
            error: function () {
                alert('Error loading more destinations');
                $('#destinationloader').hide();
                $('#loadMoreBtndestination').show();
            }
        });
    });
});

</script>
@endsection
