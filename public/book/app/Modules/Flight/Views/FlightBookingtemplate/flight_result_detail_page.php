<!---new code---->
<div class="collapse" id="flight-booking-collapse2-{{flightitem.TtsIndex}}">
    <div class="flight-booking-detail-wrapper">
        <div class="row">
            <div class="col-lg-12 col-xl-12" ng-if="ErrorCode==0 && FlightSegment.length>0">
                <div class="flight-booking-detail-left">
                    <ul class="nav nav-tabs" id="flTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active flight-list-tab-btn" id="nav-flightDeatil_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-flightDeatil_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-flightDeatil_{{flightitem.TtsIndex}}" aria-selected="true">
                                Flight Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link flight-list-tab-btn" id="nav-fareInfo_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-fareInfo_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-fareInfo_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">
                                Fare Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link flight-list-tab-btn" id="nav-farerule_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-farerule_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-farerule_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">
                                Fare Rules
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link flight-list-tab-btn" id="nav-baggageinfo_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-baggageinfo_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-baggageinfo_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">
                                Baggage
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="flTabContent2">
                        <div class="tab-pane fade show active" id="nav-flightDeatil_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-flightDeatil_{{flightitem.TtsIndex}}-tab" tabindex="0">
                            <div ng-repeat="(tripIndicatorkey,tripSegment) in FlightSegment">
                                <div class="flight-booking-detail-info">
                                    <h6>
                                        <span>{{tripSegment[0].Origin.CityName}}</span>
                                        <span class="ars-arright">→</span>
                                        <span>{{tripSegment[tripSegment.length-1].Destination.CityName}}</span>
                                        <small> {{tripSegment[0].Origin.DepartTime|date: 'EEE, MMM d y'}}</small>
                                    </h6>

                                    <div class="flight-booking-content" ng-repeat="(segmentindicatorkey,segment) in tripSegment">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="flight-booking-airline">
                                                    <div class="flight-airline-img">
                                                        <img ng-src="<?php echo site_url('uploads/airline-images/'); ?>{{ segment.Airline.AirlineCode.trim() }}.png" alt="{{ segment.Airline.AirlineName }}">
                                                    </div>
                                                    <div class="flight-airline-info flex-grow-1">
                                                        <h5 class="flight-airline-name">{{segment.Airline.AirlineName}}</h5>
                                                        <span class="at-fontweight arct-idcode flight-airline-model">{{segment.Airline.AirlineCode}}-{{segment.Airline.FlightNumber}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="flight-booking-time row gy-lg-0 gy-3">
                                                    <div class="start-time col-lg-4">
                                                        <div class="start-time-icon">
                                                            <i class="fal fa-plane-departure"></i>
                                                        </div>
                                                        <div class="start-time-info">
                                                            <h6 class="start-time-text">{{segment.Origin.CityName}}, {{segment.Origin.CountryName}}</h6>
                                                            <p class="flight-full-date">{{segment.Origin.DepartTime|date: 'MMM d, EEE, HH:mm'}}</p>
                                                            <span class="flight-destination">{{segment.Origin.AirportName}}</span>
                                                            <span class="flight-destination" ng-if="segment.Origin.Terminal!=''">Terminal {{segment.Origin.Terminal}}</span>
                                                        </div>
                                                    </div>

                                                    <div class="flight-stop col-lg-4">

                                                        <div class="flight-booking-duration">
                                                            <span class="duration-text">{{segment.Duration|changeDurationHourMinFormat}}</span>
                                                        </div>
                                                        <div class="flight-stop-arrow"></div>
                                                        <span class="flight-stop-number">Duration</span>
                                                    </div>
                                                    <div class="end-time col-lg-4">
                                                        <div class="start-time-icon">
                                                            <i class="fal fa-plane-arrival"></i>
                                                        </div>
                                                        <div class="start-time-info">
                                                            <h6 class="end-time-text">{{segment.Destination.CityName}}, {{segment.Destination.CountryName}}</h6>
                                                            <p class="flight-full-date">{{segment.Destination.ArrivalTime|date: 'MMM d, EEE, HH:mm'}}</p>
                                                            <span class="flight-destination">{{segment.Destination.AirportName}}</span>
                                                            <span class="flight-destination" ng-if="segment.Destination.Terminal!=''">Terminal {{segment.Destination.Terminal}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="d-flex align-items-center flex-wrap justify-content-between">
                                                    <div>
                                                        <span ng-if="segment.Airline.FareClass && segment.Airline.FareClass!=''"><b>Fare Class</b> - {{segment.Airline.FareClass}}</span>
                                                    </div>
                                                    <div class="text-success">
                                                        <span ng-if="FlightFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable!=''">
                                                            {{FlightFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable}} seat(s) left
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span ng-if="FlightFareDetail.CabinClass">{{FlightFareDetail.CabinClass}}</span>
                                                    </div>
                                                    <div>
                                                        <span ng-if="segment.Craft"><b>Craft Type:</b> {{segment.Craft}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <!---layover code--->
                                                <div class="layover">
                                                    <span class="layover-label">LAYOVER :</span>
                                                    <span class="layover-time">{{segment.Layover}} {{segment.Destination.CityName}} {{ segment.TechStopPoint.length > 0  && egment.TechStopPoint[0].code != undefined  ? egment.TechStopPoint[0].code : ""}}</span> 
                                                </div>

                                               


                                                <!---layover code--->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" ng-repeat="(tripskey,trip) in flightitem.MainSegment" id="airline_remark_{{flightitem.TtsIndex}}">
                                <span ng-if="flightitem.AirlineRemark!=''"><b>Airline Remark :</b> {{flightitem.AirlineRemark}} </span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-fareInfo_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-fareInfo_{{flightitem.TtsIndex}}-tab" tabindex="0">
                            <div class="flight-booking-detail-info">
                                <h6 class="fare-details-text-label">Fare Details for {{paxFarekey|paxtype}}</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Fare</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody ng-repeat="(paxFarekey,paxFare) in  FlightFareDetail.FareBreakdown">
                                            <tr>
                                                <td>Base Price</td>
                                                <td>{{CurrencySymbol}} {{paxFare.BaseFare/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</td>
                                                <td>{{CurrencySymbol}} {{paxFare.BaseFare}}</td>
                                            </tr>
                                            <tr>
                                                <td>Taxes and Fees</td>
                                                <td>{{CurrencySymbol}} {{paxFare.Tax/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</td>
                                                <td>{{CurrencySymbol}} {{paxFare.Tax}}</td>
                                            </tr>
                                            <tr>
                                                <td>Service and Other Charges</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.OtherCharges+FlightFareDetail.Fare.ServiceCharges}}</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.OtherCharges+FlightFareDetail.Fare.ServiceCharges}}</td>
                                            </tr>
                                            <tr>
                                                <td>GST</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.GST.CGSTAmount+FlightFareDetail.Fare.GST.SGSTAmount+FlightFareDetail.Fare.GST.IGSTAmount}}</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.GST.CGSTAmount+FlightFareDetail.Fare.GST.SGSTAmount+FlightFareDetail.Fare.GST.IGSTAmount}}</td>
                                            </tr>
                                            <tr ng-if="FlightFareDetail.Fare.WebPDiscount!=0">
                                                <td>Discount(-)</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.Discount}}</td>
                                                <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.Discount + FlightFareDetail.Fare.AgentCommission}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" colspan="2">Total</th>
                                                <th scope="row">{{CurrencySymbol}} {{FlightFareDetail.Fare.OfferedPrice}}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php /* if(0) {  */ ?>
                        <div class="tab-pane fade" id="nav-farerule_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-farerule_{{flightitem.TtsIndex}}-tab" tabindex="0">
                            <div class="flight-booking-detail-info">
                                <div class="row" ng-if="fareRuleLoading==true">
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
                                <div class="row" ng-if="fareRuleLoading==false && FareRuleErrorCode==0" ng-repeat="(farerulekey,farerule) in  FlightFareRule">
                                    <div class="col-md-12">
                                        <button class="ars-activelist fare-rules-tabs">
                                            {{farerule.Origin}}-{{farerule.Destination}}
                                        </button>
                                    </div>
                                    <div class="col-md-12 fare-rule-content" id="fareRuleData{{flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}">
                                        {{farerule.FareRuleDetail|html_filter:"fareRuleData"+flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}
                                    </div>
                                </div>
                                <div class="row" ng-if="fareRuleLoading==false && FareRuleErrorCode!=0">
                                    <div class="col-md-12">
                                        {{fareruleerrormessage}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*  } */ ?>
                        <div class="tab-pane fade" id="nav-baggageinfo_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-baggageinfo_{{flightitem.TtsIndex}}-tab" tabindex="0">
                            <div class="flight-booking-detail-info">
                                <table class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sector</th>
                                            <th>Check-In</th>
                                            <th>Cabin</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-repeat="(tripBaggagekey,tripBaggageInfo)  in FlightBaggageInfo">
                                        <tr ng-repeat="(segmentBaggagekey,BaggageInfo) in tripBaggageInfo">
                                            <td>{{BaggageInfo.Sector}}</td>
                                            <td>{{BaggageInfo.CheckIn}}</td>
                                            <td>{{BaggageInfo.Cabin}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!---new code end---->

<div class="d-none">
    <div class="row  flight-detail" id="id-{{flightitem.TtsIndex}}" style="display:none;">
        <div class="col-12 col-md-12" ng-if="ErrorCode==0 && FlightSegment.length>0">
            <div class="flight-list-tab">
                <nav class="d-flex align-items-center justify-content-between border-bottom">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active flight-list-tab-btn" id="nav-flightDeatil_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-flightDeatil_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-flightDeatil_{{flightitem.TtsIndex}}" aria-selected="true">Flight Details
                        </button>
                        <button class="nav-link flight-list-tab-btn" id="nav-fareInfo_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-fareInfo_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-fareInfo_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">Fare Details
                        </button>
                        <?php /* if(0) { */ ?>
                        <button class="nav-link flight-list-tab-btn" id="nav-farerule_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-farerule_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-farerule_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">Fare Rules
                        </button>
                        <?php /* } */ ?>
                        <button class="nav-link flight-list-tab-btn" id="nav-baggageinfo_{{flightitem.TtsIndex}}-tab" data-bs-toggle="tab" data-bs-target="#nav-baggageinfo_{{flightitem.TtsIndex}}" type="button" role="tab" aria-controls="nav-baggageinfo_{{flightitem.TtsIndex}}" aria-selected="false" tabindex="-1">Baggage
                        </button>
                    </div>
                    <div class="">
                        <button type="button" class="btn cross-btn" ng-click="hidFlightDetail(flightitem.TtsIndex)"><i class="fas fa-times"></i></button>
                    </div>
                </nav>
                <div class="tab-content flight-list-tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-flightDeatil_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-flightDeatil_{{flightitem.TtsIndex}}-tab" tabindex="0">
                        <div class="row align-items-center gy-2" ng-repeat="(tripIndicatorkey,tripSegment) in FlightSegment">
                            <div class="col-md-12 d-md-flex align-items-center justify-content-between mb-3">

                                <h6 class="flight-details-top-list">
                                    <span>{{tripSegment[0].Origin.CityName}}</span>
                                    <span class="ars-arright">→</span>
                                    <span>{{tripSegment[tripSegment.length-1].Destination.CityName}}</span>
                                    <span class="graycolor"> {{tripSegment[0].Origin.DepartTime|date: 'EEE, MMM d y'}}</span>
                                </h6>
                                <div class="flight-listair1" ng-repeat="(segmentindicatorkey,segment) in tripSegment">
                                    <div> <span>{{segment.Duration|changeDurationHourMinFormat}}, <span class="at-fontweight graycolor" ng-if="FlightFareDetail.CabinClass">{{FlightFareDetail.CabinClass}}</span>,

                                            <span ng-if="FlightFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable!=''">
                                                {{FlightFareDetail.SeatBaggage[tripIndicatorkey][segmentindicatorkey].NoOfSeatAvailable}} seat(s) left
                                            </span>,
                                            <span ng-if="segment.Airline.FareClass && segment.Airline.FareClass!=''">Fare Class - {{segment.Airline.FareClass}}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" ng-repeat="(segmentindicatorkey,segment) in tripSegment">
                                <div class="row gy-4">
                                    <div class="col-lg-3 col-md-3">
                                        <ul class="flight-listair1 d-flex">
                                            <li>
                                                <img ng-src="<?php echo site_url('uploads/airline-images/'); ?>{{ segment.Airline.AirlineCode.trim() }}.png" alt="{{ segment.Airline.AirlineName }}" class="airline-logo">
                                            </li>
                                            <li>
                                                <div class="flight-holdid">
                                                    <span class="at-fontweight arct-idcode">{{segment.Airline.AirlineCode}}-{{segment.Airline.FlightNumber}}</span><span class="equipType" ng-if="segment.Craft"><i class="fa fa-plane"></i>-{{segment.Craft}}</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-9 col-md-9">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-sm-5 col-12 text-lg-start text-center">
                                                <div class="flight-listair1">
                                                    <span class="fw-bold">{{segment.Origin.DepartTime|date: 'MMM d, EEE, HH:mm'}}</span>
                                                    <span class="at-fontweight atb-airport graycolor">{{segment.Origin.CityName}}, {{segment.Origin.CountryName}}</span>
                                                    <span class="at-fontweight atb-airport graycolor"> {{segment.Origin.AirportName}}</span>
                                                    <span class="graycolor" ng-if="segment.Origin.Terminal!=''">Terminal {{segment.Origin.Terminal}}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 text-lg-center text-center col-12">
                                                <span class="ars-lsprice ars-prclist atb-iconclass abt-nnstop stop-arrowline">Non-Stop</span>
                                                <span class="fa fa-long-arrow-right d-block "></span>
                                            </div>
                                            <div class="col-sm-5 col-12">
                                                <div class="flight-listair1 text-lg-end text-center">
                                                    <span class="fw-bold">{{segment.Destination.ArrivalTime|date: 'MMM d, EEE, HH:mm'}}</span>
                                                    <span class="at-fontweight atb-airport graycolor">{{segment.Destination.CityName}}, {{segment.Destination.CountryName}}</span>
                                                    <span class="at-fontweight atb-airport graycolor"> {{segment.Destination.AirportName}}</span>
                                                    <span class="graycolor" ng-if="segment.Destination.Terminal!=''">Terminal {{segment.Destination.Terminal}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-fareInfo_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-fareInfo_{{flightitem.TtsIndex}}-tab" tabindex="0">
                        <h6 class="fare-details-text-label">Fare Details for {{paxFarekey|paxtype}}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Fare</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody ng-repeat="(paxFarekey,paxFare) in  FlightFareDetail.FareBreakdown">
                                    <tr>
                                        <td>Base Price</td>
                                        <td>{{CurrencySymbol}} {{paxFare.BaseFare/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</td>
                                        <td>{{CurrencySymbol}} {{paxFare.BaseFare}}</td>
                                    </tr>
                                    <tr>
                                        <td>Taxes and Fees</td>
                                        <td>{{CurrencySymbol}} {{paxFare.Tax/paxFare.PassengerCount}} x {{paxFare.PassengerCount}}</td>
                                        <td>{{CurrencySymbol}} {{paxFare.Tax}}</td>
                                    </tr>
                                    <tr>
                                        <td>Service and Other Charges</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.OtherCharges+FlightFareDetail.Fare.ServiceCharges}}</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.OtherCharges+FlightFareDetail.Fare.ServiceCharges}}</td>
                                    </tr>
                                    <tr>
                                        <td>GST</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.GST.CGSTAmount+FlightFareDetail.Fare.GST.SGSTAmount+FlightFareDetail.Fare.GST.IGSTAmount}}</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.GST.CGSTAmount+FlightFareDetail.Fare.GST.SGSTAmount+FlightFareDetail.Fare.GST.IGSTAmount}}</td>
                                    </tr>
                                    <tr ng-if="FlightFareDetail.Fare.WebPDiscount!=0">
                                        <td>Discount(-)</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.Discount}}</td>
                                        <td>{{CurrencySymbol}} {{FlightFareDetail.Fare.Discount + FlightFareDetail.Fare.AgentCommission}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="2">Total</th>
                                        <th scope="row">{{CurrencySymbol}} {{FlightFareDetail.Fare.OfferedPrice}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php /* if(0) {  */ ?>
                    <div class="tab-pane fade" id="nav-farerule_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-farerule_{{flightitem.TtsIndex}}-tab" tabindex="0">
                        <div class="row" ng-if="fareRuleLoading==true">
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
                        <div class="row" ng-if="fareRuleLoading==false && FareRuleErrorCode==0" ng-repeat="(farerulekey,farerule) in  FlightFareRule">
                            <div class="col-md-12">
                                <button class="ars-activelist fare-rules-tabs">
                                    {{farerule.Origin}}-{{farerule.Destination}}
                                </button>
                            </div>
                            <div class="col-md-12 fare-rule-content" id="fareRuleData{{flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}">
                                {{farerule.FareRuleDetail|html_filter:"fareRuleData"+flightitem.TtsIndex+farerule.Origin+farerule.Destination+farerulekey}}
                            </div>
                        </div>
                        <div class="row" ng-if="fareRuleLoading==false && FareRuleErrorCode!=0">
                            <div class="col-md-12">
                                {{fareruleerrormessage}}
                            </div>
                        </div>
                    </div>
                    <?php /*  } */ ?>
                    <div class="tab-pane fade" id="nav-baggageinfo_{{flightitem.TtsIndex}}" role="tabpanel" aria-labelledby="nav-baggageinfo_{{flightitem.TtsIndex}}-tab" tabindex="0">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>Sector</th>
                                    <th>Check-In</th>
                                    <th>Cabin</th>
                                </tr>
                            </thead>
                            <tbody ng-repeat="(tripBaggagekey,tripBaggageInfo)  in FlightBaggageInfo">
                                <tr ng-repeat="(segmentBaggagekey,BaggageInfo) in tripBaggageInfo">
                                    <td>{{BaggageInfo.Sector}}</td>
                                    <td>{{BaggageInfo.CheckIn}}</td>
                                    <td>{{BaggageInfo.Cabin}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>