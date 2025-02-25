@extends('frontend.layouts.master')
@push('title') {{$homeSettings->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$homeSettings->meta_keywords}}">
<meta name="description" content="{{$homeSettings->meta_description}}">@endpush
@section('content')
    <!-- home section -->
    <div class="container" style="background-repeat: no-repeat;background-size: cover;background: url('{{asset('assets/images/col-img-01_2.png')}}');">
        <!-- first section  -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="heading-section">
                    <p class="my-0">FIND AWESOME FLIGHTS,HOTEL,TOUR AND PACKAGES</p>
                    <h2 class="fw-bold my-0">EXPLORE THE WORLD <br />TOGETHER</h2>
                    <span>Thinking of taking a breal from every day's life? Dont't worry,we take care of your trip.</span>
                </div>
                <div class="card py-4 px-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link an_flight active p-1 mx-1" id="simple-tab-1" data-bs-toggle="tab" href="#tour" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false"><img src="{{asset('assets/images/icons/globe1.png')}}" alt=""> Tours</a>
                        </li>
                         <li class="nav-item" role="presentation">
                            <a class="nav-link  an_flight p-1 mx-2" id="simple-tab-0" data-bs-toggle="tab" href="#flight" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true"><img src="{{asset('assets/images/icons/take-flight.png')}}" alt=""> Flights</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link an_flight p-1 mx-1" id="simple-tab-2" data-bs-toggle="tab" href="#hotel" role="tab" aria-controls="simple-tabpanel-2" aria-selected="false"><img src="{{asset('assets/images/icons/hotel-construction-iconSvg-co.png')}}" alt=""> Hotels</a>
                        </li>
                     
                    </ul>
                    <div class="tab-content pt-3" id="tab-content">
                        <div class="tab-pane" id="flight" role="tabpanel" aria-labelledby="simple-tab-0">
                            <div>
                                <ul class="flight_tab d-flex p-0">
                                    <li class=" active">One Way</li>
                                    <li class="">Round Tirp</li>
                                    <li class="">Multi City</li>
                                    <li class="">Direct FLight</li>
                                </ul>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <div class="from_flight px-2 py-2">
                                                <label for="exampleInputPassword1" class="form-label">From </label>
                                                <img src="{{asset('assets/images/icons/take-flight1.png')}}" alt="" style="float: right;">
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="New Delhi" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>DEL, Indira Gandhi International Airport</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="from_flight px-2 py-2">
                                                <label for="exampleInputPassword1" class="form-label">To </label>
                                                <img src="{{asset('assets/images/icons/take-flightto-1.png')}}" alt="" style="float: right;">
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="New Delhi" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>DEL, Indira Gandhi International Airport</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3 align-items-center">
                                            <div class="from_flight" style="display: flex;margin-left: 13px;">
                                                <div class="col-md-6 border-end col-6">
                                                    <div class=" px-1 py-1">
                                                        <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                        <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <div class=" px-1 py-1">
                                                        <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                        <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 col-6">
                                            <div class="from_flight px-2 py-2">
                                                <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>Business</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 d-flex align-items-end justify-content-center">
                                            <button class="btn btn-danger w-100" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane active" id="tour" role="tabpanel" aria-labelledby="simple-tab-1">
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Destination </label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Where are yoy going?" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Where are you going?</span>
                                        </div>
                                    </div>
                                    <div class="row mt-3 align-items-center">
                                          <div class="from_flight" style="display: flex;margin-left: 13px;">
                                            <div class="col-md-6 border-end col-6">
                                                <div class=" px-1 py-1">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class=" px-1 py-1">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 col-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Business</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 d-flex align-items-end justify-content-center">
                                        <button class="btn btn-danger w-100" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="hotel" role="tabpanel" aria-labelledby="simple-tab-2">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Destination </label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Where are you going?" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Where are you going?</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Country </label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="India" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Tap Here and Select Country</span>
                                        </div>
                                    </div>
                                    <div class="row mt-3 align-items-center">
                                         <div class="from_flight" style="display: flex;margin-left: 13px;">
                                            <div class="col-md-6 border-end col-6">
                                                <div class=" px-1 py-1">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class=" px-1 py-1">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 col-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Business</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 d-flex align-items-end justify-content-center">
                                        <button class="btn btn-danger w-100" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row p-2">
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[0]['DookSlug']}}/{{$campaign[0]['DookDepartureId']}}" target="_blank">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_1.png')}}" class="img-fluid" />
                            <div class="tour_1">
                                <p>{{ explode('-', $campaign[0]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                     @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif     
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[0]['Price'] + (round($campaign[0]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[0]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[0]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                     </a>
                    </div>
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[1]['DookSlug']}}/{{$campaign[1]['DookDepartureId']}}" target="_blank">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_2.png')}}" class="img-fluid" />
                            <div class="tour_2">
                               <p>{{ explode('-', $campaign[1]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                      @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif 
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[1]['Price'] + (round($campaign[1]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[1]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[1]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[2]['DookSlug']}}/{{$campaign[2]['DookDepartureId']}}" target="_blank">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_3.png')}}" class="img-fluid" />
                            <div class="tour_3">
                               <p>{{ explode('-', $campaign[2]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                      @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif 
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[2]['Price'] + (round($campaign[2]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[2]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[2]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-4 p-2">
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[3]['DookSlug']}}/{{$campaign[3]['DookDepartureId']}}" target="_blank">
                        <div class="group_img4">
                            <img src="{{asset('assets/images/Img_4.png')}}" class="img-fluid" />
                             <div class="tour_1">
                                <p>{{ explode('-', $campaign[3]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                       @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif 
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[3]['Price'] + (round($campaign[3]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[3]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[3]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[4]['DookSlug']}}/{{$campaign[4]['DookDepartureId']}}" target="_blank">
                        <div class="group_img5">
                            <img src="{{asset('assets/images/Img_5.png')}}" class="img-fluid" />
                            <div class="tour_5">
                                <p>{{ explode('-', $campaign[4]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                       @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif 
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[4]['Price'] + (round($campaign[4]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[4]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[4]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                    <div class="col-md-4 col-4 p-1">
                         <a href="group-tours/{{$campaign[5]['DookSlug']}}/{{$campaign[5]['DookDepartureId']}}" target="_blank">
                        <div class="group_img6">
                            <img src="{{asset('assets/images/Img_6.png')}}" class="img-fluid" />
                            <div class="tour_2">
                               <p>{{ explode('-', $campaign[5]['Name'])[0] }}</p>
                                <ul class="d-flex p-0 px-1">
                                      @php
                                        $inclusions = [];
                                        if(array_key_exists(0 ,$campaign[0]['DepartureDateWithPrice'])):
                                            foreach ($campaign[0]['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                $inclusions[] = $inclusion;
                                            endforeach;
                                        endif;
                                        $attractions  = array_filter($campaign[0]['Itinerary'], function ($item) {
                                            return !is_null($item['Attraction']);
                                        });
                                    @endphp
                                    @if (count(getInclusionByName($inclusions)) > 0)
                                        @foreach (getInclusionByName($inclusions) as $inclusion)
                                          <li><img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt=""  class="px-1 inc_icon"></li>
                                        @endforeach
                                    @endif 
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹{{ formatIndianNumber($campaign[5]['Price'] + (round($campaign[5]['Price'] * 0.05))) }}</small> <span>₹{{formatIndianNumber($campaign[5]['Price'])}}</span></p> 
                                   
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">{{$campaign[5]['nights_only']}}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="package_dest p-0 pt-3">
        <div class="container">
            <!-- GROUP TOURS -->
            <div class="row" style="margin-bottom: -100px;">
                <div class="col-md-6 col-7">
                    <div class="blog_section pb-4">
                        <h5>OUR GROUP TOURS</h5>
                    </div>
                </div>
                <div class="col-md-6 col-5 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6>View All <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="owl-carousel">
                        @foreach ($groupTours as $groupTour)
                            <div class="item">
                                <div class="card">
                                      <a href="group-tours/{{$groupTour['DookSlug']}}/{{$groupTour['DookDepartureId']}}" target="_blank">
                                    <img src="@if (array_key_exists(1, $groupTour['DookImage'])) {{$groupTour['DookImage'][1]}} @else {{asset('assets/images/maine-Image.jpg')}} @endif" class="card-img-top" alt="{{$groupTour['Name']}}">
                                    <div class="best_selling">
                                        @if ($groupTour['BestSellingPackage'])
                                            <img src="{{asset('assets/images/icons/Rectangle19435.png')}}" class="w-auto">
                                            <p class="best_sell">BEST SELLING</p>
                                        @endif
                                    </div>
                                    <div class="card-body pack_height" >
                                       <h6>{{ explode('-', $groupTour['Name'])[0] }}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>{{$groupTour['DayNight']}}</p>
                                            </div>
                                            <div class="col-md-6 d-flex justify-content-end">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            </div>
                                            <div class="col-md-12 d-flex">  
                                                @php
                                                    $inclusions = [];
                                                    if(array_key_exists(0 ,$groupTour['DepartureDateWithPrice'])):
                                                        foreach ($groupTour['DepartureDateWithPrice'][0]['Inclusion'] as $inclusion):
                                                            $inclusions[] = $inclusion;
                                                        endforeach;
                                                    endif;
                                                    $attractions  = array_filter($groupTour['Itinerary'], function ($item) {
                                                        return !is_null($item['Attraction']);
                                                    });
                                                @endphp
                                                @if (count(getInclusionByName($inclusions)) > 0)
                                                    @foreach (getInclusionByName($inclusions) as $inclusion)
                                                        <img src="{{env('AWS_BUCKET_URL')}}/inclusion/{{$inclusion->icon }}" alt="" class="inclu_icon">
                                                    @endforeach
                                                @endif              
                                            </div>
                                            <div class="col-md-12 hightlights mt-3">
                                                <h6>Tours Highlights</h6>
                                                <ul class="hightlights m-0">
                                                    @foreach (getFirstNonNullAttraction($groupTour['Itinerary']) as $attraction)
                                                        <li>{{$attraction['Name']}}</li>
                                                    @endforeach      
                                                </ul>
                                                <p >
                                                    <small class="text-decoration-line-through"> ₹{{ formatIndianNumber($groupTour['Price'] + (round($groupTour['Price'] * 0.05))) }}</small>  
                                                    <span>₹{{formatIndianNumber($groupTour['Price'])}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- dook special -->
            <div class="row dook_special pb-5" style="padding-top: 150px;">
                <div class="col-md-12 mb-4">
                    <h6>DOOK SPECIAL</h6>
                    <h4>WHY TRAVEL WITH DOOK?</h4>
                </div>
                @foreach ($dook_special as $dookspecial)
                <div class="col-md-3 pb-2 pt-2">
                   <a href="{{$dookspecial->slug}}" target="_blank">
                    <div class="card" style="border-radius: 20px;">
                        <img src="{{asset('assets/images')}}/{{$dookspecial->image}}" class="card-img-top" alt="...">
                        <div class="dook_special1 shadow">
                            <img src="{{asset('assets/images/icons/')}}/{{$dookspecial->icon_image}}" class="w-auto" style="top: 13px;position: relative;">
                        </div>
                        <div class="card-body" style="margin-top: -30px;">
                            <h3 class="text-dark">{{ $dookspecial->count}}</h3>
                            <h6 class="text-dark">{{ $dookspecial->title ?? ''}}</h6>
                        </div>
                    </div>
                   </a>
                </div>
                @endforeach
            </div>
            <!-- bestselling -->
            <div class="row pb-5 pt-5">
                <div class="col-md-6">
                    <div class="blog_section pb-4">
                        <h5>BESTSELLING PACKAGES</h5>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6>View All <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="owl-carousel">
                        @foreach ($departures as $departure)
                            <div class="item">
                                <div class="card">
                                    <a href="{{ url($departure->slug_url_pre.'/'.$departure->slug_url.'/'.$departure->dep_dook_ref_id) }}" target="_blank">
                                    <img src="{{env('AWS_BUCKET_URL').'/package/'.$departure->image}}" class="card-img-top" alt="...">                                     
                                      @if($departure->featured == 1)
                                        <div class="best_selling">
                                            <img src="{{ asset('assets/images/icons/Rectangle19435.png') }}" class="w-auto">
                                            <p class="best_sell">Best Selling</p>
                                        </div> 
                                    @endif
                                    {{-- @dd($departure) --}}
                                    <div class="card-body pack_height">
                                        <h6>{{$departure->title}}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>{{$departure->duration}}</p>
                                            </div>
                                            <div class="col-md-6 d-flex justify-content-end">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            </div>
                                            <div class="col-md-12 d-flex">                     
                                                <img src="{{asset('assets/images/inclusion/airfare.png')}}" alt="" class="w-auto">
                                                <img src="{{asset('assets/images/inclusion/trekking-27045311.png')}}" alt="" class="w-auto px-2">
                                                <img src="{{asset('assets/images/inclusion/sailboat-17434651.png')}}" alt="" class="w-auto">
                                            </div>
                                            <div class="col-md-12 hightlights mt-3">
                                                <h6>Tours Highlights</h6>
                                                <ul class="hightlights m-0">
                                                    <li>Munnar Beach</li>
                                                    <li>Varkala Beach</li>
                                                    <li>Periyar National Park</li>
                                                    <li>Lighthouse Beach</li>
                                                </ul>
                                                @if ($departure->price !='' || !is_null($departure->price))
                                                    <p>
                                                        <small class="text-decoration-line-through">₹{{ formatIndianNumber($departure->price + (round($departure->price * 0.05))) }}</small> 
                                                        <span>₹ {{formatIndianNumber($departure->price)}}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- destination -->
            <div class="row pb-5 pt-5">
                <div class="col-md-6">
                    <div class="blog_section pb-4">
                        <h5>WE PROVIDE TOP DESTINATIONS</h5>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6><a href="{{route('frontend.destinations')}}" target="_blank">All Destination <img src="{{asset('assets/images/arrow.png')}}"></a></h6>
                    </div>
                </div>
                <div class="row p-0">
                    @foreach ($topDestinations as $topDestination) 
                        <div class="col-md-2 text-center col-6">
                             <a href="{{url('destinations')}}/{{$topDestination->destination->slug_url}}" target="_blank">
                            <img src="{{env('AWS_BUCKET_URL').'/poi/'.$topDestination->destination->image}}" class="dest_imgan">
                            <p class="an_dest">{{$topDestination->departureDestination->count()}} TOURS</p>
                            <h6 class="mt-3">{{$topDestination->destination->actualname}}</h6>
                        </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active slide">
                                <img src="{{asset('assets/images/image(13).png')}}" class="d-block w-100" alt="..." style="height: 400px;">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Experience -->
    <section class="experience">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="blog_section pb-4">
                        <h6>POPULAR EXPERIENCES</h6>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6>View All <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-8">
                             <a href="{{url('/')}}/{{$homeSettings->experinceOne->slug_url}}" class="" target="_blank">
                            <div class="exp_img1" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image1}}')">
                                <div class="honeymoon">
                                    <h4>{{$homeSettings->experinceOne->experience_name}}</h4>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-md-4">
                             <a href="{{url('/')}}/{{$homeSettings->experinceTwo->slug_url}}" class="" target="_blank">
                            <div class="exp_img2" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image2}}')">

                                <div class="family">
                                    <h4>{{$homeSettings->experinceTwo->experience_name}}</h4>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                             <a href="{{url('/')}}/{{$homeSettings->experinceThree->slug_url}}" class="" target="_blank">
                            <div class="exp_img3" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image3}}')">
                                <div class="shopping">
                                    <h4>{{$homeSettings->experinceThree->experience_name}}</h4>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-md-8">
                             <a href="{{url('/')}}/{{$homeSettings->experinceFour->slug_url}}" class="" target="_blank">
                            <div class="exp_img4" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image4}}')">
                                <div class="nightlife">
                                    <h4>{{$homeSettings->experinceFour->experience_name}}</h4>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{url('/')}}/{{$homeSettings->experinceFive->slug_url}}" class="" target="_blank">
                    <div class="exp_img5" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image5}}')">  
                        <div class="turkey">
                            <h4>{{$homeSettings->experinceFive->experience_name}}</h4>
                        </div>
                    </div>
                   </a>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial -->
      @include('frontend.common.testimonial')
    <!-- blog section -->
     <section class="bg-white p-0">
        <div class="container">
            <div class="row mb-4 mt-4">
                <div class="col-md-12 text-center">
                    <h4>{{$homeSettings->text_heading}}</h4>
                    <p class="color_gray pb-3">{{$homeSettings->text_sub_heading}}</p>
                </div>
                <div class="col-md-12 color_gray">
                    {!!$homeSettings->description!!}
                </div>
            </div>
            <div class="row ">
                <div class="col-md-6">
                    <div class="blog_section pb-4">
                        <h6>STAY UPDATE WITH DOOK TIPS</h6>
                    </div>
                </div>
                 <div class="col-md-6 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6>All Blogs Post <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div> 
                 @foreach (getBlog() as $key=> $latestPost) 
                    @if ($key < 3)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{$latestPost['image']}}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="blog_date text-center">
                                                <h4>{{date('d M',strtotime($latestPost['published_date']))}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Travel <img src="{{asset('assets/images/icons/Rectangle19436.png')}}">  Admin <img src="{{asset('assets/images/icons/Rectangle19436.png')}}"> Coments (8)</p>
                                    <h5 class="card-title">{{$latestPost['title']}}</h5>
                                    <p class="card-text"> {{ Str::limit($latestPost['short_description'], 100, '...') }}</p>
                                    <a href="{{url('blog')}}/{{$latestPost['slug']}}/" target="_blank" class="btn btn-danger">Read More</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                    
            </div>
        </div>
    </section>

    <!-- Quick Link Section -->
    <div class="container" style="margin-bottom: 85px !important;">
        <div class="row">
            <div class="col-md-12 footer_links">
                <h6 class="mt-4">Popular Countries</h6>
                 <ul class="listInlinecoma" style="text-align: justify;">
                    @foreach($countries as $key => $country)
                    <li><a href="{{url('/')}}/{{$country->slug}}" class=""
                            target="_blank">{{$country->name}}</a></li>
                    @endforeach
                </ul>
                <h6 class="mt-4">Popular Destinations</h6>
                 <ul class="listInlinecoma" style="text-align: justify;">
                    @foreach($destinations as $key => $destination)
                    <li><a href="{{url('destinations')}}/{{$destination->slug}}" class=""
                            target="_blank">{{$destination->name}}</a></li>
                    @endforeach
                </ul>
                <h6 class="mt-4">Popular Experiences</h6>
                 <ul class="listInlinecoma" style="text-align: justify;">
                        @foreach($experiences as $key => $experience)
                        <li><a href="{{url('/')}}/{{$experience->slug}}" class=""
                                target="_blank">{{$experience->name}}</a></li>
                        @endforeach
                    </ul>
            </div>
        </div>
    </div>
    
    <!-- Option 1: Bootstrap Bundle with Popper -->
@endsection
