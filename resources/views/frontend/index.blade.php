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
                    <p class="my-0">{{$homeSettings->banner_title}}</p>
                    <h2 class="fw-bold my-0">EXPLORE THE WORLD <br />TOGETHER</h2>
                    <span>Thinking of taking a breal from every day's life? Dont't worry,we take care of your trip.</span>
                </div>
                <div class="card py-4 px-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link an_flight active" id="simple-tab-1" data-bs-toggle="tab" href="#tour" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false"><img src="{{asset('assets/images/icons/globe1.png')}}" alt=""> Tours</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link an_flight" id="simple-tab-2" data-bs-toggle="tab" href="#hotel" role="tab" aria-controls="simple-tabpanel-2" aria-selected="false"><img src="{{asset('assets/images/icons/hotel-construction-iconSvg-co.png')}}" alt=""> Hotels</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link  an_flight" id="simple-tab-0" data-bs-toggle="tab" href="#flight" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true"><img src="{{asset('assets/images/icons/take-flight.png')}}" alt=""> Flights</a>
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
                                        <div class="col-md-6">
                                            <div class="from_flight px-3 py-2">
                                                <label for="exampleInputPassword1" class="form-label">From </label>
                                                <img src="{{asset('assets/images/icons/take-flight1.png')}}" alt="" style="float: right;">
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="New Delhi" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>DEL, Indira Gandhi International Airport</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="from_flight px-3 py-2">
                                                <label for="exampleInputPassword1" class="form-label">To </label>
                                                <img src="{{asset('assets/images/icons/take-flightto-1.png')}}" alt="" style="float: right;">
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="New Delhi" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>DEL, Indira Gandhi International Airport</span>
                                            </div>
                                        </div>
                                        <div class="row mt-3 align-items-center">
                                            <div class="from_flight" style="display: flex;margin-left: 13px;">
                                                <div class="col-md-6 border-end">
                                                    <div class="">
                                                        <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                        <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class=" px-3 py-2">
                                                        <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                        <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                        <span>Thursday</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <div class="from_flight px-3 py-2">
                                                <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                                <span>Business</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-md-6 d-flex align-items-end justify-content-center">
                                            <button class="btn btn-danger" type="submit">Search</button>
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
                                            <div class="col-md-6 border-end">
                                                <div class="">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" px-3 py-2">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Business</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-6 d-flex align-items-end justify-content-center">
                                        <button class="btn btn-danger" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="hotel" role="tabpanel" aria-labelledby="simple-tab-2">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Destination </label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Where are you going?" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Where are you going?</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Country </label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="India" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Tap Here and Select Country</span>
                                        </div>
                                    </div>
                                    <div class="row mt-3 align-items-center">
                                        <div class="from_flight" style="display: flex;margin-left: 13px;">
                                            <div class="col-md-6 border-end">
                                                <div class="">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" px-3 py-2">
                                                    <label for="exampleInputPassword1" class="form-label">Journey date </label>
                                                    <input type="date" class="form-control" id="exampleInputPassword1" placeholder="05-12-2024" style="margin-bottom: -7px;margin-top: -6px;">
                                                    <span>Thursday</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="from_flight px-3 py-2">
                                            <label for="exampleInputPassword1" class="form-label">Passenger, Class</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="0 Passenger" style="margin-bottom: -7px;margin-top: -6px;">
                                            <span>Business</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-6 d-flex align-items-end justify-content-center">
                                        <button class="btn btn-danger" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_1.png')}}" />
                            <div class="tour_1">
                                <p>Sunset Vibes Summer Gala on the East Coast Adventure</p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_2.png')}}" />
                            <div class="tour_2">
                                <p>Sunset Vibes Summer Gala on 
                                    the East Coast Adventure
                                </p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group_img">
                            <img src="{{asset('assets/images/Img_3.png')}}" />
                            <div class="tour_3">
                                <p>Sunset Vibes Summer Gala on 
                                    the East Coast Adventure
                                </p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="group_img4">
                            <img src="{{asset('assets/images/Img_4.png')}}" />
                            <div class="tour_1">
                                <p>Sunset Vibes Summer Gala on 
                                    the East Coast Adventure
                                </p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group_img5">
                            <img src="{{asset('assets/images/Img_5.png')}}" />
                            <div class="tour_5">
                                <p>Sunset Vibes Summer Gala on 
                                    the East Coast Adventure
                                </p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group_img6">
                            <img src="{{asset('assets/images/Img_6.png')}}" />
                            <div class="tour_2">
                                <p>Sunset Vibes Summer Gala on 
                                    the East Coast Adventure
                                </p>
                                <ul class="d-flex p-0 px-1">
                                    <li><img src="{{asset('assets/images/inclusion/airfarewhite.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/trekkingwhite-27045311.png')}}" alt="" class="px-1 w-auto"></li>
                                    <li><img src="{{asset('assets/images/inclusion/sailboatwhite-17434651.png')}}" alt="" class="px-1 w-auto"></li>
                                </ul>
                                <p style="margin-top: -20px;"><small class="text-decoration-line-through">₹ 1,80,014</small> <span>₹ 1,60,014</span></p>
                                <div class="flag">
                                    <img src="{{asset('assets/images/icons/Rectangleyellow19435.png')}}" class="w-auto">
                                    <p class="flag1">6 Nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="package_dest">
        <div class="container">
            <!-- GROUP TOURS -->
            <div class="row pt-5" style="margin-bottom: -100px;">
                <div class="col-md-6">
                    <div class="blog_section pb-4">
                        <h5>OUR GROUP TOURS</h5>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="blog_section pb-4">
                        <h6>View All <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="owl-carousel">
                        @foreach ($groupTours as $groupTour)
                            <div class="item">
                                <div class="card">
                                    <img src="@if (array_key_exists(1, $groupTour['DookImage'])) {{$groupTour['DookImage'][1]}} @else {{asset('assets/images/maine-Image.jpg')}} @endif" class="card-img-top" alt="{{$groupTour['Name']}}">
                                    <div class="best_selling">
                                        @if ($groupTour['BestSellingPackage'])
                                            <img src="{{asset('assets/images/icons/Rectangle19435.png')}}" class="w-auto">
                                            <p class="best_sell">BEST SELLING</p>
                                        @endif
                                    </div>
                                    <div class="card-body" style="margin-top: -25px;">
                                        <h6>{{$groupTour['Name']}}</h6>
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
                                                <ul class="hightlights">
                                                    @foreach (getFirstNonNullAttraction($groupTour['Itinerary']) as $attraction)
                                                        <li>{{$attraction['Name']}}</li>
                                                    @endforeach      
                                                </ul>
                                                <p >
                                                    {{-- <small class="text-decoration-line-through">₹ 1,80,014</small>  --}}
                                                    <span>₹{{formatIndianNumber($groupTour['Price'])}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
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
                <div class="col-md-3">
                    <div class="card" style="border-radius: 20px;">
                        <img src="{{asset('assets/images/image(11).png')}}" class="card-img-top" alt="...">
                        <div class="dook_special1 shadow">
                            <img src="{{asset('assets/images/icons/users.png')}}" class="w-auto" style="top: 13px;position: relative;">
                        </div>
                        <div class="card-body" style="margin-top: -30px;">
                            <h3 class="text-dark">2000+</h3>
                            <h6 class="text-dark">GROUP TOURS</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="border-radius: 20px;">
                        <img src="{{asset('assets/images/image(12).png')}}" class="card-img-top" alt="...">
                        <div class="dook_special1 shadow">
                            <img src="{{asset('assets/images/icons/globe.png')}}" class="w-auto" style="top: 13px;position: relative;">
                        </div>
                        <div class="card-body" style="margin-top: -30px;">
                            <h3 class="text-dark">500+</h3>
                            <h6 class="text-dark">Destinations</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="border-radius: 20px;">
                        <img src="{{asset('assets/images/image().png')}}" class="card-img-top" alt="...">
                        <div class="dook_special1 shadow">
                            <img src="{{asset('assets/images/icons/briefcase.png')}}" class="w-auto" style="top: 13px;position: relative;">
                        </div>
                        <div class="card-body" style="margin-top: -30px;">
                            <h3 class="text-dark">50+</h3>
                            <h6 class="text-dark">EXPERIENCES</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="border-radius: 20px;">
                        <img src="{{asset('assets/images/image(14).png')}}" class="card-img-top" alt="...">
                        <div class="dook_special1 shadow">
                            <img src="{{asset('assets/images/icons/Group 48098666.png')}}" class="w-auto" style="top: 13px;position: relative;">
                        </div>
                        <div class="card-body" style="margin-top: -30px;">
                            <h3 class="text-dark">45K+</h3>
                            <h6 class="text-dark">HAPPY CUSTOMER</h6>
                        </div>
                    </div>
                </div>
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
                                    <img src="{{env('AWS_BUCKET_URL').'/package/'.$departure->image}}" class="card-img-top" alt="...">
                                    {{-- <div class="best_selling">
                                        <img src="./images/icons/Rectangle 19435.png" class="w-auto">
                                        <p class="best_sell">BEST SELLING</p>
                                    </div> --}}
                                    {{-- @dd($departure) --}}
                                    <div class="card-body" style="margin-top: -45px;">
                                        <h6>{{$departure->title}}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>{{$departure->no_of_nights}} Nights</p>
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
                                                <ul class="hightlights">
                                                    <li>Munnar Beach</li>
                                                    <li>Varkala Beach</li>
                                                    <li>Periyar National Park</li>
                                                    <li>Lighthouse Beach</li>
                                                </ul>
                                                @if ($departure->price !='' || !is_null($departure->price))
                                                    <p>
                                                        {{-- <small class="text-decoration-line-through">₹ 1,80,014</small>  --}}
                                                        <span>₹ {{formatIndianNumber($departure->price)}}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
                        <h6>All Destination <img src="{{asset('assets/images/arrow.png')}}"></h6>
                    </div>
                </div>
                <div class="row">
                    @foreach ($topDestinations as $topDestination)
                        <div class="col-md-2 text-center">
                            <img src="{{env('AWS_BUCKET_URL').'/poi/'.$topDestination->destination->image}}">
                            <p class="an_dest">{{$topDestination->departureDestination->count()}} TOURS</p>
                            <h6 class="mt-3">{{$topDestination->destination->actualname}}</h6>
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
                            <div class="exp_img1" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image1}}')">
                                <div class="honeymoon">
                                    <h4>{{$homeSettings->experinceOne->experience_name}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="exp_img2" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image2}}')">

                                <div class="family">
                                    <h4>{{$homeSettings->experinceTwo->experience_name}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="exp_img3" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image3}}')">
                                <div class="shopping">
                                    <h4>{{$homeSettings->experinceThree->experience_name}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="exp_img4" style="background: url('{{env('AWS_BUCKET_URL').'/home/'.$homeSettings->exp_image4}}')">
                                <div class="nightlife">
                                    <h4>{{$homeSettings->experinceFour->experience_name}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="exp_img5">  
                        <div class="turkey">
                            <h4>Exotic Turkey</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial -->
    <section class="testimonial">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 testi">
                    <p>Testimonials</p>
                    <h4>Travelers love our locals</h4>
                    <div><span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                    </div>
                    <p class="pt-3 testi_review">"Our tour with Dook Internation was just fantastic. We learnt so much about Vietnam history, culture and food, and of course tasted some amazing dishes along the way...”</p>
                    <h4>Sumit Sharma</h4>
                    <p>Customer</p>
                </div>
                <div class="col-md-6">
                    <img src="{{asset('assets/images/Group48098714.png')}}">
                </div>
            </div>
        </div>
    </section>
    <!-- blog section -->
    <section class="bg-white">
        <div class="container">
            <div class="row mb-4 mt-4">
                <div class="col-md-12 text-center">
                    <h4>{{$homeSettings->text_heading}}</h4>
                    <p class="color_gray pb-3">{{$homeSettings->text_sub_heading}}</p>
                </div>
                <div class="col-md-12">
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
                            {{-- @dd($latestPost) --}}
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
                                        <p class="card-text">{{$latestPost['short_description']}}</p>
                                        <a href="javascript:void(0)" class="btn btn-danger">Read More</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
            </div>
        </div>
    </section>
    <!-- Quick Link Section -->
    <div class="container" style="margin-bottom: 110px !important;">
        <div class="row">
            <div class="col-md-12 footer_links">
                <h6 class="mt-4">Quick Links</h6>
                <p>Delhi Chennai Flights, Delhi Mumbai Flights, Delhi Goa Flights, Chennai Mumbai flights, Mumbai Hyderabad flights, Kolkata to Rupsi Flights, Rupsi to Guwahati Flights, Pasighat to Guwahati Flights, Delhi to Khajuraho Flights, Cochin to Agatti Island Flights, Hotels in Delhi, Hotels in Mumbai, Hotels In Goa, Hotels In Jaipur, Hotels In Ooty, Hotels In Udaipur, Hotels in Puri, Hotels In North Goa, Hotels In Rishikesh, Honeymoon Packages, Kerala Packages, Kashmir Packages, Ladakh Packages, Goa Packages, Thailand Packages, Sri Lanka Visa, Thailand Visa, Explore Goa, Explore Manali, Explore Shimla, Explore Jaipur, Explore Srinagar
                </p>
                <h6 class="mt-4">About the Site</h6>
                <p>Customer Support, Payment Security, Privacy Policy, Cookie Policy, User Agreement, Terms of Service, Franchise Offices, Make A Payment, Work From Home, Escalation Channel, Report Security Issues</p>
                <h6 class="mt-4">Important Links</h6>
                <p>Cheap Flights, Flight Status, Kumbh Mela, Domestic Airlines, International Airlines, Indigo, Spicejet, GoAir, Air Asia, Air India, Indian Railways, Trip Ideas, Beaches, Honeymoon Destinations, Romantic Destinations, Popular Destinations, Resorts In Udaipur, Resorts In Munnar, Villas In Lonavala, Hotels in Thailand, Villas In Goa, Domestic Flight Offers, International Flight Offers, UAE Flight Offers, USA, UAE, Saudi Arabia, UK, Oman</p>
            </div>
        </div>
    </div>
    
    <!-- Option 1: Bootstrap Bundle with Popper -->
@endsection
