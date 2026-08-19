@extends('frontend.layouts.master')
@push('title') {{$homeSettings->meta_title}}@endpush
@push('meta_tag')
<meta name="keywords" content="{{$homeSettings->meta_keywords}}">
<meta name="description" content="{{$homeSettings->meta_description}}">
<meta property="og:description" content="{{$homeSettings->meta_description}}">
<meta name="twitter:description" content="{{$homeSettings->meta_description}}">
<meta name="google-site-verification" content="Cavclw-EjZaddjTTbiv0FIynJj_fqUydbBC5noH3NhY" />
@endpush
@section('content')
<style type="text/css">
  .nav-tabs {
    border-bottom: none !important;
  }

  .rounded-20 {
    border-radius:
      12px;
  }
</style>

<div class="main-container" >
  <!-- Hero Section -->
  <section class="hero-section mt-3">
    <div class=""
      style="max-width: 1400px;margin: 0 auto;display: flex;align-items: center;justify-content: space-between;gap: 30px;">
      <!-- LEFT GALLERY -->
      <div class="d-none d-lg-block order-1">
        <div class="gallery-side gallery-left ">
          <!-- Row 1: 3 Large Images -->
          <div class="gallery-row gallery-row-large">
            <div class="gallery-img-large">
              <img src="{{asset('assets/images/header/1.png')}}" alt="Beach" />
            </div>
            <div class="gallery-img-small">
              <img src="{{asset('assets/images/header/8.png')}}" alt="Monument" />
            </div>
            <div class="gallery-img-medium">
              <img src="{{asset('assets/images/header/12.png')}}" alt="City" />
            </div>
          </div>

          <!-- Row 2: 4 Medium Images -->
          <div class="gallery-row gallery-row-medium align-items-start">
            <div class="gallery-img-large">
              <img src="{{asset('assets/images/header/2.png')}}" alt="Building" />
            </div>
            <div class="gallery-img-small">
              <img src="{{asset('assets/images/header/7.png')}}" alt="Palace" />
            </div>
            <div class="gallery-img-medium">
              <img src="{{asset('assets/images/header/11.png')}}" alt="Architecture" />
            </div>
            <div class="gallery-img-smallest">
              <img src="{{asset('assets/images/header/13.png')}}" alt="Sunset" />
            </div>
          </div>
        </div>
      </div>
      <div class="flight-animation">
        <h1 class="hero-title">India's #1 <span class="text-gradient">CIS & Central Asia</span> Travel Specialist</h1>
       <!--  <h2 >
          Travel.<span class="text-gradient"> Relax.</span> Discover.
        </h2> -->
        <p class="hero-subtitle">
          Enjoy seamless travel experiences with our exclusive CIS Countries Tour Packages from India.
        </p>
        <div class="d-flex gap-2 flex-wrap mb-3 justify-content-center">
          <a href="{{url('countries')}}" class="btn-explore">
            Explore Tours <i class="fas fa-arrow-right"></i>
          </a>
          <a href="{{url('contact-us')}}" class="btn-contact">
            <i class="fas fa-phone"></i> Contact Us
          </a>
        </div>
        <div class="stats-row justify-content-center">
          <div class="stat-item">
            <div class="stat-number">13+</div>
            <div class="stat-label">Years Experience</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">1M+</div>
            <div class="stat-label">Happy Travelers</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label">Global Partners</div>
          </div>
        </div>
      </div>
      <!-- RIGHT GALLERY -->
      <div class="d-none d-lg-block order-3">
        <div class="gallery-side gallery-right ">
          <!-- Row 1: 4 Large Images -->
          <div class="gallery-row gallery-row-medium justify-content-end">
            <div class="gallery-img-smallest">
              <img src="{{asset('assets/images/header/3.png')}}" alt="City View" />
            </div>
            <div class="gallery-img-medium">
              <img src="{{asset('assets/images/header/10.png')}}" alt="Temple" />
            </div>
            <div class="gallery-img-small">
              <img src="{{asset('assets/images/header/6.png')}}" alt="Landscape" />
            </div>
            <div class="gallery-img-large">
              <img src="{{asset('assets/images/header/14.png')}}" alt="Mountain" />
            </div>
          </div>

          <!-- Row 2: 3 Large Images -->
          <div class="gallery-row gallery-row-medium align-items-start justify-content-end">
            <div class="gallery-img-medium">
              <img src="{{asset('assets/images/header/9.png')}}" alt="Water" />
            </div>
            <div class="gallery-img-small">
              <img src="{{asset('assets/images/header/5.png')}}" alt="Sky" />
            </div>
            <div class="gallery-img-large">
              <img src="{{asset('assets/images/header/4.png')}}" alt="Beach2" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Tab Buttons -->
  <div class="tabs-container">
    <div class="tab-buttons">
      <button class="tab-btn active" data-tab="tours">
        <div class="tab-icon-wrapper"><i class="fas fa-globe"></i></div>
        <span>Tours</span>
      </button>
      <button class="tab-btn d-none" data-tab="flights">
        <div class="tab-icon-wrapper"><i class="fas fa-plane"></i></div>
        <span>Flights</span>
      </button>
      <button class="tab-btn" data-tab="hotels">
        <div class="tab-icon-wrapper"><i class="fas fa-hotel"></i></div>
        <span>Hotels</span>
      </button>
    </div>
  </div>
  <?php
      $searchDestinations = DB::table('default_search_destinations')
      ->orderBy('orders')->select('name')->get();
      $flags = [
          'Almaty'    => '🇰🇿', // Kazakhstan
          'Tashkent'  => '🇺🇿', // Uzbekistan
          'Bishkek'   => '🇰🇬', // Kyrgyzstan
          'Moscow'    => '🇷🇺', // Russia
          'Belarus'   => '🇧🇾',
          'Armenia'   => '🇦🇲',
          'Georgia'   => '🇬🇪',
          'Turkey'    => '🇹🇷',
          'Russia'    => '🇷🇺',
          'Serbia'    => '🇷🇸',
          'Finland'   => '🇫🇮',
          'Baku'      => '🇦🇿', // Azerbaijan
      ];

      ?>
  <!-- Booking Section -->
  <section class="booking-section">
    <div class="booking-form-wrapper">
      <!-- Tours Form -->
      <div class="form-content active" id="tours">
        <div class="tours-search-wrapper">
          <form id="commonSearchForm" action="{{url('search')}}?searchKeyword=">
            <div class="tours-search-box">
              <div class="search-icon-wrapper-tours">
                <i class="fas fa-search"></i>
              </div>

              @if(isset($keyword))
              <input id="searchKeyword" value="" type="text" class="tours-search-input"
                placeholder="Search destinations, attractions, or activities..." name="searchKeyword" required />
              @else
              <input id="searchKeyword" type="text" class="tours-search-input"
                placeholder="Search destinations, attractions, or activities..." name="searchKeyword" required />
              @endif
              <button class="btn-search-tours" type="submit" aria-label="Search Tours">
                <i class="fas fa-arrow-right"></i>
              </button>

            </div>
          </form>
          <div class="tours-quick-links">
            <span class="quick-link-label">Popular:</span>
            @foreach($searchDestinations as $searchDestination)
            @php
            $flag = $flags[$searchDestination->name] ?? '🏳️';
            @endphp
            <a href="{{url('search')}}?searchKeyword={{$searchDestination->name}}" class="quick-link-btn">{{ $flag }}
              {{$searchDestination->name}}</a>
            @endforeach
          </div>
        </div>
      </div>
      <!-- Flights Form -->
      <div class="form-content" id="flights">
        <!-- Trip Type Selector -->
        <div class="trip-type">
          <input type="radio" name="tripType" id="oneway" value="oneway" checked />
          <label for="oneway">Oneway</label>

          <input type="radio" name="tripType" id="roundtrip" value="roundtrip" />
          <label for="roundtrip">Round Trip</label>

          <input type="radio" name="tripType" id="multicity" value="multicity" />
          <label for="multicity">Multi City</label>
        </div>

        <!-- Oneway/Roundtrip Form -->
        <div id="singleTripForm">
          <div class="form-grid">
            <div class="form-group" style="position: relative">
              <label class="form-label">From</label>
              <input type="text" class="form-input airport-code" value="Delhi" readonly />
              <div class="form-input airport-detail">
                [DEL] Indira Gandhi International
              </div>
              <button class="swap-btn">
                <i class="fas fa-exchange-alt"></i>
              </button>
            </div>

            <div class="form-group">
              <label class="form-label">To</label>
              <input type="text" class="form-input airport-code" value="Mumbai" readonly />
              <div class="form-input airport-detail">
                [BOM] Chhatrapati Shivaji
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Depart</label>
              <input type="text" class="form-input" value="22 Oct 25" readonly style="margin-bottom: 4px" />
              <div style="font-size: 0.6875rem; color: #000; font-weight: 600">
                Wednesday
              </div>
            </div>

            <div class="form-group return-field">
              <label class="form-label">Return</label>
              <input type="text" class="form-input return-input" placeholder="Return Date" readonly
                style="margin-bottom: 4px" />
              <div class="return-note" style="font-size: 0.6875rem; color: #000; font-weight: 600">
                Book a round trip to save more
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Travellers & Class</label>
              <input type="text" class="form-input" value="1 Passenger" readonly style="margin-bottom: 4px" />
              <div style="font-size: 0.6875rem; color: #000; font-weight: 600">
                Any
              </div>
            </div>

            <button class="btn-search">
              <i class="fas fa-search"></i> Search
            </button>
          </div>

          <!-- Fare Type & Carriers -->
          <div class="fare-carriers-row">
            <div class="fare-section">
              <div class="section-title">Select A Fare Type</div>
              <div class="checkbox-group">
                <div class="checkbox-item">
                  <input type="checkbox" id="nonstop" />
                  <label for="nonstop">Non-Stop</label>
                </div>
              </div>
            </div>

            <div class="carriers-section">
              <div class="section-title">Preferred Carriers</div>
              <div class="checkbox-group">
                <div class="checkbox-item">
                  <input type="checkbox" id="indigo" />
                  <label for="indigo">Indigo</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="vistara" />
                  <label for="vistara">Vistara</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="airindia" />
                  <label for="airindia">Air India</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="spicejet" />
                  <label for="spicejet">SpiceJet</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="airasia" />
                  <label for="airasia">Air Asia</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Multi City Form -->
        <div id="multiCityForm" style="display: none">
          <div class="flight-segment">
            <div class="form-grid" style="grid-template-columns: 2fr 2fr 1.5fr 1.5fr">
              <div class="form-group">
                <label class="form-label">From</label>
                <input type="text" class="form-input airport-code" value="Delhi" readonly />
                <div class="form-input airport-detail">
                  [DEL] Indira Gandhi International
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">To</label>
                <input type="text" class="form-input airport-code" value="Mumbai" readonly />
                <div class="form-input airport-detail">
                  [BOM] Chhatrapati Shivaji
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Depart</label>
                <input type="text" class="form-input" value="22 Oct 25" readonly style="margin-bottom: 4px" />
                <div style="font-size: 0.6875rem; color: #000; font-weight: 600">
                  Wednesday
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Travellers & Class</label>
                <input type="text" class="form-input" value="1 Passenger" readonly style="margin-bottom: 4px" />
                <div style="font-size: 0.6875rem; color: #000; font-weight: 600">
                  Any
                </div>
              </div>
            </div>
          </div>

          <div class="flight-segment">
            <div class="form-grid" style="grid-template-columns: 2fr 2fr 1.5fr 1.5fr">
              <div class="form-group">
                <label class="form-label">From</label>
                <input type="text" class="form-input airport-code" value="Mumbai" readonly />
                <div class="form-input airport-detail">
                  [BOM] Chhatrapati Shivaji
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">To</label>
                <input type="text" class="form-input" placeholder="Destination" readonly />
                <div class="form-input airport-detail">
                  Select destination
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Depart</label>
                <input type="text" class="form-input" placeholder="Depart Date" readonly />
              </div>

              <div class="form-group">
                <label class="form-label">Travellers & Class</label>
                <input type="text" class="form-input" value="1 Passenger" readonly style="margin-bottom: 4px" />
                <div style="font-size: 0.6875rem; color: #000; font-weight: 600">
                  Any
                </div>
              </div>
            </div>
          </div>

          <button class="add-flight-btn">
            <i class="fas fa-plus"></i> Add Flight
          </button>

          <button class="btn-search" style="float: right">
            <i class="fas fa-search"></i> Search
          </button>
          <div style="clear: both"></div>

          <!-- Fare Type & Carriers -->
          <div class="fare-carriers-row">
            <div class="fare-section">
              <div class="section-title">Select A Fare Type</div>
              <div class="checkbox-group">
                <div class="checkbox-item">
                  <input type="checkbox" id="nonstop-multi" />
                  <label for="nonstop-multi">Non-Stop</label>
                </div>
              </div>
            </div>

            <div class="carriers-section">
              <div class="section-title">Preferred Carriers</div>
              <div class="checkbox-group">
                <div class="checkbox-item">
                  <input type="checkbox" id="indigo-multi" />
                  <label for="indigo-multi">Indigo</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="vistara-multi" />
                  <label for="vistara-multi">Vistara</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="airindia-multi" />
                  <label for="airindia-multi">Air India</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="spicejet-multi" />
                  <label for="spicejet-multi">SpiceJet</label>
                </div>
                <div class="checkbox-item">
                  <input type="checkbox" id="airasia-multi" />
                  <label for="airasia-multi">Air Asia</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hotels Form -->
      <div class="form-content" id="hotels">
        <form action="/book/hotel/hotel-result" class="tts__form_wrapper" name="hotelform" type="get">
          <div class="form-grid hotels">
            <div class="form-group full-width">
              <div class="form-group">
                <label class="form-label text-white">Enter your Destination or Property</label>
                <div class="form-group-icon">
                  <input type="text" class="form-control tts__input__input" placeholder="CITY" value="Goa, India"
                    data-validation="required" name="location" data-validation-error-msg="Please select city"
                    tts-hotel-location="true">
                  <input type="hidden" name="cityDom" cityDom="true" value="Goa_119805_IN" data-validation="required">
                  <input type="hidden" name="room" hotel-total-selected-rooms="true" value="1"
                    data-validation="required">
                </div>
                <div class="flight_text_p"></div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label text-white">Check-In</label>
              <div class="form-group-icon">
                <input type="text" class="form-control tts__input__input" placeholder="Check-In"
                  value="<?php $Date = date(" Y/m/d");echo date('d M y', strtotime($Date . ' + 1 days' )); ?>"
                data-validation="required"
                data-validation-error-msg="Please select check in date"
                hotel-check-in-date="true" name="checkIn">
              </div>
              <!-- <div class="flight_text_p">
                               {{--   <?php echo date('l', strtotime($Date . ' + 1 days')); ?> --}}
                              </div> -->
            </div>
            <div class="form-group">
              <label class="form-label text-white">Check-Out</label>
              <input type="text" class="form-control tts__input__input" placeholder="Check-Out"
                data-validation="required" data-validation-error-msg="Please select check out date"
                value="<?php $Date = date(" Y/m/d"); echo date('d M y', strtotime($Date . ' + 2 days' )); ?>"
              hotel-check-out-date="true" name="checkOut">
              <!--  <div class="flight_text_p">
                                 {{-- <?php echo date('l', strtotime($Date . ' + 2 days')); ?> --}}
                              </div> -->
            </div>
            <div class="form-group">
              <label class="form-label text-white">Rooms & Guests</label>
              <div class="passenger-class form-input" id="select_hotel_pax" data-bs-toggle="dropdown"
                aria-expanded="false" data-bs-auto-close="outside">
                <div class="form-group-icon">
                  <div class="passenger-total">
                    <span class="passenger-total-amount" tts-hotel-guest-info="true">2</span> Guest,
                    <span class="passenger-total-amount" tts-hotel-rooms-info="true">1</span> Rooms
                  </div>
                </div>
                <div class="flight_text_p"></div>
              </div>
              <div class="dropdown-menu" aria-labelledby="select_hotel_pax" hotel-room-dropdown="true"
                style="width:300px">
                <div class="dropdown-item">
                  <div class="passenger-item">
                    <div class="passenger-info">
                      <h6>Star Rating</h6>
                    </div>
                    <div>
                      <select class="small" name="rating">
                        <option value="0">Show All</option>
                        <option value="1">1 Star or less</option>
                        <option value="2">2 Star or less</option>
                        <option value="3">3 Star or less</option>
                        <option value="4">4 Star or less</option>
                        <option value="5">5 Star or less</option>
                        <option value="6">1 Star or More</option>
                        <option value="7" selected>2 Star or More
                        </option>
                        <option value="8">3 Star or More</option>
                        <option value="9">4 Star or More</option>
                        <option value="10">5 Star or More</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="dropdown-item">
                  <h6 class="small fw-bold">Room 1</h6>
                  <div class="passenger-item">
                    <div class="passenger-info">
                      <h6>Adult <span>(12y +)</span></h6>
                    </div>
                    <div>
                      <select class="small" name="adult_1" onchange="get_hotel_adt(this)">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    </div>
                  </div>

                  <div class="passenger-item mt-2">
                    <div class="passenger-info">
                      <h6>Children <span>(Age 12y and below)</span>
                      </h6>
                    </div>
                    <div>
                      <select class="small" name="child_1" onchange="add_child_age('1',this.value);">
                        <option value="0" selected>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                      </select>
                    </div>
                  </div>
                  <div class="passenger-item mt-3 row" add-room-child-age-element-1="true"></div>
                </div>

                <div append-extra-hotel-room="true"></div>

                <div class="dropdown-item d-flex align-items-center justify-content-between">
                  <button type="button" class="shadow-none btn-sm btn-dark tts__close_dropdown"
                    hotel-room-dropdown-event="true">Done</button>
                  <div>
                    <button class="tts__add__room small shadow-none me-1 text-dark" add-extra-hotel-room-event="true"
                      onclick="add_room()">Add Room</button>
                    <button class="tts__remove__room small shadow-none text-danger hide"
                      remove-extra-hotel-room-event="true" onclick="remove_room()">Remove Room</button>
                  </div>
                </div>
              </div>

            </div>
            <div class="form-group">
              <label class="form-label text-white">Country</label>
              <select class="form-input tts__input__select tts__traveller_select" name="nationalitycode">
                <option value="AF">
                  Afghanistan </option>
                <option value="AX">
                  Aland Islands </option>
                <option value="AL">
                  Albania </option>
                <option value="DZ">
                  Algeria </option>
                <option value="AS">
                  American Samoa </option>
                <option value="AD">
                  Andorra </option>
                <option value="AO">
                  Angola </option>
                <option value="AI">
                  Anguilla </option>
                <option value="AQ">
                  Antarctica </option>
                <option value="AG">
                  Antigua And Barbuda </option>
                <option value="AR">
                  Argentina </option>
                <option value="AM">
                  Armenia </option>
                <option value="AW">
                  Aruba </option>
                <option value="AU">
                  Australia </option>
                <option value="AT">
                  Austria </option>
                <option value="AZ">
                  Azerbaijan </option>
                <option value="BH">
                  Bahrain </option>
                <option value="BD">
                  Bangladesh </option>
                <option value="BB">
                  Barbados </option>
                <option value="BY">
                  Belarus </option>
                <option value="BE">
                  Belgium </option>
                <option value="BZ">
                  Belize </option>
                <option value="BJ">
                  Benin </option>
                <option value="BM">
                  Bermuda </option>
                <option value="BT">
                  Bhutan </option>
                <option value="BO">
                  Bolivia </option>
                <option value="BQ">
                  Bonaire, Sint Eustatius and Saba </option>
                <option value="BA">
                  Bosnia and Herzegovina </option>
                <option value="BW">
                  Botswana </option>
                <option value="BV">
                  Bouvet Island </option>
                <option value="BR">
                  Brazil </option>
                <option value="IO">
                  British Indian Ocean Territory </option>
                <option value="BN">
                  Brunei </option>
                <option value="BG">
                  Bulgaria </option>
                <option value="BF">
                  Burkina Faso </option>
                <option value="BI">
                  Burundi </option>
                <option value="KH">
                  Cambodia </option>
                <option value="CM">
                  Cameroon </option>
                <option value="CA">
                  Canada </option>
                <option value="CV">
                  Cape Verde </option>
                <option value="KY">
                  Cayman Islands </option>
                <option value="CF">
                  Central African Republic </option>
                <option value="TD">
                  Chad </option>
                <option value="CL">
                  Chile </option>
                <option value="CN">
                  China </option>
                <option value="CX">
                  Christmas Island </option>
                <option value="CC">
                  Cocos (Keeling) Islands </option>
                <option value="CO">
                  Colombia </option>
                <option value="KM">
                  Comoros </option>
                <option value="CG">
                  Congo </option>
                <option value="CK">
                  Cook Islands </option>
                <option value="CR">
                  Costa Rica </option>
                <option value="CI">
                  Cote D'Ivoire (Ivory Coast) </option>
                <option value="HR">
                  Croatia </option>
                <option value="CU">
                  Cuba </option>
                <option value="CW">
                  Curaçao </option>
                <option value="CY">
                  Cyprus </option>
                <option value="CZ">
                  Czech Republic </option>
                <option value="CD">
                  Democratic Republic of the Congo </option>
                <option value="DK">
                  Denmark </option>
                <option value="DJ">
                  Djibouti </option>
                <option value="DM">
                  Dominica </option>
                <option value="DO">
                  Dominican Republic </option>
                <option value="TL">
                  East Timor </option>
                <option value="EC">
                  Ecuador </option>
                <option value="EG">
                  Egypt </option>
                <option value="SV">
                  El Salvador </option>
                <option value="GQ">
                  Equatorial Guinea </option>
                <option value="ER">
                  Eritrea </option>
                <option value="EE">
                  Estonia </option>
                <option value="ET">
                  Ethiopia </option>
                <option value="FK">
                  Falkland Islands </option>
                <option value="FO">
                  Faroe Islands </option>
                <option value="FJ">
                  Fiji Islands </option>
                <option value="FI">
                  Finland </option>
                <option value="FR">
                  France </option>
                <option value="GF">
                  French Guiana </option>
                <option value="PF">
                  French Polynesia </option>
                <option value="TF">
                  French Southern Territories </option>
                <option value="GA">
                  Gabon </option>
                <option value="GM">
                  Gambia The </option>
                <option value="GE">
                  Georgia </option>
                <option value="DE">
                  Germany </option>
                <option value="GH">
                  Ghana </option>
                <option value="GI">
                  Gibraltar </option>
                <option value="GR">
                  Greece </option>
                <option value="GL">
                  Greenland </option>
                <option value="GD">
                  Grenada </option>
                <option value="GP">
                  Guadeloupe </option>
                <option value="GU">
                  Guam </option>
                <option value="GT">
                  Guatemala </option>
                <option value="GG">
                  Guernsey and Alderney </option>
                <option value="GN">
                  Guinea </option>
                <option value="GW">
                  Guinea-Bissau </option>
                <option value="GY">
                  Guyana </option>
                <option value="HT">
                  Haiti </option>
                <option value="HM">
                  Heard Island and McDonald Islands </option>
                <option value="HN">
                  Honduras </option>
                <option value="HK">
                  Hong Kong S.A.R. </option>
                <option value="HU">
                  Hungary </option>
                <option value="IS">
                  Iceland </option>
                <option value="IN" selected="">
                  India </option>
                <option value="ID">
                  Indonesia </option>
                <option value="IR">
                  Iran </option>
                <option value="IQ">
                  Iraq </option>
                <option value="IE">
                  Ireland </option>
                <option value="IL">
                  Israel </option>
                <option value="IT">
                  Italy </option>
                <option value="JM">
                  Jamaica </option>
                <option value="JP">
                  Japan </option>
                <option value="JE">
                  Jersey </option>
                <option value="JO">
                  Jordan </option>
                <option value="KZ">
                  Kazakhstan </option>
                <option value="KE">
                  Kenya </option>
                <option value="KI">
                  Kiribati </option>
                <option value="XK">
                  Kosovo </option>
                <option value="KW">
                  Kuwait </option>
                <option value="KG">
                  Kyrgyzstan </option>
                <option value="LA">
                  Laos </option>
                <option value="LV">
                  Latvia </option>
                <option value="LB">
                  Lebanon </option>
                <option value="LS">
                  Lesotho </option>
                <option value="LR">
                  Liberia </option>
                <option value="LY">
                  Libya </option>
                <option value="LI">
                  Liechtenstein </option>
                <option value="LT">
                  Lithuania </option>
                <option value="LU">
                  Luxembourg </option>
                <option value="MO">
                  Macau S.A.R. </option>
                <option value="MK">
                  Macedonia </option>
                <option value="MG">
                  Madagascar </option>
                <option value="MW">
                  Malawi </option>
                <option value="MY">
                  Malaysia </option>
                <option value="MV">
                  Maldives </option>
                <option value="ML">
                  Mali </option>
                <option value="MT">
                  Malta </option>
                <option value="IM">
                  Man (Isle of) </option>
                <option value="MH">
                  Marshall Islands </option>
                <option value="MQ">
                  Martinique </option>
                <option value="MR">
                  Mauritania </option>
                <option value="MU">
                  Mauritius </option>
                <option value="YT">
                  Mayotte </option>
                <option value="MX">
                  Mexico </option>
                <option value="FM">
                  Micronesia </option>
                <option value="MD">
                  Moldova </option>
                <option value="MC">
                  Monaco </option>
                <option value="MN">
                  Mongolia </option>
                <option value="ME">
                  Montenegro </option>
                <option value="MS">
                  Montserrat </option>
                <option value="MA">
                  Morocco </option>
                <option value="MZ">
                  Mozambique </option>
                <option value="MM">
                  Myanmar </option>
                <option value="NA">
                  Namibia </option>
                <option value="NR">
                  Nauru </option>
                <option value="NP">
                  Nepal </option>
                <option value="NL">
                  Netherlands </option>
                <option value="NC">
                  New Caledonia </option>
                <option value="NZ">
                  New Zealand </option>
                <option value="NI">
                  Nicaragua </option>
                <option value="NE">
                  Niger </option>
                <option value="NG">
                  Nigeria </option>
                <option value="NU">
                  Niue </option>
                <option value="NF">
                  Norfolk Island </option>
                <option value="KP">
                  North Korea </option>
                <option value="MP">
                  Northern Mariana Islands </option>
                <option value="NO">
                  Norway </option>
                <option value="OM">
                  Oman </option>
                <option value="PK">
                  Pakistan </option>
                <option value="PW">
                  Palau </option>
                <option value="PS">
                  Palestinian Territory Occupied </option>
                <option value="PA">
                  Panama </option>
                <option value="PG">
                  Papua new Guinea </option>
                <option value="PY">
                  Paraguay </option>
                <option value="PE">
                  Peru </option>
                <option value="PH">
                  Philippines </option>
                <option value="PN">
                  Pitcairn Island </option>
                <option value="PL">
                  Poland </option>
                <option value="PT">
                  Portugal </option>
                <option value="PR">
                  Puerto Rico </option>
                <option value="QA">
                  Qatar </option>
                <option value="RE">
                  Reunion </option>
                <option value="RO">
                  Romania </option>
                <option value="RU">
                  Russia </option>
                <option value="RW">
                  Rwanda </option>
                <option value="SH">
                  Saint Helena </option>
                <option value="KN">
                  Saint Kitts And Nevis </option>
                <option value="LC">
                  Saint Lucia </option>
                <option value="PM">
                  Saint Pierre and Miquelon </option>
                <option value="VC">
                  Saint Vincent And The Grenadines </option>
                <option value="BL">
                  Saint-Barthelemy </option>
                <option value="MF">
                  Saint-Martin (French part) </option>
                <option value="WS">
                  Samoa </option>
                <option value="SM">
                  San Marino </option>
                <option value="ST">
                  Sao Tome and Principe </option>
                <option value="SA">
                  Saudi Arabia </option>
                <option value="SN">
                  Senegal </option>
                <option value="RS">
                  Serbia </option>
                <option value="SC">
                  Seychelles </option>
                <option value="SL">
                  Sierra Leone </option>
                <option value="SG">
                  Singapore </option>
                <option value="SX">
                  Sint Maarten (Dutch part) </option>
                <option value="SK">
                  Slovakia </option>
                <option value="SI">
                  Slovenia </option>
                <option value="SB">
                  Solomon Islands </option>
                <option value="SO">
                  Somalia </option>
                <option value="ZA">
                  South Africa </option>
                <option value="GS">
                  South Georgia </option>
                <option value="KR">
                  South Korea </option>
                <option value="SS">
                  South Sudan </option>
                <option value="ES">
                  Spain </option>
                <option value="LK">
                  Sri Lanka </option>
                <option value="SD">
                  Sudan </option>
                <option value="SR">
                  Suriname </option>
                <option value="SJ">
                  Svalbard And Jan Mayen Islands </option>
                <option value="SZ">
                  Swaziland </option>
                <option value="SE">
                  Sweden </option>
                <option value="CH">
                  Switzerland </option>
                <option value="SY">
                  Syria </option>
                <option value="TW">
                  Taiwan </option>
                <option value="TJ">
                  Tajikistan </option>
                <option value="TZ">
                  Tanzania </option>
                <option value="TH">
                  Thailand </option>
                <option value="BS">
                  The Bahamas </option>
                <option value="TG">
                  Togo </option>
                <option value="TK">
                  Tokelau </option>
                <option value="TO">
                  Tonga </option>
                <option value="TT">
                  Trinidad And Tobago </option>
                <option value="TN">
                  Tunisia </option>
                <option value="TR">
                  Turkey </option>
                <option value="TM">
                  Turkmenistan </option>
                <option value="TC">
                  Turks And Caicos Islands </option>
                <option value="TV">
                  Tuvalu </option>
                <option value="UG">
                  Uganda </option>
                <option value="UA">
                  Ukraine </option>
                <option value="AE">
                  United Arab Emirates </option>
                <option value="GB">
                  United Kingdom </option>
                <option value="US">
                  United States </option>
                <option value="UM">
                  United States Minor Outlying Islands </option>
                <option value="UY">
                  Uruguay </option>
                <option value="UZ">
                  Uzbekistan </option>
                <option value="VU">
                  Vanuatu </option>
                <option value="VA">
                  Vatican City State (Holy See) </option>
                <option value="VE">
                  Venezuela </option>
                <option value="VN">
                  Vietnam </option>
                <option value="VG">
                  Virgin Islands (British) </option>
                <option value="VI">
                  Virgin Islands (US) </option>
                <option value="WF">
                  Wallis And Futuna Islands </option>
                <option value="EH">
                  Western Sahara </option>
                <option value="YE">
                  Yemen </option>
                <option value="ZM">
                  Zambia </option>
                <option value="ZW">
                  Zimbabwe </option>
              </select>
              <div class="flight_text_p"></div>
            </div>

            <button type="submit" class="btn-search" onclick="return checkHotelSearchValidation();"> <i
                class="fas fa-search"></i>Search</button>

          </div>
        </form>

      </div>
    </div>
  </section>
