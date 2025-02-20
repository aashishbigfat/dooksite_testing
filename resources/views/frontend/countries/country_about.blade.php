@extends('frontend.layouts.master')
@push('title') {{$country->about_meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country->about_meta_keywords}}">
<meta name="description" content="{{$country->about_meta_description}}">@endpush
@section('content')
    <!-- home section -->
   <div class="container">
    <div class="row mt-4">
        <div class="col-md-12 header-sticky">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="/" class="text-danger">Tours</a>/ {{$country->country_name}}
               </p>
                <ul class="nav nav-tabs shadow-sm p-3 mb-5 bg-white rounded ">
                    <li class="active"><a href="#Details">Details</a></li>
                   
                    <li><a href="#Itinerary">Tourism</a></li>
          
                    <li><a href="#Inclusion">Travel Guide</a></li>
             
                    <li><a href="#Attractions">Visa Info</a></li>
                </ul>
        </div>
        <div class="col-md-12">     
        <div id="Details" class="tab-pane fade in active">
            <div class="row">
                  <h4>{{$country->about_title}}</h4>
                <p>{{$country->about_sub_title}}</p>
                <div class="col-md-3">
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
                </div>


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
                            <h4>Details</h4>
                            <p class="color_gray ">
                                {!! $country->about_description !!}
                            </p>
                            
                          
                            <hr>
                            <div id="Itinerary" class="tab-pane fade">
                                <h4>Tourism</h4>
                             <p class="color_gray ">
                                {!! $country->tourism_description !!}
                            </p>
                            </div>
                            <div id="Inclusion" class="tab-pane fade mt-4 pt-4">
                            <h4>Travel Guide</h4>
                            <p class="color_gray "> {!! $country->guide_description !!}</p>
                            </div>
                             <div id="Attractions" class="tab-pane fade">
                                <h4>Visa Information</h4>
                                <p class="color_gray "> {!! $country->about_visa_information !!}</p>
                            </div>
                        <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

</div>
    <!-- testimonial -->
   @include('frontend.common.testimonial')
@endsection
