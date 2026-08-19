@extends('frontend.layouts.master')
@push('title') {{$country->about_meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country->about_meta_keywords}}">
<meta name="description" content="{{$country->about_meta_description}}">
<meta property="og:description" content="{{$country->meta_description}}">
<meta name="twitter:description" content="{{$country->meta_description}}">
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
            <a href="{{route('frontend.countries')}}">Countries</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$country->country_name}}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Page Header Content -->
    <section class="page-header-content">
      <div class="container">
        <div class="animate-fade-up delay-100">
          <h1 class="page-title mt-0">{{$country->about_title}}</h1>
          <p class="page-subtitle">
          {{$country->about_sub_title}}
          </p>
        </div>
      </div>
    </section>

   <div class="container">
    <div class="row mt-4">
               <div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
                <ul class="nav nav-tabs product_detail d-flex " style="border-bottom:none">
                    <li class="active"><a href="#Details">Details</a></li>
                   
                    <li><a href="#Itinerary">Tourism</a></li>
          
                    <li><a href="#Inclusion">Travel Guide</a></li>
             
                    <li><a href="#Attractions">Visa Information</a></li>
                </ul>
            </div>
        
        <div class="col-md-12">     
        <div id="Details" class="tab-pane fade in active">
            <div class="row">                  
                <h2>About {{$country->country_name}} — Tourism, Travel Guide, Visa and Essential Facts</h2>
                <!-- <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <img src="url('images/no_image.jpg') "
                                alt="" class="w-100 pack_img">
                        </div>
                        <div class="col-md-12 mt-4">
                            <img src="url('images/no_image.jpg') "
                                alt="" class="w-100 pack_img">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4">
                    <img src="url('images/no_image.jpg')"
                        alt="" class="w-100 pack_img_mid">
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <img src=" url('images/no_image.jpg')"
                                alt="" class="w-100 pack_img">
                        </div>
                        <div class="col-md-12 mt-4">
                            <img src=" url('images/no_image.jpg')"
                                alt="" class="w-100 pack_img">
                        </div>
                    </div>
                </div> -->


                <div class="col-md-12 mt-4">
                    <div class="row">
                        <div class="col-md-2">                 
                          <h6> <strong>Formal Name: </strong> </h6>
                            <p>{{$country->official_name}}</p>                              
                        </div>
                        <div class="col-md-2">
                            <h6><strong>Location of Kazakhstan: </strong> </h6>
                            <p>{{$country->sub_continent}}</p>                                    
                        </div>
                        <div class="col-md-2">
                           <h6><strong>Capital: </strong></h6>
                            <p> {{$country->capital}}</p>
                        </div>
                        <div class="col-md-2">   
                           <h6><strong>Demonym: </strong></h6>
                            <p>{{$country->demonym_name}}</p>
                        </div>
                        <div class="col-md-2">
                            <h6><strong>Area: </strong></h6>
                            <p>{{$country->area}}{{$country->area_unit}}</p>
                        </div>
                        <div class="col-md-2">
                            <h6><strong>Population: </strong></h6>
                            <p> {{$country->population}}</p>
                        </div>
                        <div class="col-md-2">
                            <h6><strong>Ethnic Groups: </strong></h6>
                            <p >{{$country->ethnicity_name}}</p>
                        </div>

                        <div class="col-md-2">  
                            <h6><strong>Languages: </strong> </h6>
                            <p>{{$country->language_name}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Religion: </strong> </h6>
                            <p>{{$country->religion_name}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Currency: </strong> </h6>
                            <p> {{$country->currency}} ({{$country->currency_code}}, {{$country->currency_symbol}})</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Best Time To Visit: </strong> </h6>
                            <p>{{$country->name}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Calling Code: </strong> </h6>
                            <p>{{$country->isd_code}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Drives On: </strong> </h6>
                            <p>{{$country->drives_on}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Visa on Arrival: </strong> </h6>
                            <p>{{$country->visa_on_arrival}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Major Cities: </strong> </h6>
                            <p>{{$country->dest_name}}</p>
                        </div>
                        <div class="col-md-2">  
                            <h6><strong>Administrative and Territorial Structure: </strong> </h6>
                            <p>{!! $country->administrative_territorial !!}</p>
                        </div>
                          <div class="col-md-2">  
                            <h6><strong>Land Boundaries: </strong> </h6>
                            <p>{!! $country->land_boundaries !!}</p>
                        </div>


                        <div class="col-md-12">
                            <!-- <h4>Details</h4> -->
                            <p class="color_gray ">
                                {!! $country->about_description !!}
                            </p>
                            
                          </div>
                          </div>
                            <hr>
                            <div class="col-md-12">
                            <div id="Itinerary" class="tab-pane fade">
                                <h2>{{$country->country_name}} Tourism — Top Destinations and Attractions</h2>
                             <p class="color_gray ">
                                {!! $country->tourism_description !!}
                            </p>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <div id="Inclusion" class="tab-pane fade mt-4 pt-4">
                            <h2> {{$country->country_name}} Tour & Travel Guide</h2>
                            <p class="color_gray "> {!! $country->guide_description !!}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div id="Attractions" class="tab-pane fade visa">
                                <h2>{{$country->country_name}} Visa Information</h2>
                                <p class="color_gray "> {!! $country->about_visa_information !!}</p>
                            </div>
                        </div>
                        <hr>
                        
                    
                </div>
            </div>
        </div>
    </div>

    </div>

</div>
    <!-- testimonial -->
   @include('frontend.common.testimonial')
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
