@extends('frontend.layouts.master')
@push('title') {{$region_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$region_header->meta_keywords}}">
<meta name="description" content="{{$region_header->meta_description}}">
<meta property="og:description" content="{{$region_header->meta_description}}">
<meta name="twitter:description" content="{{$region_header->meta_description}}">
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
            <span class="breadcrumb-current">Regions</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$region_header->title}}</h1>
          <p class="page-subtitle">
          {{$region_header->sub_title}}
          </p>
        </div>
      </div>
    </section>
    
  <div class="container">
    <div class="row mt-4 mb-4">
      <!-- heading -->
   <!--     <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Regions</p>
       </div> -->
        <div class="col-md-12">
   <!--          <div class="tour topheading" id="Top">
             <h1 class="text-capitalize my-1">{{$region_header->title}}</h1>
               <p>{{$region_header->sub_title}}</p>
           </div> -->
         <div class="region_container">
              @foreach($regions as $key => $region)
                <div class="region_card">
                    <div class=" shadow-sm rounded  position-relative">
                         <div class="destination-sec-country ml-2">                                      
                            <div class="destination">
                               <ul class="list-inline p-0 m-0">
                                @foreach($region->experiences as $key => $experience)
                                <li class="list-inline-item px-2"><a href="{{url('/')}}/{{$experience->slug_url}}"
                                    class=""><span>{{$experience->experience_name}}</span></a></li>
                                @endforeach
                              </ul>
                            </div>                                
                        </div> 
                        <img class="img-fluid w-100" src="{{$region->image}}" alt="{{$region->region_name}}">
                        <div class=" dest_card">
                           <div class="row test align-items-center dest">
                            <div class="col-md-12" style="padding: 10px;">
                             <h5 class="m-0"><a href="{{url('/')}}/{{$region->slug_url}}" style="color:#fff;" class="">{{$region->region_name}}</a></h5> 
                             <ul class="list-inline mb-0 country-list">
                                @foreach($region->countries as $key => $country)
                                <li class="list-inline-item m-0"><a href="{{url('/')}}/{{$country->slug_url}}"
                                    class=""><span>{{$country->country_name}}</span></a></li>
                                @endforeach
                              </ul>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
       </div>
      

   </div>
   <hr>
   <div class="row">
    <div class="col-md-12 mb-2 heading">
         <h2 class="text-capitalize">Leave only footprints and take only memories.</h2>
           <p>It is about the journey not the destination. Start your journey with a single click!</p>

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
<hr>
 <div class="row">
        <div class="col-md-12 desc">{!! $region_header->description !!}</div>
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

</script>
@endsection