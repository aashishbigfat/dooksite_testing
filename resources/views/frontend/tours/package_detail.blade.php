@extends('frontend.layouts.master')
@push('title') {{$departure->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure->meta_keywords}}">
<meta name="description" content="{{$departure->meta_description}}">
<meta property="og:description" content="{{$departure->meta_description}}">
<meta name="twitter:description" content="{{$departure->meta_description}}">@endpush
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
                <a href="{{route('frontend.international-tour-packages')}}">Tours</a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item">
                <span class="breadcrumb-current">{{ $departure->title }}</span>
            </div>
        </div>
    </div>
</section>


<!-- Page Header Content -->
<section class="page-header-content">
    <div class="container">
        <div class="animate-fade-up delay-100">
            <h1 class="page-title mt-0">{{$departure->title}}</h1>
            <p class="page-subtitle">
                {{$departure->sub_title}}
            </p>
        </div>
    </div>
</section>

<div class="container">
    <div class="row mt-4">
        <div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
            <ul class="nav nav-tabs product_detail d-flex" style="border-bottom:none">
                <li class="active"><a href="#Details">Details</a></li>

                <li><a href="#Itinerary">Itinerary</a></li>
                @if(count($departure->inclusions)>0)
                <li><a href="#Inclusion">Inclusions</a></li>@endif

                <li><a href="#Attractions">Tour Attractions</a></li>
                @if(count($departure->activities)>0)
                <li><a href="#Thingstodo">Things To Do</a></li> @endif
                @if(count($departure->visa)>0)
                <li><a href="#Visa">Visa Info</a></li>
                @endif
                @if($departure->conditions != "" || $departure->conditions != null)
                <li><a href="#Terms">Terms</a></li>
                @endif
            </ul>
        </div>
        <div class="col-md-12">
            <!-- detail -->
            <div id="Details" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <div class="row"> -->
                        <!--       <div class="col-md-8">
                               <h4 class="">{{ $departure->title }}</h4>
                                <div><span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span> <span class="color_gray">(1 Review)</span>
                                </div>                          
                           </div> -->

                        <!-- </div> -->
                        <div class="row mt-2">
                            @if (!empty($departure->price))
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/tag.png') }}" alt="Price">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Price</p>
                                        <p style="margin-top: -18px; color: green;">
                                            ₹ {{ $departure->price ?? 0 }}
                                            <span class="color_gray"><s>₹
                                                    {{ number_format($departure->price_with_profit, 0) }}</s></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/clock.png')}}" alt="Duration">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Duration</p>
                                        <p style="margin-top: -18px;"> {{$departure->no_of_nights}} Nights
                                            {{$departure->no_of_days}} Days </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/file-text.png') }}" alt="pkgid">
                                    </div>
                                    <div class="col-md-9">
                                        <p>#Pkg Id</p>
                                        <p style="margin-top: -18px;">{{ $departure->dep_dook_ref_id }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin2.png') }}" alt="Destination">
                                    </div>
                                    <div class="col-md-9 trip-introduction-countries position-relative listAttraction">
                                        <p>Destinations</p>
                                        <p style="margin-top: -18px;">{{ $departure->destinationTotal }}</p>
                                        <div class="dropDestinHover">
                                            @foreach ($departure->destinations as $destination)
                                            <a href="{{ url('destinations/' . $destination->slug_url) }}"
                                                class="destination-name" target="_blank">
                                                {{ $destination->dest_name }}
                                            </a>
                                            <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map.png') }}" alt="Attractions">
                                    </div>
                                    <div class="col-md-9 trip-introduction-countries position-relative listAttraction">
                                        <p>Attractions</p>
                                        <p style="margin-top: -18px;">{{ $departure->poiTotal }}</p>
                                        <div class="dropDestinHover">
                                            @foreach ($departure->poi as $pois)
                                            <a href="{{ url('poi/' . $pois->poi_url . '/' . $pois->poiId) }}"
                                                class="destination-name" target="_blank">
                                                {{ $pois->poi_name }}
                                            </a>
                                            <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($departure->from))
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin(1).png') }}" alt="From Date">
                                    </div>
                                    <div class="col-md-9">
                                        <p>From</p>
                                        <p style="margin-top: -18px;">{{$departure->from}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset($departure->ending_at))
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin(1).png') }}" alt="To Date">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Ending at</p>
                                        <p style="margin-top: -18px;">{{$departure->ending_at}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- @if($departure->dep_type == "main")
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/calendar.png') }}" alt="Date">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Date</p>
                                        <p style="margin-top: -18px;">26/11/2024</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6 col-6">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/users1.png') }}" alt="No of Travelers">
                                    </div>
                                    <div class="col-md-9">
                                        <p>No. of Travelers</p>
                                        <p style="margin-top: -18px;">01</p>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6 mt-4 d-none d-lg-block">
                                @if($departure->dep_type == "main")
                                <button class="btn btn-danger mt-4 ">Book Now</button>
                                @else
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                    onclick="openEnquiryModal()">Enquire Now</button>
                                @endif
                            </div>
                        </div>

                    </div>
                    @if(count($departure->images)>0)
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[0]['image']) && !empty($departure->images[0]['image']) ? env('Image_Urls') . $departure->images[0]['image'] : url('images/no_image.jpg') }}"
                                    alt="image1" class="w-100 pack_img">
                            </div>
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[1]['image']) && !empty($departure->images[1]['image']) ? env('Image_Urls') . $departure->images[1]['image'] : url('images/no_image.jpg') }}"
                                    alt="image2" class="w-100 pack_img">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[2]['image']) && !empty($departure->images[2]['image']) ? env('Image_Urls') . $departure->images[2]['image'] : url('images/no_image.jpg') }}"
                                    alt="image3" class="w-100 pack_img">
                            </div>
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[3]['image']) && !empty($departure->images[3]['image']) ? env('Image_Urls') . $departure->images[3]['image'] : url('images/no_image.jpg') }}"
                                    alt="image4" class="w-100 pack_img">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <h6>Details</h6>
                <p class="color_gray fs-6">{!! $departure->description !!}</p>
                <h6>Activities</h6>
                <div class="color_gray row">
                    @if (!empty($departure->activities) && count($departure->activities) > 0)
                    @foreach($departure->activities as $activity)
                    <div class="col-md-3">{{ $activity->activity_name }}</div>
                    @endforeach
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Price Includes</h6>
                        <ul class="inclusion1 p-0">
                            @foreach($departure->inclusions as $inclusion)
                            <li>{{ $inclusion->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @if(count($departure->uniqueExclusions)>0)
                    <div class="col-md-6">
                        <h6>Price Excludes</h6>
                        <ul class="inclusion1 p-0">
                            @foreach($departure->uniqueExclusions as $exclusion)
                            <li>{{ $exclusion }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            <hr>
            <!-- itinery -->
            <div id="Itinerary" class="tab-pane fade col-md-12">
                <h6>Itinerary</h6>
                <div class="accordion mb-3" id="accordionExample">
                    @foreach($departure->itinerary as $index => $day)
                    <div class="accordion-item  bg-transparent border-0 border-2">
                        <h2 class="accordion-header" id="faq{{ $day->id }}">
                            <button
                                class="accordion-button bg-transparent text-dark p-2 {{ $index == 0 ? '' : 'collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $day->id }}collapseOne"
                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                aria-controls="faq{{ $day->id }}collapseOne">
                                Day {{ $day->day_number }} - {{ $day->day_heading }}
                            </button>
                        </h2>
                        <div id="faq{{ $day->id }}collapseOne"
                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                            aria-labelledby="faq{{ $day->id }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body p-0 ">
                                <ul class="color_gray fs-6 ">
                                    {!! ($day->description) !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <hr>

            <!-- inclusion -->
            <div id="Inclusion" class="tab-pane fade mt-4 pt-4">
                <h5>Includes</h5>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <ul class="inclusion">
                            @foreach($departure->inclusions as $inclusion)
                            <li>{{ $inclusion->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <hr>
            <!-- attraction -->
            <div id="Attractions" class="tab-pane fade">
                <h5>Top Attractions</h5>
                <div class="row mb-4">
                    @foreach($departure->poi as $pointOfInterest)
                    @include('frontend.poi.poi_card')
                    @endforeach

                </div>
            </div>
            <hr>
            <!-- activities -->
            @if(count($departure->activities)>0)
            <div id="Thingstodo" class="tab-pane fade">
                <h5>Things to Do</h5>
                <div class="row mb-4">
                    @foreach($departure->activities as $key => $activity)
                    <div class="col-md-2 col-12 mb-3">
                        <a href="{{url('activities')}}/{{$activity->slug_url}}" class="thingstodo-picture"
                            target="_blank">
                            <div class="card attraction_card">
                                <img src="{{ $activity->image ?? url('images/poi-no-image.jpg') }}"
                                    alt="{{$activity->activity_name}}" />
                                <div class="attraction_card_body">
                                    <h6 class="m-0 p-0">{{$activity->activity_name}}</h6>

                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <hr>
            <!-- visa -->
            @if(count($departure->visa)>0)
            <div id="Visa" class="tab-pane fade mb-3">
                <h5>Visa</h5>
                @foreach($departure->visa as $visa)
                <p>{{$visa->phCountry}} to {{$visa->visiting_country}} Visa Informations
                    <a href="{{url('/')}}/{{$visa->url}}" target="_blank" class="text-danger">
                        Click Here
                    </a>
                </p>
                @endforeach
            </div>
            @endif
            <hr>
            <!-- terms and condition -->
            @if($departure->conditions != "" || $departure->conditions != null)
            <div id="Terms" class="tab-pane fade">
                <h5>Dook Policy</h5>
                <div class="accordion mb-3" id="accordionExample">
                    <div class="accordion-item bg_accordion1">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Terms
                            </button>
                        </h2>
                        <div id="faq1collapseOne" class="accordion-collapse collapse show" aria-labelledby="faq1"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body color_gray">
                                {!! $departure->conditions !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>


    </div>

</div>


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