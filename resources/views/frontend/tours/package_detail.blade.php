@extends('frontend.layouts.master')
@push('title') {{$departure->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure->meta_keywords}}">
<meta name="description" content="{{$departure->meta_description}}">@endpush
@section('content')

<!-- home section -->
<div class="container">
    <div class="row mt-4">
        <div class="col-md-12 header-sticky">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="/" class="text-danger">Tours</a>/
                {{ $departure->title }}</p>
                 <ul class="nav nav-tabs shadow-sm p-3 mb-5 bg-white rounded">
                    <li class="active"><a href="#Details">Details</a></li>
                   
                    <li><a href="#Itinerary">Itinerary</a></li>
                      @if(count($departure->inclusions)>0)
                    <li><a href="#Inclusion">Inclusion</a></li>@endif
             
                    <li><a href="#Attractions">Tour Attractions</a></li>

                    @if(count($departure->visa)>0)
                    <li><a href="#Visa">Visa Info</a></li>
                    @endif
                    @if($departure->conditions != "" || $departure->conditions != null)
                    <li><a href="#Terms">Terms</a></li>
                    @endif
                </ul>
        </div>
        <div class="col-md-12">
            <div id="Details" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[0]['image']) && !empty($departure->images[0]['image']) ? env('Image_Urls') . $departure->images[0]['image'] : url('images/no_image.jpg') }}"
                                    alt="" class="w-100 pack_img">
                            </div>
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[1]['image']) && !empty($departure->images[1]['image']) ? env('Image_Urls') . $departure->images[1]['image'] : url('images/no_image.jpg') }}"
                                    alt="" class="w-100 pack_img">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <img src="{{ isset($departure->image) && !empty($departure->image) ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images/no_image.jpg') }}"
                            alt="" class="w-100 pack_img_mid">
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[2]['image']) && !empty($departure->images[2]['image']) ? env('Image_Urls') . $departure->images[2]['image'] : url('images/no_image.jpg') }}"
                                    alt="" class="w-100 pack_img">
                            </div>
                            <div class="col-md-12 mt-4">
                                <img src="{{ isset($departure->images[3]['image']) && !empty($departure->images[3]['image']) ? env('Image_Urls') . $departure->images[3]['image'] : url('images/no_image.jpg') }}"
                                    alt="" class="w-100 pack_img">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4 class="mt-4">{{ $departure->title }}</h4>
                        <div><span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span> <span class="color_gray">(1 Review)</span>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        @if($departure->dep_type == "main")
                        <button class="btn btn-danger mt-4 h-50">Book Now</button>
                        @else
                        <button class="btn btn-danger mt-4 h-50">On Request</button>
                        @endif
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/tag.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Price</p>
                                        <p style="margin-top: -18px;"><span class="text-success">₹ 1,60,014</span> <span
                                                class="color_gray"><s> ₹ 1,80,014</s></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{asset('assets/images/icons/clock.png')}}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Duration</p>
                                        <p style="margin-top: -18px;">{{ $departure->no_of_nights }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/file-text.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>#Pkg Id</p>
                                        <p style="margin-top: -18px;">{{ $departure->dep_dook_ref_id }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin2.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Destinations</p>
                                        <p style="margin-top: -18px;">{{ $departure->destinationTotal }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Attractions</p>
                                        <p style="margin-top: -18px;">{{ $departure->poiTotal }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin(1).png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>From</p>
                                        <p style="margin-top: -18px;">{{$departure->from}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/map-pin(1).png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Ending at</p>
                                        <p style="margin-top: -18px;">{{$departure->ending_at}}</p>
                                    </div>
                                </div>
                            </div>
                            @if($departure->dep_type == "main")
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/calendar.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>Date</p>
                                        <p style="margin-top: -18px;">26/11/2024</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-2 tags">
                                        <img src="{{ asset('assets/images/icons/users1.png') }}" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <p>No. of Travelers</p>
                                        <p style="margin-top: -18px;">01</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
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
                                            <li>{{-- $inclusion->name --}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Price Excludes</h6>
                                        <ul class="inclusion1 p-0">
                                            @foreach($departure->uniqueExclusions as $exclusion)
                                            <li>{{ $exclusion }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                                {{-- <h6>What to Expect</h6>
                                <p class="color_gray fs-6">
                                    When choosing a destination for your backpacking trip, it is important to consider the
                                    level of difficulty of the trail and the weather conditions. Some popular backpacking
                                    destinations include national parks, wilderness areas, and mountain ranges. It is also
                                    important to obtain any necessary permits and to be aware of any regulations or rules
                                    for the area you plan to visit.
                                </p>
                                <ul style="list-style-type: none;" class="color_gray p-0">
                                    <li><img src="./images/icons/dot.svg" alt=""> View the Nature</li>
                                    <li><img src="./images/icons/dot.svg" alt=""> Hiking in the forest</li>
                                    <li><img src="./images/icons/dot.svg" alt=""> Discover the famous view point "The Lark"
                                    </li>
                                    <li><img src="./images/icons/dot.svg" alt=""> Sunset on the cruise</li>
                                </ul>
                                <hr> --}}
                                <div id="Itinerary" class="tab-pane fade">
                                    <h6>Itinerary</h6>
                                    <div class="accordion mb-3" id="accordionExample">
                                        @foreach($departure->itinerary as $index => $day)
                                        <div class="accordion-item border-bottom bg-transparent border-0 border-2">
                                            <h2 class="accordion-header" id="faq{{ $day->id }}">
                                                <button class="accordion-button bg-transparent text-dark p-2" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#faq{{ $day->id }}collapseOne"
                                                    aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                    aria-controls="faq{{ $day->id }}collapseOne">
                                                    Day {{ $day->day_number }} - {{ $day->day_heading }}
                                                </button>
                                            </h2>
                                            <div id="faq{{ $day->id }}collapseOne"
                                                class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                aria-labelledby="faq{{ $day->id }}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body p-0 m-0">
                                                    <ul class="color_gray fs-6 p-0">
                                                        {!! ($day->description) !!}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
            <div id="Attractions" class="tab-pane fade">
                <h5>Top Attractions</h5>
                <div class="row mb-4">
                   @foreach($departure->poi as $pointOfInterest)
                      @include('frontend.poi.poi_card')
                    @endforeach

                </div>
            </div>
            <hr>
             @if(count($departure->visa)>0)
            <div id="Visa" class="tab-pane fade mb-3">
                <h5>Visa</h5>
                  @foreach($departure->visa as $visa)      
                   <p>{{$visa->phCountry}} to {{$visa->visiting_country}} Visa Informations  
                      <a href="{{url('/')}}/{{$visa->url}}" target="_blank" class="text-danger">
                        Click Here
                      </a></p>
                  @endforeach                    
            </div>
              @endif 
              <hr>
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
                    <!-- <div class="accordion-item mt-3 bg_accordion1">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Payment Policy
                            </button>
                        </h2>
                        <div id="faq2collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq2"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until
                                the collapse plugin adds the appropriate classes that we use to style each element. These
                                classes control the overall appearance, as well as the showing and hiding via CSS
                                transitions. You can modify any of this with custom CSS or overriding our default variables.
                                It's also worth noting that just about any HTML can go within the
                                <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg_accordion1 mt-3">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Cancellation Policy
                            </button>
                        </h2>
                        <div id="faq3collapseThree" class="accordion-collapse collapse" aria-labelledby="faq3"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                                collapse plugin adds the appropriate classes that we use to style each element. These
                                classes control the overall appearance, as well as the showing and hiding via CSS
                                transitions. You can modify any of this with custom CSS or overriding our default variables.
                                It's also worth noting that just about any HTML can go within the
                                <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
             @endif
       </div>


    </div>

</div>


@include('frontend.common.testimonial')
@endsection