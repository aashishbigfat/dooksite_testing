@extends('frontend.layouts.master')
@push('title') {{$departure->title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$departure->meta_keywords}}">
<meta name="description" content="{{$departure->meta_description}}">
<meta property="og:description" content="{{$departure->meta_description}}">
<meta name="twitter:description" content="{{$departure->meta_description}}">
@endpush
@section('content')
<style>
    .btn-info1 {
        color: #000;
        background-color: #e9ecef !important;
        border-color: #aaa !important;
    }
</style>
<!-- home section -->
<section class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
            <div class="breadcrumb-item">
                <a href="/"><i class="fas fa-home"></i>Home</a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item">
                <a href="{{route('frontend.group-tours')}}">Group Tours</a>
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

            <div><span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span> <span class="color_gray">(1 Review)</span>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <div class="row mt-4">

        <div class="secondheader shadow-sm p-3 mb-5 bg-white rounded py-4">
            <ul class="nav nav-tabs product_detail d-flex" style="border-bottom:none">
                <li class="active"><a href="#Details">Details</a></li>

                <li><a href="#Itinerary">Itinerary</a></li>
                @if(!empty($departure->inclusions) && count($departure->inclusions) > 0)
                <li><a href="#Inclusion">Inclusions</a></li>
                @endif


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
                <form id="bookingForm" method="POST" action="{{route('frontend.book_now_submit') }}">
                    @csrf
                    <input type="hidden" name="destination_title" value="{{$departure->title}}">
                    <input type="hidden" name="no_of_nights" value="{{$departure->no_of_nights}}">
                    {{--<input type="hidden" name="no_of_days" value="{{$departure->no_of_days}}">--}}
                    <input type="hidden" name="departure_date" id="departure_date">
                    <input type="hidden" name="departure_dateid" id="idInput">
                    <input type="hidden" name="pkgid" value="{{$departure->dep_dook_ref_id}}">
                    <input type="hidden" id="priceInput" name="price">
                    <input type="hidden" id="priceIdInput" name="priceId">
                    <input type="hidden" id="singlePriceInput" name="singleprice">
                    <input type="hidden" id="singlePriceIdInput" name="singlePriceIdInput">
                    <div id="singlePriceDisplay" style="display:none"></div>
                    <div id="singlePriceIdDisplay" style="display:none"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- <div class="row"> -->
                            <!--  <div class="col-md-8">
                                                   
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
                                            <p style="margin-top: -18px; color: green;" id="departurePriceUpdate"></p>
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
                                            <p style="margin-top: -18px;">{{ $departure->no_of_nights }}</p>
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
                                {{-- <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/map-pin2.png') }}"
                                                alt="Destination">
                                        </div>
                                        <div class="col-md-9">
                                            <p>Destinations</p>
                                            <p style="margin-top: -18px;">{{ $departure->destinationTotal }}</p>
                                        </div>
                                    </div>
                                </div> --}}
                                @if (!empty($departure->Destination))
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/map-pin2.png') }}"
                                                alt="Destination">
                                        </div>
                                        <div
                                            class="col-md-9 trip-introduction-countries position-relative listAttraction">
                                            <p>Destinations</p>
                                            <p style="margin-top: -18px;">{{ $departure->destinationTotal }}</p>
                                            <div class="dropDestinHover">
                                                @if (!empty($departure->Destination))
                                                @foreach ($departure->Destination as $destination)
                                                @if (!empty($destination['slug_url']))
                                                <a href="{{ url('destinations/' . $destination['slug_url']) }}"
                                                    class="destination-name" target="_blank">
                                                    {{ $destination['dest_name'] }}
                                                </a>
                                                @else
                                                <a href="#" class="destination-name" target="_blank">
                                                    {{ $destination['dest_name'] }}
                                                </a>
                                                @endif
                                                <br>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if (!empty($departure->poi))
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags"><img src="{{ asset('assets/images/icons/map.png') }}"
                                                alt="Attractions"></div>
                                        <div
                                            class="col-md-9 trip-introduction-countries position-relative listAttraction">
                                            <p>Attractions</p>
                                            <p style="margin-top: -18px;">{{ count($departure->poi) }}</p>
                                            <div class="dropDestinHover">
                                                @foreach ($departure->poi as $pois)
                                                <a href="#" class="destination-name">

                                                    {{ $pois['poi_name'] }}

                                                </a>
                                                <br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                {{--<div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/map.png') }}" alt="Attractions">
                                        </div>
                                        <div class="col-md-9">
                                            <p>Attractions</p>
                                            <p style="margin-top: -18px;">{{ $departure->poiTotal }}</p>
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/map-pin(1).png') }}"
                                                alt="From Date">
                                        </div>
                                        <div class="col-md-9">
                                            <p>From</p>
                                            <p style="margin-top: -18px;">{{$departure->from}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/map-pin(1).png') }}"
                                                alt="Ending Date">
                                        </div>
                                        <div class="col-md-9">
                                            <p>Ending at</p>
                                            <p style="margin-top: -18px;">{{$departure->ending_at}}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($departure->dep_type == "main")
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/calendar.png') }}" alt="Date">
                                        </div>
                                        <div class="col-md-9">
                                            <p>Date</p>
                                            <select id="departureDates" class="form-select" style="margin-top: -10px;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6 col-6" id="numberOfTravellersContainer">
                                    <div class="row">
                                        <div class="col-md-2 tags">
                                            <img src="{{ asset('assets/images/icons/users1.png') }}"
                                                alt="No of Travelers">
                                        </div>
                                        <div class="col-md-9">
                                            <p>No. of Travelers</p>
                                            <!-- <p style="margin-top: -18px;">01</p> -->
                                            <div class="main d-flex align-items-center">
                                                <button class="down_count btn btn-info px-2 py-0" title="Decrease"
                                                    type="button">-</button>
                                                <input
                                                    class="counter1 form-control text-center mx-2 px-2 py-0 color_gray"
                                                    type="text" value="2" name="pax" id="pax" readonly
                                                    style="background: #fff;">
                                                <button class="up_count btn btn-info px-2 py-0" title="Increase"
                                                    type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6 d-flex p-0 mt-4 d-none d-lg-block">

                                    <button type="button"
                                        class="btn btn-danger w-100 p-2 fs-12 mx-1 d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" onclick="openEnquiryModal()">Enquire Now</button>

                                    {{-- <button type="submit"
                                        class="btn btn-danger fill w-100 h-50 fs-12 d-flex align-items-center justify-content-center"
                                        id="addTraveler">Book Now</button> --}}

                                </div>
                            </div>

                        </div>
                        @if(count($departure->gallery)>0)
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12 mt-4 col-6">
                                    <img src="{{ isset($departure->gallery[0]) && !empty($departure->gallery[0]) ? env('Image_Urls') . $departure->gallery[0] : url('images/no_image.jpg') }}"
                                        alt="image1" class="w-100 pack_img">
                                </div>
                                <div class="col-md-12 mt-4 col-6">
                                    <img src="{{ isset($departure->gallery[1]) && !empty($departure->gallery[1]) ? $departure->gallery[1] : url('images/no_image.jpg') }}"
                                        alt="image2" class="w-100 pack_img">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12 mt-4 col-6">
                                    <img src="{{ isset($departure->gallery[2]) && !empty($departure->gallery[2]) ?  $departure->gallery[2] : url('images/no_image.jpg') }}"
                                        alt="image3" class="w-100 pack_img">
                                </div>
                                <div class="col-md-12 mt-4 col-6">
                                    <img src="{{ isset($departure->gallery[3]) && !empty($departure->gallery[3]) ? $departure->gallery[3] : url('images/no_image.jpg') }}"
                                        alt="image4" class="w-100 pack_img">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
                <h6 class="mt-4">Details</h6>
                <p class="color_gray fs-6">{!! $departure->description !!}</p>

                <hr>

            </div>
            <div id="Inclusion" class="tab-pane fade mt-4 pt-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Price Includes</h6>
                        <ul class="inclusion mt-4" id="inclusionsList">
                            @if(!empty($departure->inclusions))
                            @foreach ($departure->inclusions as $inclusion)
                            <li>{{ $inclusion }}</li>
                            @endforeach
                            @endif

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
            </div>
            <hr>
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
                            <div class="accordion-body m-0">
                                <ul class="color_gray fs-6 p-0">
                                    {!! ($day->description) !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <hr>
            <!--   <div id="Inclusion" class="tab-pane fade mt-4 pt-4">
                <h5>Includes</h5>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <ul class="inclusion mt-4" id="inclusionsList">
                           @if(!empty($departure->inclusions))
                        @foreach ($departure->inclusions as $inclusion)
                            <li>{{ $inclusion }}</li>
                        @endforeach
                         <hr>
                     @endif

                        </ul>
                    </div>
                </div>
            </div>    -->

            <div id="Attractions" class="tab-pane fade">
                <h5>Top Attractions</h5>
                <div class="row mb-4">
                    @foreach($departure->poi as $pointOfInterest)

                    <div class="col-md-3 col-6 mb-2">
                        <div class="card attraction_card">
                            <img src="{{ $pointOfInterest['image'] ?? url('images/poi-no-image.jpg') }}"
                                alt="{{ $pointOfInterest['poi_name'] }}" />
                            <div class="attraction_card_body">
                                <h6 class="m-0 p-0">{{ $pointOfInterest['poi_name'] }}</h6>
                                <p class="p-0 m-0 text-white">Duration: {{ $pointOfInterest['duration'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>
            </div>
            <hr>
            @if(count($departure->visa)>0)
            <div id="Visa" class="tab-pane fade mb-3">
                <h5>Visa</h5>
                @foreach($departure->visa as $visa)
                <p>{{$visa['phCountry']}} to {{$visa['visiting_country']}} Visa Informations
                    <a href="{{url('/')}}/{{$visa['url']}}" target="_blank" class="text-danger">
                        Click Here
                    </a>
                </p>
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
                                {!! $departure->conditions->conditions !!}

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
<style>
    .btn-info {
        color: #000;
        background-color: #e9ecef !important;
        border-color: #aaa !important;
    }

    .form-select {
        height: 26px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 0;
        font-size: 13px;
        color: gray;
        border-radius: 10px;
        padding-bottom: 0;
    }

    .btn-info {
        color: gray;
        border: 1px solid #ced4da !important;
        background: transparent !important;
    }
</style>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
    let selectBox = document.getElementById("departureDates");
    let inclusionsList = document.getElementById("inclusionsList");
    let updatedPriceDisplay = document.getElementById("departurePriceUpdate");

    // Get the container for the number of travellers field
    let numberOfTravellersContainer = document.getElementById("numberOfTravellersContainer");

    var tourData = @json($departure); 

    tourData.departure_price_seats.forEach(function(seat) {
        let option = document.createElement("option");
        option.value = seat.DepartureId;
        option.innerText = `${seat.DepartureDate}`;
        option.setAttribute("data-single-price", seat.FareInfo.find(info => info.RoomShare === "Single")?.OfferedPrice);
        option.setAttribute("data-double-price", seat.FareInfo.find(info => info.RoomShare === "Double")?.OfferedPrice);
        option.setAttribute("data-inclusions", JSON.stringify(seat.Inclusion));
        option.setAttribute("data-departure-date", seat.DepartureDate);
        option.setAttribute("data-available-seats", seat.AvailableSeats);  // Add available seats data
        selectBox.appendChild(option);
    });

    function updateInclusions() {
        let selectedOption = selectBox.options[selectBox.selectedIndex];

        // Get available seats from the selected option
        let availableSeats = parseInt(selectedOption.getAttribute("data-available-seats")) || 0;

        // Show or hide the number of travellers field based on available seats
        if (availableSeats > 0) {
            numberOfTravellersContainer.style.display = "block"; // Show the field
        } else {
            numberOfTravellersContainer.style.display = "none";  // Hide the field
        }

        // Find the FareInfo object for the Double room based on the selected DepartureId
        let fareInfoForDoubleRoom = tourData.departure_price_seats
            .find(seat => seat.DepartureId == selectedOption.value)
            .FareInfo.find(info => info.RoomShare === "Double");

        // Find the FareInfo object for the Single room based on the selected DepartureId
        let fareInfoForSingleRoom = tourData.departure_price_seats
            .find(seat => seat.DepartureId == selectedOption.value)
            .FareInfo.find(info => info.RoomShare === "Single");

        // Get the single and double prices
        let singlePrice = parseFloat(selectedOption.getAttribute("data-single-price")) || 0;
        let doublePrice = parseFloat(selectedOption.getAttribute("data-double-price")) || 0;

        // Display both single and double prices with a 10% profit
        let profitSinglePrice = singlePrice * 1.1;
        let profitDoublePrice = doublePrice * 1.1;

        updatedPriceDisplay.innerHTML = 
            `            
             <span style="color: green;">₹${doublePrice.toFixed(2)} </span><span class="color_gray"><s>₹${profitDoublePrice.toFixed(2)} </s></span>`;

        // Get inclusions
        let inclusions = JSON.parse(selectedOption.getAttribute("data-inclusions") || "[]");

        // Clear previous inclusions
        inclusionsList.innerHTML = "";

        // Display inclusions
        if (inclusions.length > 0) {
            inclusions.forEach(function(inclusion) {
                let li = document.createElement("li");
                li.textContent = inclusion;
                inclusionsList.appendChild(li);
            });
        } else {
            let li = document.createElement("li");
            li.textContent = "Inclusions not available for selected date.";
            li.style.color = "red";
            inclusionsList.appendChild(li);
        }

        // Update hidden input fields with the single and double room prices and departure date ID
        document.getElementById("priceInput").value = doublePrice;
        document.getElementById("singlePriceInput").value = singlePrice;

        // Set the departure date and DepartureId in the hidden fields
        document.getElementById("departure_date").value = selectedOption.getAttribute("data-departure-date"); // Set departure date
        document.getElementById("idInput").value = selectedOption.value; // Set DepartureId

        // Set the FareInfo "Double" Room ID in the hidden field for priceIdInput
        if (fareInfoForDoubleRoom) {
            document.getElementById("priceIdInput").value = fareInfoForDoubleRoom.Id; // Set the ID of the Double room
        }

        // Set the FareInfo "Single" Room ID in the hidden field for singlePriceIdInput
        if (fareInfoForSingleRoom) {
            document.getElementById("singlePriceIdInput").value = fareInfoForSingleRoom.Id; // Set the ID of the Single room
        }

        // Show the Single Room Price and ID in the display divs
        if (fareInfoForSingleRoom) {
            // Show the single room price and ID in their respective divs
            document.getElementById("singlePriceDisplay").style.display = "none";
            document.getElementById("singlePriceDisplay").innerHTML = `Single Room Price: ₹${singlePrice.toFixed(2)}`;
            
            document.getElementById("singlePriceIdDisplay").style.display = "none";
            document.getElementById("singlePriceIdDisplay").innerHTML = `Single Room ID: ${fareInfoForSingleRoom.Id}`;
        } else {
            // Hide the single room price and ID if not available
            document.getElementById("singlePriceDisplay").style.display = "none";
            document.getElementById("singlePriceIdDisplay").style.display = "none";
        }
    }

    // Initialize with the first selection
    updateInclusions();

    // Add event listener to update inclusions and price when a user changes the selection
    selectBox.addEventListener("change", updateInclusions);
});


</script>
<script>
    // Add this script to capture the selected departure date
    document.getElementById('bookingForm').addEventListener('submit', function () {
        // var selectedDate = document.getElementById('gtdates').value;
        document.getElementById('departure_date').value = selectedDate;
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let paxInput = document.getElementById("pax");
        let increaseBtn = document.querySelector(".up_count");
        let decreaseBtn = document.querySelector(".down_count");

        let minTravelers = 1;
        let maxTravelers = 10; // Change this limit as needed

        increaseBtn.addEventListener("click", function() {
            let currentValue = parseInt(paxInput.value);
            if (currentValue < maxTravelers) {
                paxInput.value = currentValue + 1;
            }
        });

        decreaseBtn.addEventListener("click", function() {
            let currentValue = parseInt(paxInput.value);
            if (currentValue > minTravelers) {
                paxInput.value = currentValue - 1;
            }
        });
    });
</script>
@endsection