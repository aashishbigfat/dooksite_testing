<main ng-app="flightApp" ng-controller="flightCtrl" scroll>
   <div class="container" ng-if="loading==true">
      <?php echo view('Modules/Flight/Views\FlightBookingtemplate\loading-page.php'); ?>
   </div>
   <!-- Breadcrumb --
   <div class="breadcrumb-bar breadcrumb-bg-05 text-center" ng-if="loading==false">
      <div class="container">
         <div class="row">
            <div class="col-md-12 col-12">
               <h2 class="breadcrumb-title mb-2">Flight</h2>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb justify-content-center mb-0">
                     <li class="breadcrumb-item"><a href="<?php echo site_url('/'); ?>"><i class="fa-solid fa-home"></i></a></li>
                     <li class="breadcrumb-item">Flight</li>
                     <li class="breadcrumb-item active" aria-current="page">Flight Lists</li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>
   <!-- /Breadcrumb -->
   <div class="content ng-cloak" ng-if="loading==false">
      <div class="container">
         <?php echo view('Modules/Flight/Views/FlightBookingtemplate\modify_search.php'); ?>
         <div class="row">
            <!-- filter starts here -->
            <div class="col-xl-3 col-lg-3 theiaStickySidebar" ng-if="ErrorCode==0">
               <?php echo view('Modules/Flight/Views/FlightBookingtemplate\flight_filter.php'); ?>
            </div>
            <!-- filter ends here -->
            <!-- oneway result starts here -->
            <div class="col-xl-9 col-lg-9" ng-if="ErrorCode==0">
               <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <h6 class="mb-3">Showing Result {{FilteredResultCount}} of {{TotalResult}} Flights</h6>
               </div>
               <!-- <div class="login-bg-info p-3 mb-3">
                  <div class="d-flex align-items-center justify-content-between flex-wrap">
                     <p class="fs-14 fw-medium mb-0 d-inline-flex align-items-center"><i class="fa fa-info-circle me-2"></i>Save more on flights! Sign in to unlock up to 15% off on thousands of destinations.</p>
                     <a href="javascript:void(0);" class="btn btn-white btn-sm" view-data-modal="B5-Login" data-controller='login' data-id="" data-href="<?php echo site_url('login/login-modal/'); ?>">Sign In</a>
                  </div>
               </div> -->
               <div class="sorting-list mb-3">
                  <div class="row">
                     <div class="col-lg-9">
                        <div class="row">
                           <div class="col-lg-4">
                              <a href="javascript:void(0);" ng-click="orderby('AirlineName')">Airlines
                                 <span class="fa" ng-show="field === 'AirlineName'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                              </a>
                           </div>
                           <div class="col-lg-8">
                              <div class="d-flex align-items-center justify-content-between">
                                 <div>
                                    <a href="javascript:void(0);" ng-click="orderby('DepartTime')">Depart
                                       <span class="fa" ng-show="field === 'DepartTime'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                    </a>
                                 </div>
                                 <div class="text-center">
                                    <a href="javascript:void(0);" ng-click="orderby('DurationMin')">Duration
                                       <span class="fa" ng-show="field === 'DurationMin'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                    </a>
                                 </div>
                                 <div>
                                    <a href="javascript:void(0);" ng-click="orderby('ArrivalTime')">Arrive
                                       <span class="fa" ng-show="field === 'ArrivalTime'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-3">
                        <div class="row">
                           <div class="col-md-12">
                              <a href="javascript:void(0);" ng-click="orderby('Fare')">Price
                                 <span class="fa" ng-show="field === 'Fare'" ng-class="(reverse) ? 'fa-sort-up' : 'fa-sort-down'"></span>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- flight booking item new code -->
               <div class="row">
                  <div class="col-lg-12">
                     <div class="flight-booking-item" ng-repeat="(flightkey,flightitem) in Results|orderBy:field:reverse |limitTo:limit">
                        <div class="flight-booking-wrapper row">
                           <div class="col-lg-9">
                              <div class="flight-booking-info" ng-repeat="(tripskey,trip) in flightitem.MainSegment" ng-class="{'flight-booking-return': tripskey > 0}">
                                 <div class="flight-booking-content row">
                                    <div class="flight-booking-airline col-lg-3">
                                       <div class="flight-airline-img">
                                          <img ng-src="{{'<?php echo site_url('uploads/airline-images/'); ?>' + trip.Airlinecode.trim() + '.png' }}" alt="{{ trip.AirlineName }}">
                                       </div>
                                       <h5 class="flight-airline-name">
                                          {{trip.AirlineName}}
                                          <span class="flight-holdid" ng-if="trip.AirlineCodeFlightNumberString!=''">
                                             <span class="flightids d-block">{{trip.AirlineCodeFlightNumberString}}</span>
                                          </span>
                                       </h5>
                                    </div>
                                    <div class="flight-booking-time col-lg-9">
                                       <div class="start-time">
                                          <div class="start-time-icon">
                                             <i class="fal fa-plane-departure"></i>
                                          </div>
                                          <div class="start-time-info">
                                             <h6 class="start-time-text">{{trip.DepartTime}}</h6>
                                             <span class="flight-destination">{{trip.DepartureCity}}</span>
                                             <h6 class="start-Depart-text">{{trip.DepartDate}}</h6>
                                          </div>
                                       </div>
                                       <div class="flight-stop">
                                          <span class="flight-stop-number" ng-if="trip.Stops>0">{{trip.Stops}} Stop(s)</span>
                                          <span class="flight-stop-number" ng-if="trip.Stops==0">Non Stop(s)</span>
                                          <div class="flight-stop-arrow"></div>
                                          <div class="flight-booking-duration">
                                             <span class="duration-text">{{trip.Duration}}</span>
                                          </div>
                                       </div>
                                       <div class="end-time">
                                          <div class="start-time-icon">
                                             <i class="fal fa-plane-arrival"></i>
                                          </div>
                                          <div class="start-time-info">
                                             <h6 class="end-time-text">{{trip.ArrivalTime}}</h6>
                                             <span class="flight-destination">{{trip.ArrivalCity}}</span>
                                             <h6 class="start-Depart-text">{{trip.ArrivalDate}}</h6>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="flight-indicator-content mt-3">
                                    <div>
                                       <span class="flightarrives-after" ng-if="trip.ArrivalDays!=0"> <i class="fa fa-plane-departure flightarrive-icons"></i> Flight Arrives after {{trip.ArrivalDays}} Day(s) </span>
                                       <span class="handbag-icons handbag-icons-positionHandle" style="color:#b50000;" ng-if="trip.Seats!=0 && trip.Seats!=''"> Seats left:
                                          <samp id="seat_left_{{flightitem.TtsIndex}}{{tripskey}}">{{trip.Seats}}</samp>
                                       </span>
                                    </div>
                                    <!-- <div id="airline_remark_{{flightitem.TtsIndex}}">
                                       <span ng-if="flightitem.AirlineRemark!=''">Airline Remark : {{flightitem.AirlineRemark}} </span>
                                    </div> -->
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-3">
                              <div class="flight-booking-price">
                                 <div class="price-info">
                                    <div class="flight-radiolist flight-farelist_{{flightitem.TtsIndex}}">
                                       <div class="flight-leftresult" ng-repeat="(farelistkey,farelist) in flightitem.FareList" ng-class="{'d-none' : farelistkey >= fareshowlimit}">
                                          <div class="form-check">
                                             <input class="form-check-input me-2" type="radio" id="fare_option_{{flightitem.TtsIndex}}_{{farelistkey}}" name="search_result_{{flightitem.TtsIndex}}" ng-if="farelistkey==0" value="{{farelistkey}}" ng-click="fareoOptionselected(flightitem.TtsIndex)" checked  style   = "display:none;">

                                             <label class="form-check-label" for="fare_option_{{flightitem.TtsIndex}}_{{farelistkey}}">
                                                <span class="price-amount">{{CurrencySymbol}} {{farelist.Fare.OfferedPrice}}
                                                   <span class="badge bg-warning text-dark badge-sm">{{farelist.FareType}}</span>
                                                </span>
                                                <span class="price-amount text-success" ng-if="shownetfare">{{CurrencySymbol}} {{farelist.Fare.OfferedPrice}}</span>
                                             </label>
                                             <ul class="list-unstyled mb-0">
                                                <li>
                                                   <span class="ars-refunsleft ars-lastre">
                                                      <span class="d-lg-block">{{farelist.CabinClass}},</span>
                                                      <span class="cursor-pointer tts-refundable" data-bs-toggle="tooltip" data-bs-placement="top" title="Refundable" ng-if="farelist.IsRefundable==true">Refundable</span>
                                                      <span class="cursor-pointer tts-non-refundable" data-bs-toggle="tooltip" data-bs-placement="top" title="Non Refundable" ng-if="farelist.IsRefundable!=true">Non Refundable</span>
                                                      <span ng-if="farelist.Source" class="ars-refunsleft ars-lastre">{{farelist.Source}}</span>
                                                   </span>
                                                </li>
                                             </ul>
                                             <!-- <hr ng-if="flightitem.FareList.length!=(farelistkey+1)"> -->
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <button href="javascript:void(0);" class="btn btn-primary theme-btn" ng-click="getConfirmationInfo(flightitem.TtsIndex,'confirm',$event)" ng-if="flightitem['FareList'].length ==fareshowlimit" name="search_result_{{flightitem.TtsIndex}}" value="{{0}}">Book Now <i class="far fa-arrow-right"></i></button>
                                 <button href="javascript:void(0);" class="btn btn-primary theme-btn" ng-click="getOtherFare(flightitem.TtsIndex)" ng-if="flightitem['FareList'].length > fareshowlimit">View Prices <i class="far fa-arrow-right"></i></button>
                              </div>
                           </div>
                        </div>
                        <div class="flight-booking-detail">
                           <div class="flight-booking-detail-header">
                              <a href="#flight-booking-collapse2-{{flightitem.TtsIndex}}" class="btn flight-viewbtn flight-viewbtn_harish flight-viewbtn_harish_{{flightitem.TtsIndex}}" ng-click="getFlightDetail(flightitem.TtsIndex)" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="#flight-booking-collapse2-{{flightitem.TtsIndex}}">
                                 Flight Details <i class="far fa-angle-down"></i>
                              </a>
                           </div>
                           <?php echo view('Modules/Flight/Views/FlightBookingtemplate\flight_result_detail_page.php'); ?>
                        </div>
                     </div>
                  </div>
               </div>
               <!-----Filter No Flight ------------>
               <div class="no-flight tts-api-error-msg mt-4" ng-if="FliterNoFlight">
                  <p class="mt-4">No flight found that matches your filter criteria.Reset your filter </p>
                  <button type="button" class="btn btn-primary btn-sm" ng-click="clearFilterAll(0,'all')">
                     Reset All Filter
                  </button>
               </div>
               <!-----Filter No Flight ------------>
               <!-- flight booking item new code end-->

            </div>
            <!-- oneway result ends here -->
            <div class="col-sm-12 mt-3" ng-if="ErrorCode!=0">
               <div class="tts-api-error-msg">
                  <img src="<?php echo site_url('webroot/img/no-flight-found.png'); ?>" width="25%">
                  <h5 class="mt-4"> {{errormessage}}</h5>
                  <p class="mb-2">Sorry,No flights found on this route for the requested date. Please change your
                     dates / search criteria.
                  </p>
                  <a href="<?php echo site_url('flight'); ?>" class="btn btn-outline-danger">PICK ANOTHER DATE</a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!------Start Flight Confirmation modal------>
   <div class="modal fade" id="FlightConfirmationModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content modal_content">
            <div class="modal-header modal_header">
               <h5 class="modal-title" ng-if="fareConfrimationLoading==true  || FareConfirmationPriceChangeTitle==''">
                  Confirming Your
                  Flight
               </h5>
               <h5 class="modal-title" ng-if="fareConfrimationLoading==false && FareConfirmationPriceChangeTitle!=''">
                  {{FareConfirmationPriceChangeTitle}}
               </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="hotel-main">
                  <!----- start loading ---->
                  <div class="row" ng-if="fareConfrimationLoading==true">
                     <div class="col-md-12">
                        <div class="text-center">
                           <div class="loader mt-3 mb-3">
                              <div role="status" class="spinner-grow text-primary">
                                 <span class="sr-only">Loading...</span>
                              </div>
                              <div role="status" class="spinner-grow text-secondary">
                                 <span class="sr-only">Loading...</span>
                              </div>
                              <div role="status" class="spinner-grow text-danger">
                                 <span class="sr-only">Loading...</span>
                              </div>
                              <div role="status" class="spinner-grow text-dark">
                                 <span class="sr-only">Loading...</span>
                              </div>
                           </div>
                           <h5> Please Wait... </h5>
                        </div>
                     </div>
                  </div>
                  <!----- end loading -->
                  <div class="row" ng-if="fareConfrimationLoading==false">
                     <div class="col-md-12" ng-if="FareConfrimationErrorCode!=0">
                        <div class="text-center">
                           <h5 class="mt-4"> {{FareConfrimationmessage}}</h5>
                        </div>
                     </div>
                     <div class="col-md-12" ng-if="FareConfrimationErrorCode==0 && GetConfirmShowBox==1">
                        <div class="text-center" id="fareConfirmationpricechangeinfo">
                           {{FareConfirmationPriceChangeInfo|html_filter:"fareConfirmationpricechangeinfo"}}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade" id="FlightFareListModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

      <div class="modal-dialog modal-xl modal-dialog-centered">

         <div class="modal-content modal_content">

            <div class="modal-header modal_header">

               <h5 class="modal-title" ng-if="fareList==true  || fareList==''">

                  Fare List

               </h5>

               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

               <?php echo view('Modules/Flight/Views/FlightBookingtemplate\flight_fare_list.php'); ?>

            </div>

         </div>

      </div>

   </div>
