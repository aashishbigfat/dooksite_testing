@extends('frontend.layouts.master')
@push('title') {{$destination->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$destination->meta_keywords}}">
<meta name="description" content="{{$destination->meta_description}}">
<meta property="og:description" content="{{$destination->meta_description}}">
<meta name="twitter:description" content="{{$destination->meta_description}}">
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
            <a href="{{route('frontend.destinations')}}">Destinations</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$destination->dest_name}}</span>
          </div>
        </div>
      </div>
    </section>

   

<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <h1>{{$destination->dest_name}} Destination Guide</h1>
            <!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{route('frontend.destinations')}}" class="text-danger">Destinations</a>/{{ $destination->dest_name }}</p> -->

        </div>
        <div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
             <ul class="nav nav-tabs product_detail d-flex" style="border-bottom:none">
               
                    <li class="active"><a href="#Details">Top Tours</a></li>
                    <li><a href="#About">About {{$destination->dest_name}}</a></li>                
                    <li><a href="#Experience">Experiences</a></li>
                    <li><a href="#Attractions">Point Of Interest</a></li>
                </ul>
            </div>
        <div class="col-md-12">
            <div id="Details" class="tab-pane fade in active">
            	 <div class="row">
                        <div class="col-12">
                            <div class="sectionHeading topheading">
                                <h1 class="text-capitalize my-1">Top {{$destination->dest_name}} Tour Packages</h1>
                                <p>{!! $destination->tour_sub_title !!}</p>
                            </div>
                        </div>
                    </div>
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
            <hr>
            <div id="About" class="tab-pane fade">
           		 <div class="row">
                    <div class="col-md-9">
                        <div class="sectionHeading heading">
                            <h2 class="text-capitalize my-1">{{$destination->title}}  ({{$destination->country_name}}) </h2>
                            <p>{!! $destination->sub_title !!}</p>
                        </div>
                        <p>{!! $destination->description !!}</p>
                        <div class="row">
                        	 <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/Driving_ic.png')}}" alt="Driving Side">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Driving Side</p>
                                        <p style="margin-top: -18px;"><b>{{$destination->drives_on}}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/Airports.png')}}" alt="Airports">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Airports</p>
                                        <p style="margin-top: -18px;"><b>{{$destination->airports}}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/best time.png')}}" alt="Best time to visit">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Best time to visit</p>
                                        <p style="margin-top: -18px;"><b>{{$destination->bestTimeVisits}}</b></p>
                                    </div>
                                </div>
                            </div>
                               <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/noun-cloud-7359739 1.png')}}" alt="Climate Types">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Climate Types</p>
                                        <p style="margin-top: -18px;"><b>{{$destination->climateTypes}}</b></p>
                                    </div>
                                </div>
                            </div>
                               <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/noun-currency-7269517 1.png')}}" alt="Currency">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Currency</p>
                                        <p style="margin-top: -18px;"><b> {{$destination->currency_code}} ({{$destination->currency_symbol}})</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-3">
		               @include('frontend.common.bookwithconfidence')
		               </div>     
                </div>
            </div>
            <hr>
             <div id="Experience" class="tab-pane fade in active">
             	<div class="row">
             		<div class="col-md-9">
            	   <div class="row">
                        <div class="col-12">
                            <div class="sectionHeading heading">
                                <h2 class="text-capitalize my-1">Experiences in {{$destination->dest_name}} </h2>
                            <p>{!! $destination->experience_sub_title !!} </p>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="tourPackages">
			            @include('frontend.experiences.experience_card')
			        </div>
			        {{--<div id="departuresLoadMoreBtn">
			            @if($departures->hasMorePages())
			                <div class="col-md-12 mt-4 text-center">
			                    <div id="loader1" class="loader" style="display:none;">
			                        <div class="spinner-border text-danger" role="status">
			                            <span class="sr-only">Loading...</span>
			                        </div>
			                    </div>
			                    <button id="loadMoreDeparturesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Experiences</button>
			                </div>
			            @endif
			        </div>--}}
        		</div>
        	</div>
            </div>
            <hr>
             <div id="Attractions" class="tab-pane fade in active">
            	   <div class="row">
                        <div class="col-12">
                            <div class="sectionHeading heading">
                               <h2 class="text-capitalize my-1">Top Attractions in {{$destination->dest_name}}</h2>
                            <p>{!! $destination->attraction_sub_title !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="tourPackages">
			             @include('frontend.common.poi_card')
			        </div>
			        {{--<div id="departuresLoadMoreBtn">
			            @if($departures->hasMorePages())
			                <div class="col-md-12 mt-4 text-center">
			                    <div id="loader1" class="loader" style="display:none;">
			                        <div class="spinner-border text-danger" role="status">
			                            <span class="sr-only">Loading...</span>
			                        </div>
			                    </div>
			                    <button id="loadMoreDeparturesBtn" class="load_more_btn"><i class="fas fa-plus me-2"></i> Load More Attractions</button>
			                </div>
			            @endif
			        </div>--}}
            </div>  
            <hr>
             <div class="row">
                    <div class="col-12">
                        <div class="sectionHeading heading">
                            <h2 class="text-capitalize my-1">Plan A Trip To {{$destination->dest_name}}</h2>
                            <p>{!! $destination->trip_sub_title !!}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"><p>{!! $destination->trip_description !!}</p></div>
                </div>
                 
       </div>


    </div>

</div>
@include('frontend.common.testimonial')
<script type="text/javascript">
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
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".nav-tabs a").forEach(function(tab) {
        tab.addEventListener("click", function(event) {
            event.preventDefault();
            let target = document.querySelector(this.getAttribute("href"));
            
            if (target) {
                // Remove active class from all tabs
                document.querySelectorAll(".nav-tabs li").forEach(function(li) {
                    li.classList.remove("active");
                });
                
                // Add active class to the clicked tab
                this.parentElement.classList.add("active");

                // Smooth scroll to the section
                window.scrollTo({
                    top: target.offsetTop - 180, // Adjust for navbar height if needed
                    behavior: "smooth"
                });

                // Show the corresponding section
                document.querySelectorAll(".tab-pane").forEach(function(pane) {
                    pane.classList.remove("show", "active");
                });

                target.classList.add("show", "active");
            }
        });
    });
});
</script>
@endsection
