@extends('frontend.layouts.master')
@push('title') {{$departure_header->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure_header->meta_keywords}}">
<meta name="description" content="{{$departure_header->meta_description}}">@endpush
@section('content')

<!-- home section -->
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 mt-4">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> / Indian Tour</p>
        </div>
        <div class="col-md-3">
            @include('frontend.common.package_filter')
        </div>
        <div class="col-md-9">
            <div class="sectionHeading">
              <h2 class="text-capitalize">{{$departure_header->title}}</h2>
              <p>{{$departure_header->sub_title}}</p>
            </div>
      
              <div class="row" id="tourPackages">
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


@include('frontend.common.testimonial')

<script>
    let page = 2; 

    $('#loadMoreBtn').click(function() {
        // Show the loader
        $('#loader').show();
        $('#loadMoreBtn').hide();

        $.ajax({
            url: "{{ url()->current() }}?page=" + page,
            type: "GET",
            success: function(data) {
                $('#tourPackages').append(data.view);
                page++;
                if (!data.hasMorePages) {
                    $('#loadMoreBtn').hide();
                }
                $('#loader').hide();
                if (data.hasMorePages) {
                    $('#loadMoreBtn').show();
                }
            },
            error: function() {
                alert('Error loading more packages');
                $('#loader').hide();
                $('#loadMoreBtn').show();
            }
        });
    });
</script>
@endsection