</div>
<section class="p-0 pt-5 pb-5">
  <div class="container">
    <!-- bestselling -->
    <div class="row">
      <div class="destinations-header">
        <h2 >Discover Our Bestselling Packages</h2>
        <a href="{{route('frontend.international-tour-packages')}}" class="view-all-btn" target="_blank">
          All Bestselling <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div class="col-md-12">
        <div class="owl-slider">
          <div id="bestselling" class="owl-carousel ">
            @foreach ($departures as $departure)
            <div class="item">
              <div class="tour-card">
                <a href="{{ url($departure->slug_url_pre.'/'.$departure->slug_url.'/'.$departure->dep_dook_ref_id) }}"
                  target="_blank" aria-label="View {{$departure->title}}">
                  <div class="tour-image">
                    <img src="{{generateSignedUrl('package/'.$departure->image)}}" class="card-img-top" alt="{{$departure->title}}"  @if($loop->first)
                             fetchpriority="high"
                             loading="eager"
                         @else
                             loading="lazy"
                         @endif>
                    @if($departure->featured == 1)
                    <div class="best-selling">BEST SELLING</div>
                    @endif
                  </div>
                  <div class="tour-content">
                    <h3 class="tour-title">
                      {{$departure->title}}
                    </h3>
                    <div class="tour-duration">
                      <i class="fas fa-clock"></i>
                      {{$departure->duration}}
                    </div>
                    <div class="tour-features">
                      @foreach($departure->inclusions as $inclusion)
                      @if($inclusion->icon)
                      <img src="{{ $inclusion->icon }}" alt="{{ $inclusion->name }}" class="inclusion_icon px-1">
                      @endif
                      @endforeach
                      <!-- <div class="feature-icon"><i class="fas fa-plane"></i></div>
                                            <div class="feature-icon"><i class="fas fa-bed"></i></div>
                                            <div class="feature-icon"><i class="fas fa-utensils"></i></div>
                                            <div class="feature-icon"><i class="fas fa-camera"></i></div> -->
                    </div>
                    <div class="tour-highlights">
                      <h4>Tours Highlights</h4>
                      <div class="highlights-grid">
                        @foreach($departure->poi_names as $poiNames)
                        <div class="highlight-item">{{ $poiNames }}</div>
                        @endforeach


                      </div>
                    </div>
                    <div class="tour-price">

                      @if ($departure->price !='' || !is_null($departure->price))
                      <div class="price-info">
                        <div class="original-price">₹{{ formatIndianNumber($departure->price +
                          (round($departure->price * 0.05))) }}</div>
                        <div class="current-price">₹ {{formatIndianNumber($departure->price)}}
                        </div>
                      </div>
                      @endif
                      <div class="tour-actions">
                        <button class="view-btn">View Details</button>
                        @php
                        $whatsappMessage = urlencode("Hi! I'm interested in the " .
                        $departure->country_name . " tour package. Can you provide more
                        details?");
                        @endphp

                        <a href="https://api.whatsapp.com/send?phone=918368513675&text={{ $whatsappMessage }}"
                          class="tour-whatsapp-btn" target="_blank" aria-label="Contact via WhatsApp">
                          <i class="fab fa-whatsapp"></i>
                        </a>
                      </div>
                    </div>

                    {{-- <div class="tour-price">
                      @if ($departure->price !='' || !is_null($departure->price))
                      <div class="price-info">
                        <div class="original-price">₹{{ formatIndianNumber($departure->price +
                          (round($departure->price * 0.05))) }}</div>
                        <div class="current-price">₹ {{formatIndianNumber($departure->price)}}
                        </div>
                      </div>
                      @endif
                      <button class="view-btn">View Details</button>
                    </div> --}}

                  </div>
                </a>
              </div>

            </div>


            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Christmas Lights Section -->
<div class="lights-section">
  <div class="lights-wire"></div>
  <!-- Generate light bulbs with JavaScript -->
  <div id="lights-container"></div>
</div>
<section class="why-dook-section mb-5">
  <!-- Compact Header -->
  <div class="section-header">
    <div class="section-pretitle">Our Expertise & Excellence</div>
    <h2 class="section-title d-flex justify-content-center">
      Why Choose Dook International
    </h2>
    <p class="section-description">
      Delivering exceptional travel experiences through our commitment to quality, innovation, and customer
      satisfaction.
    </p>
  </div>

  <!-- Compact Stats Container -->
  <div class="container">
    <div class="row g-3 g-lg-4">
      <!-- Stat Card 1 -->
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-badge">Trusted</div>
          <div class="stat-icon">
            <i class="fas fa-users-line"></i>
          </div>
          <div class="stat-number" data-target="2000">0+</div>
          <div class="stat-label">Group Tours</div>
          <div class="stat-separator"></div>
          <p class="stat-description">
            Comprehensive group tours across India and internationally.
          </p>
        </div>
      </div>

      <!-- Stat Card 2 -->
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-badge">Global</div>
          <div class="stat-icon">
            <i class="fas fa-earth-americas"></i>
          </div>
          <div class="stat-number" data-target="500">0+</div>
          <div class="stat-label">Destinations</div>
          <div class="stat-separator"></div>
          <p class="stat-description">
            Diverse locations from beaches to cultural sites worldwide.
          </p>
        </div>
      </div>

      <!-- Stat Card 3 -->
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-badge">Premium</div>
          <div class="stat-icon">
            <i class="fas fa-star"></i>
          </div>
          <div class="stat-number" data-target="50">0+</div>
          <div class="stat-label">Experiences</div>
          <div class="stat-separator"></div>
          <p class="stat-description">
            Curated adventures combining luxury and culture.
          </p>
        </div>
      </div>

      <!-- Stat Card 4 -->
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-badge">Verified</div>
          <div class="stat-icon">
            <i class="fas fa-heart"></i>
          </div>
          <div class="stat-number" data-target="400">0k+</div>
          <div class="stat-label">Happy Customers</div>
          <div class="stat-separator"></div>
          <p class="stat-description">
            Satisfied clients who trust us for their travels.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Explore Our Top Destinations Section -->
<section class="destinations-section">
  <div class="container">
    <div class="destinations-header">
      <h2>Explore Our Top Destinations</h2>
      <a href="{{route('frontend.destinations')}}" class="view-all-btn" target="_blank">
        All Destinations <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <div class="row">
      <!-- Destination 1 -->
      @foreach ($topDestinations as $topDestination)
      <div class="col-md-2 col-6 p-2 position-relative text-center">
        <a href="{{url('destinations')}}/{{$topDestination->destination->slug_url}}" target="_blank">
          <div class="ornament-wrapper">
            <div class="ornament">
              <img src="{{generateSignedUrl('poi/'.$topDestination->destination->image)}}"
                alt="{{$topDestination->destination->actualname}}" />
            </div>
          </div>
          <div class="tours-count text-dark fw-bold" style="font-size: 0.78rem;">{{$topDestination->departureDestination->count()}} Tours</div>
          <div class="destination-name text-dark fw-bold" style="font-size: 0.88rem;">{{$topDestination->destination->actualname}}</div>
        </a>
      </div>
      @endforeach
    </div>
    <div class="col-md-12 mt-5 d-lg-block d-none">
      <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner" style="border-radius: 17px;">
          <div class="carousel-item active">
            <a href="#" target="_blank">
              <img src="{{asset('assets/images/Website Carousel Images (6).webp')}}" class="d-block w-100" alt="Kazakhstan With Dook"  width="100" loading="lazy">
            </a>
          </div>
          <div class="carousel-item ">
            <a href="#" target="_blank">
              <img src="{{asset('assets/images/Website Carousel Images (8).webp')}}" class="d-block w-100" alt="Azerbaijan With Dook" width="100" loading="lazy">
            </a>
          </div>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

    </div>
      <div class="col-md-12 mt-5 d-lg-none d-block">
      <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner" style="border-radius: 17px;">
          <div class="carousel-item active">
            <a href="#" target="_blank">
              <img src="{{asset('assets/400 x 200 Website Carousel Mobile (7).webp')}}" class="d-block w-100" alt="Azerbaijan With Dook"  width="100" loading="lazy">
            </a>
          </div>
          <div class="carousel-item ">
            <a href="#" target="_blank">
              <img src="{{asset('assets/400 x 200 Website Carousel Mobile (8).webp')}}" class="d-block w-100" alt="Kazakhstan With Dook" width="100" loading="lazy">
            </a>
          </div>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>

    </div>
  </div>
</section>
<!-- Enhanced Experiences Section -->
<div class="experiences-section container my-5 py-4">
  <div class="row">
    <div class="col-md-12">
      <!-- Header -->
      <div class="experiences-header">
        <h2>Enjoy the Diverse Experiences</h2>
        <a href="#" class="view-all-btn">
          View All <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <!-- Accordion Container -->
      <div class="accordion-container p-0">
        <!-- Adventure Card -->
        <div class="experience-card adventure active" data-card="adventure">
          <span class="card-badge">Popular</span>
          <div class="card-bg"
            style="background-image: url('{{ generateSignedUrl('home/' . $homeSettings->exp_image5) }}');" role="img"
            aria-label="Adventure background"></div>
          <div class="card-overlay"></div>
          <div class="card-content p-4">
            <div class="card-title-vertical">{{$homeSettings->experinceFive->experience_name}}</div>
            <div class="card-expanded-content">
              <div class="card-icon mb-3">
                <i class="fas fa-mountain"></i>
              </div>
              <div class="card-category mb-3">Thrilling</div>
              <h3 class="card-title mb-3">Adventure</h3>
              <p class="card-text mb-3">
                Embark on thrilling journeys and discover breathtaking
                landscapes. Perfect for adrenaline seekers and nature lovers.
              </p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="feature-tag">
                  <i class="fas fa-hiking"></i> Hiking
                </span>
                <span class="feature-tag">
                  <i class="fas fa-mountain"></i> Trekking
                </span>
                <span class="feature-tag">
                  <i class="fas fa-parachute-box"></i> Skydiving
                </span>
              </div>
              @if($experiencefivePrices != '' || $experiencefivePrices != null)
              <div class="card-price mb-3">
                <span class="price-label">Starts From</span>
                <span class="price-value">₹ {{$experiencefivePrices}}</span>
              </div>
              @endif
              <a href="{{url('/')}}/{{$homeSettings->experinceFive->slug_url}}" class="card-btn">
                <span>Explore Now</span> <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Honeymoon Card -->
        <div class="experience-card honeymoon" data-card="honeymoon">
          <span class="card-badge">Romantic</span>
          <div class="card-bg"
            style="background-image: url('{{ generateSignedUrl('home/'.$homeSettings->exp_image1) }}');" role="img"
            aria-label="Honeymoon background"></div>
          <div class="card-overlay"></div>
          <div class="card-content p-4">
            <div class="card-title-vertical">{{$homeSettings->experinceOne->experience_name}}</div>
            <div class="card-expanded-content">
              <div class="card-icon mb-3">
                <i class="fas fa-heart"></i>
              </div>
              <div class="card-category mb-3">Romantic</div>
              <h3 class="card-title mb-3">Honeymoon</h3>
              <p class="card-text mb-3">
                Create unforgettable memories with your loved one in the most
                romantic destinations around the world.
              </p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="feature-tag">
                  <i class="fas fa-cocktail"></i> Luxury
                </span>
                <span class="feature-tag">
                  <i class="fas fa-spa"></i> Spa
                </span>
                <span class="feature-tag">
                  <i class="fas fa-champagne-glasses"></i> Fine Dining
                </span>
              </div>
              @if($experienceOnePrices != '' || $experienceOnePrices != null)
              <div class="card-price mb-3">
                <span class="price-label">Starts From</span>
                <span class="price-value">₹{{$experienceOnePrices}}</span>
              </div>
              @endif
              <a href="{{url('/')}}/{{$homeSettings->experinceOne->slug_url}}" class="card-btn">
                <span>Explore Now</span> <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Family Card -->
        <div class="experience-card family" data-card="family">
          <span class="card-badge">Family Fun</span>
          <div class="card-bg"
            style="background-image: url('{{generateSignedUrl('home/'.$homeSettings->exp_image2)}}');" role="img"
            aria-label="Family background"></div>
          <div class="card-overlay"></div>
          <div class="card-content p-4">
            <div class="card-title-vertical">{{$homeSettings->experinceTwo->experience_name}}</div>
            <div class="card-expanded-content">
              <div class="card-icon mb-3">
                <i class="fas fa-users"></i>
              </div>
              <div class="card-category mb-3">Together</div>
              <h3 class="card-title mb-3">Family</h3>
              <p class="card-text mb-3">
                Quality time with your loved ones. Create lasting memories with
                family-friendly activities and destinations.
              </p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="feature-tag">
                  <i class="fas fa-child"></i> Kid-Friendly
                </span>
                <span class="feature-tag">
                  <i class="fas fa-umbrella-beach"></i> Beach
                </span>
                <span class="feature-tag">
                  <i class="fas fa-camera"></i> Sightseeing
                </span>
              </div>

              @if($experiencethreePrices != '' || $experiencethreePrices != null)
              <div class="card-price mb-3">
                <span class="price-label">Starts From</span>
                <span class="price-value">₹ {{$experiencetwoPrices}}</span>
              </div>
              @endif
              <a href="{{url('/')}}/{{$homeSettings->experinceTwo->slug_url}}" class="card-btn">
                <span>Explore Now</span> <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Shopping Card -->
        <div class="experience-card shopping" data-card="shopping">
          <span class="card-badge">Best Deals</span>
          <div class="card-bg"
            style="background-image: url('{{generateSignedUrl('home/'.$homeSettings->exp_image3)}}');" role="img"
            aria-label="Shopping background"></div>
          <div class="card-overlay"></div>
          <div class="card-content p-4">
            <div class="card-title-vertical">{{$homeSettings->experinceThree->experience_name}}</div>
            <div class="card-expanded-content">
              <div class="card-icon mb-3">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div class="card-category mb-3">Retail Therapy</div>
              <h3 class="card-title mb-3">Shopping</h3>
              <p class="card-text mb-3">
                Discover the finest shopping destinations and indulge in retail
                therapy at its best with exclusive deals.
              </p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="feature-tag">
                  <i class="fas fa-store"></i> Malls
                </span>
                <span class="feature-tag">
                  <i class="fas fa-tag"></i> Discounts
                </span>
                <span class="feature-tag">
                  <i class="fas fa-gem"></i> Luxury
                </span>
              </div>
              @if($experiencethreePrices != '' || $experiencethreePrices != null)
              <div class="card-price mb-3">
                <span class="price-label">Starts From</span>
                <span class="price-value">₹ {{$experiencethreePrices}}</span>
              </div>
              @endif
              <a href="{{url('/')}}/{{$homeSettings->experinceThree->slug_url}}" class="card-btn">
                <span>Explore Now</span> <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Nightlife Card -->
        <div class="experience-card nightlife" data-card="nightlife">
          <span class="card-badge">Vibrant</span>
          <div class="card-bg"
            style="background-image: url('{{generateSignedUrl('home/'.$homeSettings->exp_image4)}}');" role="img"
            aria-label="Nightlife background"></div>
          <div class="card-overlay"></div>
          <div class="card-content p-4">
            <div class="card-title-vertical">{{$homeSettings->experinceFour->experience_name}}</div>
            <div class="card-expanded-content">
              <div class="card-icon mb-3">
                <i class="fas fa-moon"></i>
              </div>
              <div class="card-category mb-3">After Dark</div>
              <h3 class="card-title mb-3">Nightlife</h3>
              <p class="card-text mb-3">
                Experience vibrant nightlife and entertainment in the world's
                most exciting cities. Dance until dawn.
              </p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="feature-tag">
                  <i class="fas fa-music"></i> Live Music
                </span>
                <span class="feature-tag">
                  <i class="fas fa-cocktail"></i> Bars
                </span>
                <span class="feature-tag">
                  <i class="fas fa-compact-disc"></i> Clubs
                </span>
              </div>
              @if($experiencefourPrices != '' || $experiencefourPrices != null)
              <div class="card-price mb-3">
                <span class="price-label">Starts From</span>
                <span class="price-value">₹ {{$experiencefourPrices}}</span>
              </div>
              @endif
              <a href="{{url('/')}}/{{$homeSettings->experinceFour->slug_url}}" class="card-btn">
                <span>Explore Now</span> <i class="fas fa-arrow-right"></i>
              </a>
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