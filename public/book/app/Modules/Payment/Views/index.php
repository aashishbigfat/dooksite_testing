<style>
   .payment-section .card-header {
      background: #ffffff;
      padding: 15px;
      font-size: 16px;
      font-weight: 700;
      border-radius: 10px 10px 0 0;
   }

   .payment-section .nav {
      background: #e8e8e8;
      height: 100%;
      border-bottom-left-radius: 10px;
   }

   .payment-section .nav li {
      list-style: none;
      transition: transform 0.3s ease-in-out;
      border-bottom: 1px solid #dedede;
   }

   .payment-section .nav li a {
      color: #111827;
      width: 100%;
      background: #e8e8e8;
      padding: 15px 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease-in-out;
   }

   .payment-section .nav li a:hover {
      background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
      color: #000;
   }

   .payment-section .nav li a.active {
      background: var(--tts-buttton-txt);
      color: var(--tts-buttton-bg);
   }

   .payment-section .tab-content {
      padding: 20px;
      height: 100%;
      background: #ffffff;
      border-radius: 10px;
      transition: all 0.3s ease-in-out;
   }

   .payment-item {
      display: flex;
      flex-direction: column;
      padding: 10px;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      background: linear-gradient(135deg, #ffffff, #f9f9f9);
      transition: all 0.3s ease-in-out;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      height: 100%;
   }

   /* .payment-item:hover {
      background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
      transform: scale(1.05);
      border-color: var(--tts-buttton-bg);
      box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
   } */

   .payment-item label {
      display: block;
      cursor: pointer;
   }

   .payment-item img {
      transition: transform 0.3s ease-in-out;
      margin-bottom: 10px;
   }

   .payment-item:hover img {
      transform: scale(1.1);
   }

   .payment-item span {
      font-weight: 600;
      font-size: 12px;
      color: #333;
      transition: color 0.3s ease-in-out;
   }

   /* Active Border Effect */
   .payment-item.active-border {
      border-color: var(--tts-buttton-bg);
      background: linear-gradient(135deg, #b5000012, #b5000012);
      box-shadow: 0 4px 12px #b500004f;
   }

   .payment-section .card ul.flight_details {
      border-top: 1px solid #f0f0f0;
      border-bottom: 1px solid #f0f0f0;
      margin: 8px -20px 20px;
   }

   .payment-section .card ul.flight_details li {
      padding: 10px 13px;
      border-right: 1px solid #f0f0f0;
      -moz-column-gap: 15px;
      column-gap: 15px;
   }

   .payment-section .card ul.flight_details li img {
      width: 30px;
      height: 30px;
      display: block;
      -o-object-fit: cover;
      object-fit: cover;
      min-width: 30px;
      background: #ddd;
   }

   .payment-section .card ul.flight_details li h3 {
      font-size: 14px;
      color: #000;
      line-height: 16px;
      margin: 0;
   }

   .payment-section .card ul.flight_details li span {
      font-weight: normal;
      font-size: 12px;
      display: block;
   }

   .payment-section .card ul.flight_details li h2 {
      color: #000;
      font-size: 14px;
      margin: 0;
   }

   .payment-section .card ul.flight_details li h4 {
      font-size: 13px;
      color: #000;
      font-weight: 700;
      line-height: 16px;
   }

   .payment-section .card ul.flight_details li h4 span {
      display: block;
      font-size: 12px;
      font-weight: 400;
   }
</style>

<section class="content payment-section" ng-app="paymentApp" ng-controller="paymentCtrl" ng-cloak>
   <div class="container">
      <div class="row g-3">
         <div class="col-lg-9 col-12 col-md-8">
            <!-------flight start------->
            <div class="flight-booking" ng-if="BookingInfo != undefined && Service === 'flight'">
               <div class="flight-booking-item">
                  <div ng-repeat="(journeyKey, Data) in BookingInfo">
                     <div ng-init="segments = jsonParse(Data.segments)">
                        <div ng-repeat="(tripKey, tripData) in segments">
                           <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                              <h6 class="mb-0">{{journeyKey === 'OB' && tripKey === 0 ? "DEPART" : "RETURN"}}</h6>
                           </div>
                           <div class="flight-booking-info" ng-if="tripData != undefined" ng-repeat="(segmentIndicatorkey, segmentData) in tripData">
                              <div class="flight-booking-content row">
                                 <div class="flight-booking-airline col-lg-3">
                                    <div class="flight-airline-img">
                                       <img ng-src="{{imageURL + 'airline-images/' + segmentData.Airline.AirlineCode + '.png'}}" alt="{{segmentData.Airline.AirlineName}}" class="airline-logo me-2">
                                    </div>
                                    <h5 class="flight-airline-name">
                                       {{segmentData.Airline.AirlineName}}
                                       <span class="flight-code">
                                          {{segmentData.Airline.AirlineCode}}- {{segmentData.Airline.FlightNumber}}
                                          {{segmentData.Airline.FareClass != '' ? segmentData.Airline.FareClass : '-'}}
                                       </span>
                                    </h5>
                                 </div>
                                 <div class="flight-booking-time col-lg-9">
                                    <div class="start-time">
                                       <div class="start-time-icon">
                                          <i class="fal fa-plane-departure"></i>
                                       </div>
                                       <div class="start-time-info">
                                          <h6 class="start-time-text"> {{getFlightTime(segmentData.Origin.DepartTime)}} <span>({{getFlightDate(segmentData.Origin.DepartTime)}})</span></h6>
                                          <span class="flight-destination">{{segmentData.Origin.CityName}} <b>({{segmentData.Origin.CityCode}})</b></span>
                                          <span class="start-Depart-text d-block">{{segmentData.Origin.AirportName}}</span>
                                          <span class="start-Depart-text d-block">Terminal - {{segmentData.Origin.Terminal != "" ? segmentData.Origin.Terminal : ""}}</span>
                                       </div>
                                    </div>
                                    <div class="flight-stop">
                                       <div class="flight-stop-arrow"></div>
                                       <div class="flight-booking-duration">
                                          <span class="duration-text">{{convertToHoursMinsfromMinDuration(segmentData.Duration)}} </span>
                                       </div>
                                    </div>
                                    <div class="end-time">
                                       <div class="start-time-icon">
                                          <i class="fal fa-plane-arrival"></i>
                                       </div>
                                       <div class="start-time-info">
                                          <h6 class="start-time-text"> {{getFlightTime(segmentData.Destination.ArrivalTime)}} <span>({{getFlightDate(segmentData.Destination.ArrivalTime)}})</span></h6>
                                          <span class="flight-destination">{{segmentData.Destination.CityName}} <b>({{segmentData.Destination.CityCode}})</b></span>
                                          <span class="flight-destination d-block">{{segmentData.Destination.AirportName}}</span>
                                          <span class="flight-destination d-block">Terminal - {{segmentData.Destination.Terminal != "" ? segmentData.Destination.Terminal : ""}}</span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="flight-indicator-content mt-3 d-flex align-items-center justify-content-between">
                                       <span><b>Aircraft:</b> {{segmentData.Craft}}</span>
                                       <span><b>Cabin Class:</b> {{segmentData.CabinClass}}</span>
                                       <span><b>Check-In Baggage :</b> {{segmentData.CheckInBaggage}}</span>
                                       <span><b>Cabin Baggage :</b> {{segmentData.CabinBaggage}}</span>
                                    </div>

                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>

                  </div>
               </div>

               <div class="card">
                  <div class="card-body" ng-repeat="(journeyKey, Data) in BookingInfo">
                     <div class="traveller d-flex align-items-center justify-content-between gap-3">
                        <h6 class="mb-0">Travellers</h6>
                        <div ng-if="Data.travelersInfo != undefined" ng-init="TravellersInfo = jsonParse(Data.travelersInfo)">
                           <div class="traveller-detail" ng-repeat="Traveller in TravellersInfo">
                              <i class="fa-solid fa-user"></i>
                              <span class="name">
                                 {{Traveller.title + " " + Traveller.first_name + " " + Traveller.last_name}}
                              </span>
                              <span class="gender">
                                 {{ucFirst(Traveller.pax_type)}},
                                 {{ucFirst(Traveller.gendar.substring(0,1))}}
                              </span>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>

               <div class="card">
                  <div class="card-body" ng-repeat="(journeyKey, Data) in BookingInfo">
                     <div class="contact_form d-flex align-items-center justify-content-between gap-3" ng-if="Data.travelersInfo != undefined" ng-init="TravellersInfo = jsonParse(Data.travelersInfo)">
                        <h6 class="mb-0">Contact</h6>
                        <div class="contact_form-detail">
                           <i class="fa-solid fa-phone"></i> {{TravellersInfo[0].mobile_number}}
                        </div>
                        <div class="">
                           <i class="fa-solid fa-envelope"></i> {{TravellersInfo[0].email_id}}
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <!-------hotel start------->
            <div class="hotel-booking" ng-if="BookingInfo != undefined && Service === 'hotel'">
               <div class="card">
                  <div class="card-body booking-summary-card">
                     <h6 class="mb-3"> {{BookingInfo['hotel_name']}}</h6>
                     <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                           <h6>Check In</h6>
                           <p class="mb-0">{{dateIndianFormat(BookingInfo['check_in_date'])}}</p>
                        </div>
                        <div class="text-center">
                           <h6>Nights</h6>
                           <p class="mb-0">{{BookingInfo['no_of_nights']}}</p>
                        </div>
                        <div>
                           <h6>Check Out</h6>
                           <p class="mb-0">{{dateIndianFormat(BookingInfo['check_out_date'])}}</p>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card">
                  <div class="card-header">Room Info</div>
                  <div class="card-body booking-summary-card pt-0 pb-0">
                     <ul class="list-group list-group-flush" ng-init="roomInfo = jsonParse(BookingInfo['hotel_rooms_details'])">
                        <li class="list-group-item px-0 p-3" ng-repeat="(roomKey, roomData) in roomInfo">
                           <h6> Room {{roomKey+1}} </h6>
                           <p> {{roomData['RoomTypeName']}} ({{roomData['RatePlanName']}}) </p>
                           <div class="traveller d-flex align-items-center justify-content-between gap-3">
                              <div ng-repeat="traveller in roomData['HotelPassenger']">
                                 <p class="gender fw-bold mb-0">{{traveller['PaxType'] == '1' ? "Adult" : "Child"}}</p>
                                 <span class="name">{{traveller['Title'] + " " + traveller['FirstName'] + " " + traveller['LastName']}}</span>

                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>

               <div class="card">
                  <div class="card-body booking-summary-card">
                     <div class="contact_form d-flex align-items-center justify-content-between gap-3">
                        <h6 class="mb-0">Contact</h6>
                        <div class="contact_form-detail">
                           <i class="fa-solid fa-phone"></i> {{BookingInfo['mobile_number']}}
                        </div>
                        <div>
                           <i class="fa-solid fa-envelope"></i>
                           {{BookingInfo['email_id']}}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------Bus start------->
            <div class="Bus_booking" ng-if="BookingInfo != undefined && Service === 'bus'">
               <div class="card">
                  <div class="card-body">

                     <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                           <h6>{{BookingInfo['origin_city'] + "-" + BookingInfo['destination_city']}}</h6>
                           <span>{{BookingInfo['bus_name'] + "-" + BookingInfo['bus_type']}}</span>
                        </div>
                        <div>
                           <h6>Origin</h6>
                           <p class="mb-0 ng-binding">{{BookingInfo['origin_city']}}</p>
                        </div>
                        <div class="text-center">
                           <h6>Destination</h6>
                           <p class="mb-0 ng-binding">{{BookingInfo['destination_city']}}</p>
                        </div>
                        <div>
                           <h6>Travel Date</h6>
                           <p class="mb-0 ng-binding">{{BookingInfo['date_of_journey']}}</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card" ng-init="travellersInfo = jsonParse(BookingInfo['travelersInfo'])">
                  <div class="card-body d-flex align-items-center justify-content-between gap-3" ng-repeat="traveller in travellersInfo">
                     <h6 class="mb-0">Travellers</h6>
                     <span class="name"><i class="fa-solid fa-user"></i> {{traveller['title'] + " " + traveller['first_name'] + " " + traveller['last_name']}}</span>
                     <span class="gender"><strong>Gender:</strong> {{ucFirst(traveller['gendar'].substring(0,1))}}</span>
                  </div>
               </div>
               <div class="card">
                  <div class="card-body">
                     <div class="contact_form d-flex align-items-center justify-content-between gap-3" ng-init="travellersInfo = jsonParse(BookingInfo['travelersInfo'])">
                        <h6 class="mb-0">Contact</h6>
                        <span class="phone"><i class="fa-solid fa-phone"></i> {{travellersInfo[0]['mobile_number']}}</span>
                        <span class="email"><i class="fa-solid fa-envelope"></i> {{travellersInfo[0]['email_id']}}</span>
                     </div>
                  </div>
               </div>
            </div>

            <!------payment option--------->
            <div class="card">
               <div class="card-header">
                  Payment Options
               </div>
               <div class="card-body p-0">
                  <div class="row gy-4 gx-lg-0">
                     <div class="col-lg-3">
                        <ul class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                           <li ng-repeat="item in PaymentModes track by $index">
                              <a class="nav-link" ng-class="{ 'active': $index === activeTabIndex }" id="{{ item.Mode }}-tab" ng-click="toggleMode(TotalPrice, $index)" role="tab" aria-controls="{{ item.Mode }}" aria-selected="{{ $index === activeTabIndex ? 'true' : 'false' }}">
                                 {{item.Label}}
                              </a>
                           </li>
                        </ul>
                     </div>
                     <div class="col-lg-9">
                        <div class="tab-content" id="v-pills-tabContent">
                           <div class="tab-pane fade" ng-class="{'show active' : $index === activeTabIndex}" id="{{key}}" role="tabpanel" aria-labelledby="{{key}}-tab" ng-repeat="(key, item) in PaymentModes track by $index">
                              <!-- Modes other than CREDIT CARD -->
                              <div class="row gy-2 gx-lg-2" ng-if="key !== 'CRDC'">
                                 <div ng-repeat="SubMode in item.SubModes track by $index" class="col-md-3" style="cursor:pointer; display:block;">
                                    <div class="payment-item" ng-click="calculateCFEE(key, SubMode, $index)" ng-class="{ 'active-border': $index === activeIndex }">
                                       <label for="" class="text-center"><img class="img-fluid" ng-src="{{gatewayURL + SubMode.Gateway + '.svg'}}" alt="{{SubMode.Gateway}}"> <span class="d-block">{{SubMode.Gateway}}</span></label>

                                    </div>
                                 </div>
                              </div>
                              <!-- Modes other than CREDIT CARD -->
                              <!--  -->
                              <!-- For CREDIT CARD Mode -->
                              <div class="row gy-2 gx-lg-2" ng-if="key === 'CRDC'">
                                 <div ng-repeat="SubMode in item.SubModes" class="col-md-3" style="cursor:pointer; display:block;">
                                    <div class="payment-item" ng-class="{ 'active-border': $index === activeIndex }">
                                       <label for="" class="text-center"><img class="img-fluid" ng-src="{{gatewayURL + SubMode.Gateway + '.svg'}}" alt="{{SubMode.Gateway}}"><span class="d-block"> {{SubMode.Gateway}}</span></label>
                                       <div ng-repeat="(CredKey, Type) in SubMode.Type" class="form-check">
                                          <label class="form-check-label" ng-click="calculateCFEE(key, SubMode, $parent.$index, Type, CredKey)" for="{{SubMode.Gateway + '-' + CredKey.split('_')[0]}}">
                                             <input class="form-check-input" type="radio" name="credit-card-type" id="{{SubMode.Gateway + '-' + CredKey.split('_')[0]}}" value="{{ucFirst(CredKey.split('_')[0])}}">
                                             <span ng-if="CredKey.split('_')[0] === 'credit'">
                                                {{"ANY" + ucFirst(CredKey.split('_')[0]) + "Card"}}
                                             </span>
                                             <span ng-if="CredKey.split('_')[0] !== 'credit'">
                                                {{ucFirst(CredKey.split('_')[0]) + "Card"}}
                                             </span>
                                          </label>
                                       </div>

                                    </div>
                                 </div>
                              </div>
                              <!-- For CREDIT CARD Mode -->
                           </div>

                           <div class="mt-3">
                              <!-- <p>Convenience Fee : <span class="cfee">{{CFEE * currency_rate}}</span></p> -->
                              <p>Convenience Fee : <span class="cfee">{{ CFEE | decimalPriceabhay }}</span></p>
                              <p class="text-danger"><span class="gateway-remark">{{GatewayRemark}}</span></p>
                              <p><strong>Please note:</strong> By placing this order, you agree to our Terms Of Use and Privacy Policy </p>
                              <form action="{{paymentURL}}" id="payment-form">
                                 <button type="submit" class="btn btn-danger pay-btn" ng-disabled="isButtonDisabled">
                                    Pay Now <?php echo $CurrencySymbol; ?>
                                    <span class="total-price">
                                       {{TotalPriceWithCFEE}}
                                    </span>
                                 </button>
                              </form>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!------payment option end--------->
         </div>
         <!-- Flight Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'flight'">
            <div class="card" ng-repeat="(fareKey, fareInfo) in FareInfoArray" ng-if="fareKey !== 'CFEE' && fareKey !== 'Total'">
               <div class="card-header">
                  <span ng-if="FareInfoArray.hasOwnProperty('IB')">
                     {{fareKey === 'OB'? "Onward" : "Return"}} Fare Summary
                  </span>
                  <span ng-if="!FareInfoArray.hasOwnProperty('IB')">
                     Fare Summary
                  </span>
               </div>
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between align-items-center mt-2" ng-repeat="breakup in fareInfo.FareBreakup">
                     <p class="m-0 "> {{breakup.Label}} </p>
                     <span class="fw-bold"><?php echo $CurrencySymbol; ?> {{breakup.Value}} </span>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between align-items-center mt-2" ng-if="FareInfoArray.hasOwnProperty('CFEE')">
                     <p class="m-0">{{FareInfoArray.CFEE.Label}}</p>
                     <span class="fw-bold"><?php echo $CurrencySymbol; ?> {{FareInfoArray.CFEE.Value}}</span>
                  </div>

                  <div class="d-flex justify-content-between align-items-center mt-2">
                     <p class="m-0 fw-bold">{{FareInfoArray.Total.Label}}</p>
                     <span class="fw-bold"><?php echo $CurrencySymbol; ?> {{FareInfoArray.Total.Value}}</span>
                  </div>
               </div>
            </div>
         </div>

         <!-- Hotel Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'hotel'">
            <div class="card sticky-top">
               <div class="card-header">
                  Fare Summary
               </div>
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between align-items-center mt-2" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <p class="m-0">{{breakup.Label}}</p>
                     <span class="fw-bold"><?php echo $CurrencySymbol; ?> {{breakup.Value}} </span>
                  </div>
               </div>
            </div>
         </div>

         <!-- Bus Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'bus'">
            <div class="card sticky-top">
               <div class="card-header">Fare Summary</div>
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between align-items-center mt-2" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <p class="m-0">{{breakup.Label}}</p>
                     <span class="fw-bold"><?php echo $CurrencySymbol; ?> {{breakup.Value}} </span>
                  </div>
               </div>
            </div>
         </div>
         <!-- Bus Booking Summary -->

         <!-- Holiday Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'holiday'">
            <div class="payment-title ">
               <h5>Booking Summary</h5>
            </div>
            <div class="card mb-3">
               <div class="card-body booking-summary-card">
                  <div class="tts-holiday-review-details">
                     <?php if (isset($booking_data['HolidayImage'])) { ?>
                        <img src="<?php echo $booking_data['package_image'] ?>" class="img-fluid">
                     <?php } else { ?>
                        <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                     <?php } ?>
                     <div>
                        <ul>
                           <li>
                              <p>Package</p>
                              <span>{{BookingInfo['package_name']}}</span>
                           </li>
                           <li class="Duration-list">
                              <p>Category</p>
                              <span>{{BookingInfo['package_category']}}</span>
                           </li>
                           <li>
                              <p>Duration</p>
                              <span>{{BookingInfo['day_nights']}}</span>
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div class="traveller mb-3">
                     <div class="traveller mb-3">
                        <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                        <div class="traveller-detail">
                           <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                           <span class="name">
                              {{BookingInfo['title'] + " " + BookingInfo['first_name'] + " " +
                              BookingInfo['last_name']}}
                           </span>
                        </div>
                     </div>

                     <div class="contact_form">
                        <div class="title" style="font-weight: 600;">CONTACT</div>
                        <div class="contact_form-detail">
                           <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                           <span class="phone">
                              {{BookingInfo['mobile_number']}}
                           </span><br />
                           <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                           <span class="email">&nbsp;
                              {{BookingInfo['email_id']}}
                           </span>
                        </div>
                     </div>
                  </div>

               </div>
            </div>

            <div class="payment-title ">
               <h5> Fare Summary</h5>
            </div>

            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between  align-items-center" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <h5>{{breakup.Label}}</h5>
                     <b>
                        <?php echo $CurrencySymbol; ?> {{breakup.Value}}
                     </b>
                  </div>
               </div>
            </div>

         </div>
         <!-- Holiday Booking Summary -->
         <!-- Activity Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'activities'">
            <div class="payment-title ">
               <h5>Booking Summary</h5>
            </div>

            <div class="card mb-3">
               <div class="card-body booking-summary-card">

                  <div class="tts-holiday-review-details">
                     <?php if (isset($booking_data['activity_image'])) { ?>
                        <img src="<?php echo root_url . 'uploads/activities_package/thumbnail/' . $booking_data['activity_image']; ?>" alt="<?php echo $booking_data['activity_image']; ?>" class="img-fluid">
                     <?php } else { ?>
                        <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                     <?php } ?>
                     <div>
                        <ul>
                           <li>
                              <h6>Activities Name</h6>
                              <h5>{{BookingInfo['activity_name']}}</h5>
                           </li>
                           <li class="Duration-list">
                              <h6>Booking Date</h6>
                              <h5>{{BookingInfo['book_date']}}</h5>
                           </li>
                           <li class="text-end">
                              <h6>Duration</h6>
                              <h5>{{BookingInfo['activity_duration']}}</h5>
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div class="traveller mb-3">
                     <div class="traveller mb-3">
                        <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                        <div class="traveller-detail">
                           <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                           <span class="name">
                              {{BookingInfo['title'] + " " + BookingInfo['first_name'] + " " +
                              BookingInfo['last_name']}}
                           </span>
                        </div>
                     </div>

                     <div class="contact_form">
                        <div class="title" style="font-weight: 600;">CONTACT</div>
                        <div class="contact_form-detail">
                           <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                           <span class="phone">
                              {{BookingInfo['mobile_number']}}
                           </span><br />
                           <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                           <span class="email">&nbsp;
                              {{BookingInfo['email_id']}}
                           </span>
                        </div>
                     </div>
                  </div>

               </div>
            </div>

            <div class="payment-title ">
               <h5> Fare Summary</h5>
            </div>

            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between  align-items-center" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <h5>{{breakup.Label}}</h5>
                     <b>
                        <?php echo $CurrencySymbol; ?> {{breakup.Value}}
                     </b>
                  </div>
               </div>
            </div>

         </div>
         <!-- Activity Booking Summary -->
         <!-- Visa Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'visa'">
            <div class="payment-title ">
               <h5>Booking Summary</h5>
            </div>

            <div class="card mb-3">
               <div class="card-body booking-summary-card">
                  <div class="tts-holiday-review-details border-bottom mb-2 pb-2">
                     <div class="d-flex align-items-center justify-content-between">
                        <p class="m-0">Country Name</p>
                        <span class="fw-bold">{{BookingInfo['visa_country']}}</span>
                     </div>
                     <div class="d-flex align-items-center justify-content-between">
                        <p class="m-0">Processing Time</p>
                        <span class="fw-bold">{{BookingInfo['processing_time']}}</span>
                     </div>
                     <div class="d-flex align-items-center justify-content-between">
                        <p class="m-0">Total Pax</p>
                        <span class="fw-bold">{{BookingInfo['no_of_travellers']}}</span>
                     </div>
                     <div class="d-flex align-items-center justify-content-between">
                        <p class="m-0">Visa Type</p>
                        <span class="fw-bold">{{BookingInfo['visa_type']}}</span>
                     </div>
                  </div>


                  <div class="traveller border-bottom mb-2 pb-2 d-flex align-items-center justify-content-between">
                     <div class="traveller-detail ">
                        <div class="title fw-bold">TRAVELLERS</div>
                        <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                        <span class="name"> {{BookingInfo['title'] + " " + BookingInfo['first_name'] + " " + BookingInfo['last_name']}} </span>
                     </div>
                     <div class="traveller-detail">
                        <div class="title fw-bold">Journey Date</div>
                        <span class="gender"> {{BookingInfo['date_of_journey']}} </span>
                     </div>
                  </div>

                  <div class="contact_form">
                     <div class="title fw-bold">CONTACT</div>
                     <div class="contact_form-detail">
                        <div>
                           <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                           <span class="phone"> {{BookingInfo['mobile_number']}} </span>
                        </div>
                        <div>
                           <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                           <span class="email"> {{BookingInfo['email_id']}} </span>
                        </div>
                     </div>
                  </div>

               </div>
            </div>

            <div class="payment-title ">
               <h5> Fare Summary</h5>
            </div>

            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between  align-items-center" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <p class="m-0">{{breakup.Label}}</p>
                     <span class="fw-bold"> <?php echo $CurrencySymbol; ?> {{breakup.Value}}</span>
                  </div>
               </div>
            </div>
         </div>
         <!-- Visa Booking Summary -->
         <!-- Car Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4 mb-3" ng-if="BookingInfo != undefined && Service === 'car'">
            <div class="payment-title ">
               <h5>Booking Summary</h5>
            </div>

            <div class="card mb-3">
               <div class="card-body booking-summary-card">
                  <div class="tts-holiday-review-details">
                     <?php if (isset($booking_data['car_img'])) { ?>
                        <img src="<?php echo root_url . 'uploads/car-extranet/' . $booking_data['car_img']; ?>" alt="<?php echo $booking_data['car_img']; ?>" class="img-fluid">
                     <?php } else { ?>
                        <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                     <?php } ?>

                     <div>
                        <ul>
                           <li>
                              <h6>Car Name</h6>
                              <h5>{{BookingInfo['car_name']}}</h5>
                           </li>
                           <li class="Duration-list">
                              <h6>Source</h6>
                              <h5>{{BookingInfo['source']}}</h5>
                           </li>
                           <li class="text-end">
                              <h6>Destination</h6>
                              <h5>{{BookingInfo['destination']}}</h5>
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div class="traveller mb-3">
                     <div class="traveller mb-3">
                        <div class="title fw-bold">TRAVELLERS</div>
                        <div class="traveller-detail">
                           <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                           <span class="name">
                              {{BookingInfo['title'] + " " + BookingInfo['first_name'] + " " +
                              BookingInfo['last_name']}}
                           </span> <br />
                           <div class="title fw-bold">Journey Date</div>
                           <span class="gender"> {{BookingInfo['date_of_journey']}} </span>
                        </div>
                     </div>

                     <div class="contact_form">
                        <div class="title fw-bold">CONTACT</div>
                        <div class="contact_form-detail">
                           <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                           <span class="phone">
                              {{BookingInfo['mobile_number']}}
                           </span><br />
                           <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                           <span class="email">&nbsp;
                              {{BookingInfo['email_id']}}
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="payment-title ">
               <h5> Fare Summary</h5>
            </div>

            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between  align-items-center" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <h5>{{breakup.Label}}</h5>
                     <b>
                        <?php echo $CurrencySymbol; ?> {{breakup.Value}}
                     </b>
                  </div>
               </div>
            </div>

         </div>
         <!-- Car Booking Summary -->
         <!-- Tourguide Booking Summary -->
         <div class="col-lg-3 col-12 col-md-4" ng-if="BookingInfo != undefined && Service === 'tourguide'">
            <div class="payment-title ">
               <h5>Booking Summary</h5>
            </div>

            <div class="card mb-3">
               <div class="card-body booking-summary-card">
                  <div class="tts-holiday-review-details">
                     <div>
                        <ul>
                           <li>
                              <h6>Guide Name</h6>
                              <h5>{{BookingInfo['guide_name']}}</h5>
                           </li>
                           <li>
                              <h6>Monument Name</h6>
                              <h5>{{BookingInfo['monument_title']}}</h5>
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div class="traveller mb-3">
                     <div class="traveller mb-3">
                        <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                        <div class="traveller-detail">
                           <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                           <span class="name">
                              {{BookingInfo['title'] + " " + BookingInfo['first_name'] + " " +
                              BookingInfo['last_name']}}
                           </span> <br />
                           <div class="title" style="font-weight: 600;">Travel Date</div>
                           <span class="gender">
                              {{BookingInfo['travel_date']}}
                           </span>
                        </div>
                     </div>

                     <div class="contact_form">
                        <div class="title" style="font-weight: 600;">CONTACT</div>
                        <div class="contact_form-detail">
                           <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                           <span class="phone">
                              {{BookingInfo['mobile_number']}}
                           </span><br />
                           <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                           <span class="email">&nbsp;
                              {{BookingInfo['email_id']}}
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="payment-title ">
               <h5> Fare Summary</h5>
            </div>

            <div class="card">
               <div class="card-body payment_farebrkup">
                  <div class="d-flex justify-content-between  align-items-center" ng-repeat="breakup in FareInfoArray.FareBreakup">
                     <h5>{{breakup.Label}}</h5>
                     <b>
                        <?php echo $CurrencySymbol; ?> {{breakup.Value}}
                     </b>
                  </div>
               </div>
            </div>

         </div>
         <!-- Tourguide Booking Summary -->
      </div>
   </div>
</section>

<!-- 
<style>
   .nav-pills .nav-link.active {
      border-left: 2px solid #c92e2a;
      color: #c92e2a;
      border-radius: 0;
      background: transparent;
   }

   .nav-pills .nav-link {
      background: 0 0;
      border-radius: 0;
      width: 200px;
      text-align: left;
      color: #000;
      font-size: 14px;
      font-weight: 500;
      border-right: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      padding: 15px;
   }

   #bookingCounter {
      display: inline-block;
      height: 56px;
      background: #000;
      text-align: center;
      width: 100%;
      z-index: 999999;
      position: fixed;
      line-height: 56px;
      left: 0;
      bottom: 0;
      color: #fff;
      font-size: 16px;
   }

   .tab-content>.tab-pane.active .active-border {
      border: 1px solid black !important;
   }
</style> -->


<script>
   var app = angular.module('paymentApp', []);
   app.controller('paymentCtrl', function($scope, $http) {

       

      $scope.BookingInfo = <?= json_encode($booking_data); ?>;
      /*  console.log($scope.BookingInfo); */
      $scope.PaymentModes = <?= json_encode($PaymentModes); ?>;
      $scope.TotalPrice = <?= $total_price; ?>;
      $scope.TotalPriceWithCFEE = <?= $total_price; ?>;
      $scope.currency_rate = <?php echo $currency_rate; ?>;
      $scope.Service = "<?= $service; ?>";
      $scope.bookingID = <?= json_encode($booking_id); ?>;
      $scope.SearchToken = "<?= $search_token_id; ?>";
      $scope.activeTabIndex = 0;
      $scope.imageURL = "<?= root_url . 'uploads/'; ?>";
      $scope.gatewayURL = $scope.imageURL + 'payment-gateway/';
      let siteURL = "<?= site_url('payment/proceed-payment'); ?>";
      $scope.paymentURL = siteURL;
      $scope.CFEE = 0;
      $scope.GatewayRemark = "";
      $scope.isButtonDisabled = true;
      $scope.activeIndex = -1;
      $scope.FareInfoArray = {};

 

      if ($scope.Service === 'flight') {
         funcFlightBreakup();
      } else if ($scope.Service === 'hotel') {
         funcHotelBreakup();
      } else if ($scope.Service === 'bus') {
         funcBusBreakup();
      } else {
         console.log("called");
         funcHolidayBreakup();
      }



      $scope.toggleMode = function(price, index) {
         $scope.activeTabIndex = index;
         $scope.activeIndex = -1;
         $scope.CFEE = 0;
         $scope.GatewayRemark = "";
         $scope.TotalPriceWithCFEE = $scope.TotalPrice;
         $scope.isButtonDisabled = true;
         if ($scope.Service === 'flight') {

            funcFlightBreakup();
         } else if ($scope.Service === 'hotel') {
            funcHotelBreakup();
         } else if ($scope.Service === 'bus') {
            funcBusBreakup();
         } else {
            funcHolidayBreakup();
         }

      }

      $scope.ucFirst = function(string) {
         if (!string) return string;
         return string.charAt(0).toUpperCase() + string.slice(1);
      }

      $scope.calculateCFEE = function(Mode, SubMode, index, CardType = undefined, CreditCardType = undefined) {
         let token = {};
         if (CardType != undefined) {
            $scope.GatewayRemark = CardType.Remark;
            if (CardType.ValueType === 'fixed') {
               $scope.CFEE = parseFloat(CardType.Value);
            } else if (CardType.ValueType === 'percentage') {
               $scope.CFEE = parseFloat((CardType.Value * $scope.TotalPrice / 100).toFixed(2));
            }
            
            $scope.CFEE = $scope.CFEE * $scope.currency_rate;
            
            $scope.TotalPriceWithCFEE = parseFloat(($scope.TotalPrice + $scope.CFEE).toFixed(2));
            token = {
               "mode": Mode,
               "gateway": SubMode.Gateway,
               "cfee": $scope.CFEE,
               "type": CreditCardType,
               "service": $scope.Service,
               "id": $scope.bookingID,
               "fare": $scope.TotalPrice,
               "search_token_id": $scope.SearchToken,
            };
         } else {
            $scope.GatewayRemark = SubMode.Remark;
            if (SubMode.ValueType === 'fixed') {
               $scope.CFEE = parseFloat(SubMode.Value);
            } else if (SubMode.ValueType === 'percentage') {
               $scope.CFEE = parseFloat((SubMode.Value * $scope.TotalPrice / 100).toFixed(2));
            }
         
            $scope.CFEE = $scope.CFEE * $scope.currency_rate;
            
            
            $scope.TotalPriceWithCFEE = parseFloat(($scope.TotalPrice + $scope.CFEE).toFixed(2));



            token = {
               "mode": Mode,
               "gateway": SubMode.Gateway,
               "cfee": $scope.CFEE,
               "service": $scope.Service,
               "id": $scope.bookingID,
               "fare": $scope.TotalPrice,
               "search_token_id": $scope.SearchToken,
            };
         }

         if ($scope.Service === 'flight') {
            funcFlightBreakup();
         } else if ($scope.Service === 'hotel') {
            funcHotelBreakup();
         } else if ($scope.Service === 'bus') {
            funcBusBreakup();
         } else {
            funcHolidayBreakup();
         }


         $scope.activeIndex = index;
         $scope.isButtonDisabled = false;
         token = btoa(JSON.stringify(token));
         /*  console.log(token);return false; */

         $scope.paymentURL = siteURL + '/' + token;
      }

      $scope.jsonParse = function(jsonString) {
         if (!jsonString) return jsonString;
         return JSON.parse(jsonString);
      }

      $scope.getFlightDate = function(str) {
         const [dt, tm] = str.split('T');
         return new Date(dt).toLocaleDateString('en-US', {
            day: '2-digit',
            month: 'short'
         });
      }

      $scope.getFlightTime = function(str) {
         const [dt, tm] = str.split('T');
         return tm;
      }

      $scope.convertToHoursMinsfromMinDuration = function(minutes) {
         const hours = Math.floor(minutes / 60);
         const mins = minutes % 60;
         return hours + ' h ' + mins + ' m';
      }

      $scope.dateIndianFormat = function(str) {
         var date = new Date(str);
         var options = {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
         };
         return date.toLocaleDateString('en-US', options);
      }


      function decimalPrice(price) {
         price = parseFloat(price);
         return Math.round(parseFloat((price).toFixed(2)) * 100) / 100;
      }



      function funcFlightBreakup() {
         $scope.FareInfoArray = {};
         let TotalPayAmount = 0;
         Object.keys($scope.BookingInfo).forEach(key => {
            const info = $scope.BookingInfo[key];
            let rtype = info.trip_indicator == '1' ? 'OB' : 'IB';
            let fareBreakupArray = JSON.parse(info.customer_fare_break_up); 
            let discount = decimalPrice(fareBreakupArray['Discount']) + decimalPrice(fareBreakupArray['AgentCommission']);

            let FareBreakUp = {
               'BaseFare': {
                  'Label': 'Base Fare',
                  'Value': fareBreakupArray.BaseFare
               },
               'Taxes': {
                  'Label': 'Taxes',
                  'Value': decimalPrice(fareBreakupArray.Tax + fareBreakupArray.OtherCharges)
               },
               'ServiceCharge': {
                  'Label': 'Service Charges',
                  'Value': fareBreakupArray.ServiceCharges
               },
               'MealCharge': {
                  'Label': 'Meal Charges',
                  'Value': fareBreakupArray.TotalMealCharges
               },
               'BaggageCharge': {
                  'Label': 'Baggage Charges',
                  'Value': fareBreakupArray.TotalBaggageCharges
               },
               'MealCharge': {
                  'Label': 'Meal Charges',
                  'Value': fareBreakupArray.TotalMealCharges
               },
               'SeatCharge': {
                  'Label': 'seat Charges',
                  'Value': fareBreakupArray.TotalSeatCharges
               },
               'GST': {
                  'Label': 'GST (+)',
                  'Value': fareBreakupArray['GST']['CGSTAmount'] + fareBreakupArray['GST']['SGSTAmount'] + fareBreakupArray['GST']['IGSTAmount']
               },
            };

            if (discount > 0) {
               FareBreakUp['Discount'] = {
                  'Label': 'Discount(-)',
                  'Value': discount
               };
            }
            if (fareBreakupArray.hasOwnProperty('couponAmount') && fareBreakupArray.couponAmount > 0) {
               FareBreakUp['Coupon'] = {
                  'Label': 'Promocode Discount (-)',
                  'Value': fareBreakupArray['couponAmount']
               };
            }


            let PayAmount = 0;

            PayAmount += fareBreakupArray['PublishedPrice'] + fareBreakupArray.TotalMealCharges + fareBreakupArray.TotalBaggageCharges + fareBreakupArray.TotalSeatCharges - discount - fareBreakupArray['couponAmount'];

            FareBreakUp['Total'] = {
               'Label': 'Total Amount',
               'Value': PayAmount
            };

            TotalPayAmount += PayAmount;

            $scope.FareInfoArray[rtype] = {
               FareBreakup: FareBreakUp
            };

         });
         
      

         if ($scope.CFEE > 0) {
            $scope.FareInfoArray['CFEE'] = {
               'Label': 'Convenience Fee(+)',
               'Value': decimalPrice($scope.CFEE)
            }
         }
          
         
         $scope.FareInfoArray['Total'] = {
            'Label': 'Pay Amount',
            'Value': decimalPrice($scope.CFEE + TotalPayAmount)
         }
         
      }

      function funcHotelBreakup() {
         const customerBreakup = JSON.parse($scope.BookingInfo.customer_fare_break_up);
         const roomInfo = JSON.parse($scope.BookingInfo.hotel_rooms_details);
         let basePrice = tax = serviceCharges = gst = publishedFare = discount = 0;
         Object.keys(roomInfo).forEach(key => {
            const roomPriceDetail = customerBreakup[key];
            basePrice += roomPriceDetail['RoomPrice'];
            tax += (roomPriceDetail['Tax'] + roomPriceDetail['OtherCharges']);
            serviceCharges += roomPriceDetail['ServiceCharges'];
            gst += roomPriceDetail['GST']['CGSTAmount'] + roomPriceDetail['GST']['IGSTAmount'] + roomPriceDetail['GST']['SGSTAmount'];
            discount += decimalPrice(roomPriceDetail['Discount']) + decimalPrice(roomPriceDetail['AgentCommission']);
            publishedFare += roomPriceDetail['PublishedPrice'];
         });


         let FareBreakUp = {
            'BaseFare': {
               'Label': 'Base Fare',
               'Value': basePrice
            },
            'Taxes': {
               'Label': 'Taxes',
               'Value': decimalPrice(tax)
            },
            'ServiceCharge': {
               'Label': 'Service Charges',
               'Value': serviceCharges
            },
            'GST': {
               'Label': 'GST (+)',
               'Value': decimalPrice(gst)
            },

         };

         if (discount > 0) {
            FareBreakUp['Discount'] = {
               'Label': 'Discount(-)',
               'Value': discount
            };
         }
         if (customerBreakup.hasOwnProperty('couponAmount') && customerBreakup.couponAmount > 0) {
            FareBreakUp['Coupon'] = {
               'Label': 'Promocode Discount (-)',
               'Value': customerBreakup['couponAmount']
            };
         }
         if ($scope.CFEE > 0) {
            FareBreakUp['CFEE'] = {
               'Label': 'Convenience Fee(+)',
               'Value': decimalPrice($scope.CFEE)
            };
         }

         let PayAmount = 0;
         PayAmount += publishedFare - discount - customerBreakup['couponAmount'] + $scope.CFEE;
         FareBreakUp['Total'] = {
            'Label': 'Pay Amount',
            'Value': decimalPrice(PayAmount)
         };

         $scope.FareInfoArray = {
            FareBreakup: FareBreakUp
         };
      }

      function funcBusBreakup() {
         const customerBreakup = JSON.parse($scope.BookingInfo.customer_fare_break_up);

         let basePrice = tax = serviceCharges = gst = publishedFare = discount = 0;
         customerBreakup.forEach(breakup => {
            basePrice += breakup['BasePrice'];
            tax += decimalPrice(breakup['Tax'] + breakup['OtherCharges']);
            serviceCharges += breakup['ServiceCharges'];
            gst = breakup['GST']['CGSTAmount'] + breakup['GST']['IGSTAmount'] + breakup['GST']['SGSTAmount'];
            discount += decimalPrice(breakup['Discount']) + decimalPrice(breakup['AgentCommission']);
            publishedFare += breakup['PublishedPrice'];
         });

         let FareBreakUp = {
            'BaseFare': {
               'Label': 'Base Fare',
               'Value': basePrice
            },
            'Taxes': {
               'Label': 'Taxes',
               'Value': decimalPrice(tax)
            },
            'ServiceCharge': {
               'Label': 'Service Charges',
               'Value': serviceCharges
            },
            'GST': {
               'Label': 'GST (+)',
               'Value': decimalPrice(gst)
            },
         };

         if (discount > 0) {
            FareBreakUp['Discount'] = {
               'Label': 'Discount(-)',
               'Value': discount
            };
         }
         let couponAmount = 0;
         if ($scope.BookingInfo.hasOwnProperty('coupon_info')) {
            const couponInfo = JSON.parse($scope.BookingInfo['coupon_info']);
            couponAmount = couponInfo['couponAmount'] !== undefined ? couponInfo['couponAmount'] : 0;
            if (couponAmount > 0) {
               FareBreakUp['Coupon'] = {
                  'Label': 'Promocode Discount (-)',
                  'Value': couponAmount
               };
            }
         }

         if ($scope.CFEE > 0) {
            FareBreakUp['CFEE'] = {
               'Label': 'Convenience Fee(+)',
               'Value': decimalPrice($scope.CFEE)
            };
         }

         let PayAmount = 0;

         PayAmount += publishedFare - discount - couponAmount + $scope.CFEE;
         FareBreakUp['Total'] = {
            'Label': 'Pay Amount',
            'Value': decimalPrice(PayAmount)
         };

         $scope.FareInfoArray = {
            FareBreakup: FareBreakUp
         };
      }

      function funcHolidayBreakup() {
         const customerBreakup = JSON.parse($scope.BookingInfo['customer_fare_break_up']);
         let gst = customerBreakup['GST']['IGSTAmount'] + customerBreakup['GST']['SGSTAmount'] + customerBreakup['GST']['CGSTAmount'];
         let discount = decimalPrice(customerBreakup['Discount']) + decimalPrice(customerBreakup['AgentCommission']);;

         let basePrice = 0;
         if (customerBreakup.hasOwnProperty('BasePrice')) {
            basePrice = customerBreakup['BasePrice'];
         } else {
            basePrice = customerBreakup['BaseFare'];
         }

         let FareBreakUp = {
            'BaseFare': {
               'Label': 'Base Fare',
               'Value': basePrice
            },
            'Taxes': {
               'Label': 'Taxes',
               'Value': customerBreakup['Tax']
            },
            'ServiceCharge': {
               'Label': 'Service Charges',
               'Value': customerBreakup['ServiceCharges']
            },
            'GST': {
               'Label': 'GST (+)',
               'Value': decimalPrice(gst)
            },
         };

         if (discount > 0) {
            FareBreakUp['Discount'] = {
               'Label': 'Discount(-)',
               'Value': discount
            };
         }
         let couponAmount = 0;
         if ($scope.BookingInfo.hasOwnProperty('coupon_info')) {
            let couponInfo = JSON.parse($scope.BookingInfo['coupon_info']);
            if (couponInfo.hasOwnProperty('couponAmount')) {
               couponAmount = couponInfo['couponAmount'];
               if (couponAmount > 0) {
                  FareBreakUp['Coupon'] = {
                     'Label': 'Promocode Discount (-)',
                     'Value': couponAmount
                  };
               }
            }
         }

         if ($scope.CFEE > 0) {
            FareBreakUp['CFEE'] = {
               'Label': 'Convenience Fee(+)',
               'Value': decimalPrice($scope.CFEE)
            };
         }

         let PayAmount = 0;
         PayAmount += customerBreakup['OfferedPrice'] + customerBreakup['TDS'] - couponAmount + $scope.CFEE;
         FareBreakUp['Total'] = {
            'Label': 'Pay Amount',
            'Value': decimalPrice(PayAmount)
         };

         $scope.FareInfoArray = {
            FareBreakup: FareBreakUp
         };

      }
   });

   app.filter('decimalPriceabhay', function() {
      return function(price) {
         if (price) {
            price = parseFloat(price);
            return Math.round(parseFloat(price.toFixed(2)) * 100) / 100;
         }
         return price;
      };
   });
</script>