</main>
<!-- flight oneway result page ends here -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
   let url = "<?php echo site_url(); ?>"
   let limitStep = 20;
   var app = angular.module('flightApp', []);
   app.controller('flightCtrl', function($scope, $http) {
      $scope.fareshowlimit = 1;
      $scope.loading = true;
      $scope.fareRuleLoading = true;
      $scope.fareConfrimationLoading = true;
      $scope.topsortingtext = 'Price - Low to High';
      $scope.shownetfare = false;
      $scope.FliterNoFlight = false;
      $scope.FlightSegment = [];
      $scope.FlightFareDetail = [];
      $scope.FlightFareRule = [];
      $scope.FlightBaggageInfo = [];
      $scope.FlightSegmentPreview = [];
      $scope.CurrencySymbol = "";
      $scope.CurrencyCode = "";
      $http({
         method: "GET",
         url: url + "flight/flight-result?" + "<?php echo http_build_query($_GET); ?>"
      }).then(function mySuccess(response) {
         $scope.loading = false;
         $scope.ErrorCode = response.data.Error.ErrorCode;
         $scope.ErrorMessage = response.data.Error.ErrorMessage;
         if ($scope.ErrorCode == 0) {
            $scope.CurrencySymbol = response.data.CurrencySymbol;
            $scope.CurrencyCode = response.data.CurrencyCode;
            $scope.Results = response.data.Result[0];
            $scope.FilterResults = response.data.Result[0];
            $scope.limit = limitStep;
            $scope.FilteredResultCount = $scope.Results.length;
            $scope.token = response.data.SearchTokenId
            $scope.TotalResult = response.data.TotalResults;
            $scope.airlineLogoClass = response.data.airlineLogoClass;
            $scope.FlightFilter = response.data.Filter;
            setTimeout(function() {
               $scope.Priceslider();
            }, 100);
         } else {
            $scope.errormessage = $scope.ErrorMessage;
         }
      }, function myError(response) {
         $scope.errormessage = "Something went wrong";
      });
      $scope.showFareDetail = false;
      $scope.showTTsIndexFareDetail = "";
      $scope.hidFlightDetail = function(ttskey) {
         angular.element($('#id-' + ttskey).hide());
         let viewDetailButton = document.querySelector('.flight-viewbtn_harish_' + ttskey + ' i');
         if (viewDetailButton.classList.contains('fa-minus')) {
            viewDetailButton.classList.remove('fa-minus');
            viewDetailButton.classList.add('fa-plus');
         }
         $scope.showFareDetail = false;
         $scope.showTTsIndexFareDetail = "";
      }
      $scope.getFlightDetail = function(ttskey) {
         $scope.fareRuleLoading = true;
         let checkbox = document.getElementsByName('search_result_' + ttskey);
         let ln = 0;
         let selindex;
         for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked) {
               selindex = checkbox[i].value;
            }
         }
         if (selindex) {
            var flightInfo = $scope.Results.filter(function(flightItem) {
               return flightItem.TtsIndex == ttskey;
            })[0];
            $scope.FlightSegment = flightInfo.Segments
            $scope.FlightFareDetail = flightInfo.FareList[selindex];
            $scope.FlightBaggageInfo = flightInfo.FareList[selindex].SeatBaggage;
            var FareRuleData = {};
            FareRuleData.token = $scope.token;
            FareRuleData.FareId = flightInfo.FareList[selindex].FareId;
            $http({
               method: "POST",
               url: url + 'flight/fare-rule',
               headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
               },
               data: $.param(FareRuleData)
            }).then(function mySuccess(fareruleresponse) {
               $scope.fareRuleLoading = false;
               $scope.FareRuleErrorCode = fareruleresponse.data.Error.ErrorCode;
               $scope.fareruleerrormessage = fareruleresponse.data.Error.ErrorMessage;
               if ($scope.FareRuleErrorCode == 0) {
                  $scope.FlightFareRule = fareruleresponse.data.Result;
               }
            }, function myError(fareruleresponse) {
               $scope.fareRuleLoading = false;
               $scope.fareruleerrormessage = "Something went wrong";
            });
         }
         angular.element($(".flight-detail").hide());
         angular.element($(".flight-viewbtn_harish i").removeClass('fa-plus fa-minus'));
         angular.element($(".flight-viewbtn_harish i").addClass('fa-plus'));
         angular.element($("#airline_remark_" + ttskey).html('<span> Airline Remark : ' + flightInfo.FareList[
            selindex].AirlineRemark + '</span>'));
         for (seats = 0; seats < $scope.FlightBaggageInfo.length; seats++) {
            angular.element($("#seat_left_" + ttskey + seats).text($scope.FlightBaggageInfo[seats][0]
               .NoOfSeatAvailable));
         }
         let viewDetailButton = document.querySelector('.flight-viewbtn_harish_' + ttskey + ' i');
         if (viewDetailButton.classList.contains('fa-plus')) {
            viewDetailButton.classList.remove('fa-plus');
            viewDetailButton.classList.add('fa-minus');
         }
         angular.element($('#id-' + ttskey).show());
         $scope.showFareDetail = true;
         $scope.showTTsIndexFareDetail = ttskey;
      }
      $scope.fareoOptionselected = function(ttskey) {
         let checkbox = document.getElementsByName('search_result_' + ttskey);
         let ln = 0;
         let selindex;
         for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked) {
               selindex = checkbox[i].value;
            }
         }
         if (selindex) {
            var flightInfo = $scope.Results.filter(function(flightItem) {
               return flightItem.TtsIndex == ttskey;
            })[0];
            let FlightBaggageInfo = flightInfo.FareList[selindex].SeatBaggage;
            angular.element($("#airline_remark_" + ttskey).html('<span> Airline Remark :' + flightInfo
               .FareList[selindex].AirlineRemark + '</span>'));
            for (seats = 0; seats < FlightBaggageInfo.length; ++seats) {
               angular.element($("#seat_left_" + ttskey + seats).text(FlightBaggageInfo[seats][0]
                  .NoOfSeatAvailable));
            }
            if ($scope.showFareDetail == true && $scope.showTTsIndexFareDetail == ttskey) {
               $scope.getFlightDetail(ttskey);
            }
         }
      }
      $scope.removefareHideclass = function(event, ttsindex) {
         event.target.classList.toggle('flight__dropdown__icon--selected');
         let limit = event.target.getAttribute('data-limit');
         let uldata = document.querySelector('.flight-farelist_' + ttsindex);
         for (var i = 0; i < uldata.children.length; ++i) {
            var item = uldata.children.item(i);
            if (i >= limit) {
               if (item.classList.contains('d-none')) {
                  item.classList.remove('d-none');
               } else {
                  item.classList.add('d-none');
               }
            }
         }
      }
      $scope.getConfirmationInfo = function(selertedfarettsIndexKey, confirmOrPreview,$event) {
         $scope.fareConfrimationLoading = true;
        /*  let checkbox = document.getElementsByName('search_result_' + selertedfarettsIndexKey);
         let ln = 0;
         let selindex;
         for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked) {
               selindex = checkbox[i].value;
            }
         } */
         let selindex = event.target.value;
         if (selindex) {
            var flightInfo = $scope.Results.filter(function(flightItem) {
               return flightItem.TtsIndex == selertedfarettsIndexKey;
            })[0];
            var FareConfirmationData = {};
            FareConfirmationData.token = $scope.token;
            FareConfirmationData.FareId = flightInfo.FareList[selindex].FareId;
            FareConfirmationData.TtsResultIndexkey = selertedfarettsIndexKey;
            FareConfirmationData.FareListOptionkey = selindex;
            var FlightConfirmationModel = document.getElementById("FlightConfirmationModel");
            if (FlightConfirmationModel !== null) {
               new bootstrap.Modal(FlightConfirmationModel).show();
            }
            $http({
               method: "POST",
               url: url + 'flight/fare-confirmation',
               headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
               },
               data: $.param(FareConfirmationData)
            }).then(function mySuccess(fareconfrimationresponse) {
               $scope.fareConfrimationLoading = false;
               $scope.FareConfrimationErrorCode = fareconfrimationresponse.data.Error.ErrorCode;
               $scope.FareConfrimationmessage = fareconfrimationresponse.data.Error.ErrorMessage;
               $scope.FareConfirmationPriceChangeInfo = '';
               $scope.FareConfirmationPriceChangeTitle = '';
               if ($scope.FareConfrimationErrorCode == 0) {
                  $scope.FareConfirmationPriceChangeInfo = fareconfrimationresponse.data.PriceChange;
                  $scope.GetConfirmShowBox = fareconfrimationresponse.data.GetConfirmShowBox;
                  $scope.FareConfirmationPriceChangeTitle = fareconfrimationresponse.data.Title;
                  RedirectUrl = fareconfrimationresponse.data.RedirectUrl;
                  if ($scope.GetConfirmShowBox == 0) {
                     angular.element($("#FlightConfirmationModel").hide());
                     window.location.href = RedirectUrl;
                  }
               }
            }, function myError(fareconfrimationresponse) {
               $scope.fareConfrimationLoading = false;
               $scope.FareConfrimationmessage = "Something went wrong";
            });
         } else {
            alert("Something went wrong");
         }
      }
      $scope.orderby = function(field, order = null, event = null) {
         if (order === null) {
            $scope.reverse = ($scope.field === field) ? !$scope.reverse : true;
            $scope.field = field;
         } else {
            $scope.reverse = order;
            $scope.field = field;
            $scope.topsortingtext = event.target.innerHTML;
         }
      }
      $scope.Priceslider = function() {
         $ = jQuery.noConflict();
         price_min = $scope.FlightFilter[0]['Price']['min'];
         price_max = $scope.FlightFilter[0]['Price']['max'];
         var step = 0.01;
         $(".price-range").slider({
            range: true,
            min: price_min,
            max: price_max,
            step: parseFloat(step),
            values: [price_min, price_max],
            slide: function(event, ui) {
               $(".left-price").val(ui.values[0].toString());
               $(".right-price").val(ui.values[1].toString());
            },
            stop: function(event, ui) {
               var min = ui.values[0];
               var max = ui.values[1];
               let filters = [];
               let filteredResult = [];
               let FilterData = [];
               FilterData = $scope.FlightFilter[0];
               let filterAirline = $scope.checkedfilter(FilterData['Airline'], 'value');
               let filterStop = $scope.checkedfilter(FilterData['Stop'], 'value');
               let filterFaretype = $scope.checkedfilter(FilterData['FareType'], 'value');
               let filterDepartTime = $scope.checkedfilter(FilterData['DepartTime'], 'value');
               let filterArrivalTime = $scope.checkedfilter(FilterData['ArrivalTime'], 'value');
               if (filterAirline.length !== 0) {
                  filters['Airlinecode'] = filterAirline;
               }
               if (filterStop.length !== 0) {
                  filters['Stops'] = filterStop;
               }
               if (filterFaretype.length !== 0) {
                  filters['IsRefundable'] = filterFaretype;
               }
               if (filterDepartTime.length !== 0) {
                  filters['DepartString'] = filterDepartTime;
               }
               if (filterArrivalTime.length !== 0) {
                  filters['ArrivalString'] = filterArrivalTime;
               }
               var filtered = $scope.multiFilter(0, $scope.FilterResults, filters);
               angular.forEach(filtered, function(item) {
                  if (item.MainSegment[0].Fare >= min && item.MainSegment[0].Fare <= max) {
                     filteredResult.push(item);
                  }
               });
               $scope.FilteredResultCount = filteredResult.length;
               if (filteredResult.length == 0) {
                  $scope.FliterNoFlight = true;
               } else {
                  $scope.FliterNoFlight = false;
               }
               $scope.limit = limitStep;
               $scope.Results = filteredResult;
               $scope.$evalAsync();
            }
         });
         $(".left-price").val($(".price-range").slider("values", 0).toString());
         $(".right-price").val($(".price-range").slider("values", 1).toString());
      }
      $scope.doFilter = function(filtertype, type, event, item) {
         if (type == "FareType") {
            if (event.target.checked) {
               item['isChecked'] = true;
            } else {
               item['isChecked'] = false;
            }
         }
         if (type == "Stop") {
            if (event.target.checked) {
               item['isChecked'] = true;
            } else {
               item['isChecked'] = false;
            }
         }
         if (type == "DepartTime") {
            if (event.target.checked) {
               item['isChecked'] = true;
            } else {
               item['isChecked'] = false;
            }
         }
         if (type == "Airline") {
            if (event.target.checked) {
               item['isChecked'] = true;
            } else {
               item['isChecked'] = false;
            }
         }
         let min = $.trim($(".left-price").val());
         let max = $.trim($(".right-price").val());
         let FilterData = [];
         FilterData = $scope.FlightFilter[filtertype];
         let filters = [];
         let filteredResult = [];
         setTimeout(() => {
            let filterAirline = $scope.checkedfilter(FilterData['Airline'], 'value');
            let filterStop = $scope.checkedfilter(FilterData['Stop'], 'value');
            let filterFaretype = $scope.checkedfilter(FilterData['FareType'], 'value');
            let filterDepartTime = $scope.checkedfilter(FilterData['DepartTime'], 'value');
            let filterArrivalTime = $scope.checkedfilter(FilterData['ArrivalTime'], 'value');
            if (filterAirline.length !== 0) {
               filters['Airlinecode'] = filterAirline;
            }
            if (filterStop.length !== 0) {
               filters['Stops'] = filterStop;
            }
            if (filterFaretype.length !== 0) {
               filters['IsRefundable'] = filterFaretype;
            }
            if (filterDepartTime.length !== 0) {
               filters['DepartString'] = filterDepartTime;
            }
            if (filterArrivalTime.length !== 0) {
               filters['ArrivalString'] = filterArrivalTime;
            }
            if (type == 'clear') {
               filters = [];
            }
            filtered = $scope.multiFilter(filtertype, $scope.FilterResults, filters);
            angular.forEach(filtered, function(item) {
               if (item.MainSegment[filtertype].Fare >= min && item.MainSegment[filtertype].Fare <=
                  max) {
                  filteredResult.push(item);
               }
            });
            if (filteredResult.length == 0) {
               $scope.FliterNoFlight = true;
            } else {
               $scope.FliterNoFlight = false;
            }
            $scope.FilteredResultCount = filteredResult.length;
            $scope.Results = filteredResult;
            $scope.limit = limitStep;
            $scope.$digest();
         }, 10);
      }
      $scope.multiFilter = function(filtertype, array, filters) {
         var filterKeys = Object.keys(filters);
         let newfilters = [];
         filterKeys.forEach(function(value, key) {
            if (value != 'IsRefundable') {
               newfilters[value] = filters[value];
            }
         });
         var newfilterKeys = Object.keys(newfilters);
         let response = [];
         array.filter((item) => {
            if (filterKeys.includes("IsRefundable")) {
               let fareitem = [];
               item['FareList'].forEach(function(value, key) {
                  if (filters['IsRefundable'].indexOf(value['IsRefundable']) !== -1) {
                     fareitem.push(value);
                  }
               });
               if (newfilterKeys.every(key1 => !!~newfilters[key1].indexOf(item['MainSegment'][
                     filtertype
                  ][key1]))) {
                  if (fareitem.length != 0) {
                     let obj = Object.assign({}, item);
                     obj['FareList'] = fareitem;
                     response.push(obj);
                  }
               }
            } else {
               if (filterKeys.every(key => !!~filters[key].indexOf(item['MainSegment'][filtertype][
                     key
                  ]))) {
                  response.push(item);
               }
            }
         });
         return response;
      }
      $scope.clearFilterAll = function(filtertype, type) {
         if (type == 'all') {
            $scope.FlightFilter[filtertype]['FareType'] = $scope.resetfilter($scope.FlightFilter[filtertype][
               'FareType'
            ]);
            $scope.FlightFilter[filtertype]['Stop'] = $scope.resetfilter($scope.FlightFilter[filtertype][
               'Stop'
            ]);
            $scope.FlightFilter[filtertype]['DepartTime'] = $scope.resetfilter($scope.FlightFilter[filtertype][
               'DepartTime'
            ]);
            $scope.FlightFilter[filtertype]['ArrivalTime'] = $scope.resetfilter($scope.FlightFilter[filtertype]
               ['ArrivalTime']);
            $scope.FlightFilter[filtertype]['Airline'] = $scope.resetfilter($scope.FlightFilter[filtertype][
               'Airline'
            ]);
            angular.element($(".price-range").slider("values", 0, $scope.FlightFilter[filtertype]['Price'][
               'min'
            ]));
            angular.element($(".price-range").slider("values", 1, $scope.FlightFilter[filtertype]['Price'][
               'max'
            ]));
            angular.element($(".left-pricel").val($scope.FlightFilter[filtertype]['Price']['min']));
            angular.element($(".right-price").val($scope.FlightFilter[filtertype]['Price']['max']));
            $scope.Priceslider();
         }
         $scope.doFilter(filtertype, 'clear', '', '');
         $scope.limit = limitStep;
      }
      $scope.checkedfilter = function(array, field) {
         let response = [];
         array.forEach(function(value, key) {
            if (value.isChecked) {
               response.push(value[field]);
            }
         });
         return response;
      }
      $scope.resetfilter = function(array) {
         let response = [];
         array.forEach(function(value, key) {
            value.isChecked = false;
            response.push(value);
         });
         return response;
      }
      $scope.showHideMobileFilter = function(type) {
         if (type == 'show') {
            $("[filter-content-action]").addClass('mobile-sidebar');
         } else {
            $("[filter-content-action]").removeClass('mobile-sidebar');
         }
      }

      $scope.showHideMobilemodify = function(type) {
         if (type == 'show') {
            $("[modify-content-action]").addClass('mobile-sidebar');
         } else {
            $("[modify-content-action]").removeClass('mobile-sidebar');
         }
      }

      $scope.getOtherFare = function(selertedfarettsIndexKey) {

         let selectedObject = $scope.FilterResults[selertedfarettsIndexKey];

         $scope.FareListDataForModal = selectedObject;

         $scope.FareListIndexOfResult = selertedfarettsIndexKey;

         $scope.fareList = true;

         var FlightFareListModel = document.getElementById("FlightFareListModel");

         if (FlightFareListModel !== null) {

            new bootstrap.Modal(FlightFareListModel).show();

         }

      }
      $scope.hideModal = function() {

$('#FlightFareListModel').modal('hide');

};

   });

   app.directive("scroll", function($window) {
      return function(scope, element) {
         angular.element($window).bind("scroll", function() {
            var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement
               .offsetHeight;
            var body = document.body,
               html = document.documentElement;
            var docHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html
               .scrollHeight, html.offsetHeight);
            windowBottom = windowHeight + window.pageYOffset + 1000;
            if (windowBottom >= docHeight) {
               scope.limit += limitStep;
            }
            scope.$apply();
         });
      };
   });
   app.filter('rtrim', function() {
      return function(value) {
         return value.replace(/, \W+/g, '');
      }
   });
   app.filter('changeDurationHourMinFormat', function() {
      return function(value) {
         var hour = Math.floor(value / 60);
         var minutes = value - (hour * 60);
         return hour + "h  " + minutes + "m";
      }
   });
   app.filter("trusthtml", ['$sce', function($sce) {
      return function(htmlCode) {
         return $sce.trustAsHtml(htmlCode);
      }
   }]);
   app.filter('html_filter', function() {
      return function(input, element) {
         return $("#" + element).html(input.replace(/&lt;/g, '<').replace(/&gt;/g, '>'));
      };
   });
   app.filter('paxtype', function() {
      return function(input) {
         var paxtype;
         switch (input) {
            case 'ADT':
               paxtype = "Adult";
               break;
            case 'CHD':
               paxtype = "Child";
               break;
            case 'INF':
               paxtype = "Infant";
               break;
            default:
               paxtype = "ADULT";
         }
         return paxtype;
      };
   });
</script>