<?php if ($bookingConfrimationData) { ?>
    <div class="travelimp__thanku">
        <div class="container">
            <div class="row">
                <div class="travelimp__thanku--bookingSuccess d-flex align-items-center">
                    <div class="travelimp__thanku--statuscontent">
                        Booking<span class="<?php echo $bookingConfrimationData['bookingStatusString'] == "Confirmed" || $bookingConfrimationData['bookingStatusString'] == "Confirmed,Confirmed" ? "tts-text-success" : "tts-text-danger" ?> travelimp__thanku--statussize"><?php echo $bookingConfrimationData['bookingStatusString'];  ?></span>
                        <p class="travelimp__thanku--bookingIdShow"><b>Booking Ref Number : </b><?php $bookingrefNumbers = explode(",", $bookingConfrimationData['bookingRefNumberString']);
                                                                                                foreach ($bookingrefNumbers as $bookingrefNumberkey => $bookingrefNumber) { ?><a href="<?php echo site_url('/flight/details/') . $bookingrefNumber; ?>" target="_blank" class="travelimp__thanku--redirectid"><?php echo  $bookingrefNumber; ?></a><?php if ($bookingrefNumberkey == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                    echo  ",";
                                                                                                                                                                                                                                                                                                                                                                                                                                                } ?><?php }  ?></p>
                        <?php if ($bookingConfrimationData['pnrString'] != "") {  ?>
                            <p class="travelimp__thanku--bookingIdShow"><b>PNR : </b><a href="" class="travelimp__thanku--redirectid"><?php echo $bookingConfrimationData['pnrString'];  ?></a></p>
                        <?php } ?>
                        <?php if ($bookingConfrimationData['paymentGatewayInfo']) {
                            foreach ($bookingConfrimationData['paymentGatewayInfo'] as $paymentGateway) {
                                if ($paymentGateway) { ?>
                                    <p class="travelimp__thanku--bookingIdShow"><b>Payment Status : </b><a href="" class="travelimp__thanku--redirectid"><?php echo $paymentGateway['PaymentStatus'];  ?></a></p>

                                    <p class="travelimp__thanku--bookingIdShow"><b>Order Number : </b><a href="" class="travelimp__thanku--redirectid"><?php echo $paymentGateway['OrderId'];  ?></a></p>

                                    <p class="travelimp__thanku--bookingIdShow"><b>Amount : </b><a href="" class="travelimp__thanku--redirectid"><?php echo $paymentGateway['Amount'];  ?></a></p>
                        <?php }
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="flight-confirmation" ng-app="flightDetailApp" ng-controller="flightDetailCtrl">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="travelimp__thanku--leftside">
                        <div class="travelimp__thanku--panelHeadwrap">
                            <?php if ($bookingConfrimationData  && $bookingConfrimationData['ConfirmationBookingData']) {
                                foreach ($bookingConfrimationData['ConfirmationBookingData'] as $jtype => $ConfirmationBookingData) {
                                    $tripInfo  =  $ConfirmationBookingData['Segments'];
                                    foreach ($tripInfo  as $key =>  $trips) {
                                        $Duration =  array_column($trips, "Duration");
                                        $TotalDurationMin =   array_sum($Duration);
                                        $firstSegment  =  reset($trips);
                                        $lastegment  =  end($trips);
                            ?>
                                        <div class="travelimp__thanku--flightFlex">
                                            <div class="travelimp__thanku--flightFlex">
                                                <div class="travelimp__thanku--panelHeading travelimp__thanku--panelHeading-positionHandle"><?php echo $firstSegment['Origin']['CityName']  ?> <span class="travelimp__thanku--arrowIcon">→</span> <?php echo $lastegment['Destination']['CityName']  ?><span class="travelimp__thanku--tripdate travelimp__thanku--tripdate-positionHandle"> <?php echo  get_flight_date($firstSegment['Origin']['DepartTime']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($trips) {
                                            foreach ($trips as $segmentIndicatorkey => $segment) {
                                        ?>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="travelimp__segmentwrap d-flex align-items-center">
                                                            <img
                                                                src="<?php echo site_url('uploads/airline-images/'); ?><?php echo $segment['Airline']['AirlineCode']; ?>.png"
                                                                alt="<?php echo $segment['Airline']['AirlineName']; ?>" class="img-fluid airline-logo">

                                                            <ul class="travelimp__segmentwrap--flightul">
                                                                <li class="travelimp__segmentwrap--flightname"><?php echo  $segment['Airline']['AirlineName']; ?></li>
                                                                <li class="travelimp__segmentwrap--flightcode"><?php echo  $segment['Airline']['AirlineCode']; ?> -<?php echo  $segment['Airline']['FlightNumber']; ?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                                    <li class="travelimp__segmentwrap--airportlist"><?php echo  get_flight_date($segment['Origin']['DepartTime']); ?>, <?php echo  get_flight_time($segment['Origin']['DepartTime']); ?></li>
                                                                    <li class="travelimp__segmentwrap--airportname"><?php echo  $segment['Origin']['CityName']; ?> , <?php echo  $segment['Origin']['CountryName']; ?> </li>
                                                                    <li class="travelimp__segmentwrap--airportname"><?php echo  $segment['Origin']['AirportName']; ?></li>
                                                                    <?php if ($segment['Origin']['Terminal'] != "") {  ?>
                                                                        <li class="travelimp__segmentwrap--airportname"><span class="travelimp__segmentwrap--terminaltext">Terminal : </span>Terminal <?php echo  $segment['Origin']['Terminal']; ?></li>
                                                                    <?php  } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <ul class="travelimp__segmentwrap--stoparrowul travelimp__segmentwrap">
                                                                    <li class="travelimp__segmentwrap--stoparrowlist">Non-Stop</li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                                    <li class="travelimp__segmentwrap--airportlist"><?php echo  get_flight_date($segment['Destination']['ArrivalTime']); ?>, <?php echo  get_flight_time($segment['Destination']['ArrivalTime']); ?></li>
                                                                    <li class="travelimp__segmentwrap--airportname"><?php echo  $segment['Destination']['CityName']; ?> , <?php echo  $segment['Destination']['CountryName']; ?> </li>
                                                                    <li class="travelimp__segmentwrap--airportname"><?php echo  $segment['Destination']['AirportName']; ?></li>
                                                                    <?php if ($segment['Destination']['Terminal'] != "") {  ?>
                                                                        <li class="travelimp__segmentwrap--airportname"><span class="travelimp__segmentwrap--terminaltext">Terminal : </span>Terminal <?php echo  $segment['Destination']['Terminal']; ?></li>
                                                                    <?php  } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                            <li class="travelimp__segmentwrap--airportlist"><?php echo  get_convertToHoursMinsfromMinDuration($segment['Duration']); ?></li>
                                                            <li class="travelimp__segmentwrap--airportname"><?php echo  $segment['CabinClass']; ?> , <?php if ($ConfirmationBookingData['IsRefundable']) { ?>
                                                                    Refundable
                                                                <?php  } else { ?>
                                                                    Non Refundable
                                                                <?php }  ?></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <span class="label label-purple travelimp__segmentwrap--fareidentifire"><?php echo $ConfirmationBookingData['FareType']; ?></span>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="travelimp__segmentwrap--baggageInfo">
                                                            <p class="">Baggage Information</p>
                                                            <p class="">Adult - <span class="travelimp__segmentwrap--baggagecheckin">Check-in : <?php echo $segment['CheckInBaggage']; ?>, Cabin : <?php echo $segment['CabinBaggage']; ?></span></p>
                                                            <?php if ($bookingConfrimationData['childCount']) {  ?>
                                                                <p class="">Child - <span class="travelimp__segmentwrap--baggagecheckin">Check-in : <?php echo $segment['CheckInBaggage']; ?>, Cabin : <?php echo $segment['CabinBaggage']; ?></span></p>
                                                            <?php  } ?>
                                                            <?php if ($bookingConfrimationData['infantCount']) {  ?>
                                                                <p class="">Infant - <span class="travelimp__segmentwrap--baggagecheckin">Cabin : <?php echo $segment['CabinBaggage']; ?></span></p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php  }
                                        }
                                    } ?>
                                    <?php /* if(0) { */ if ($ConfirmationBookingData['FareRule']) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="travelimp__segmentwrap--btn">
                                                    <button type="button" class="btn btn-default asr-viewbtn" onclick="showHidefareRule('farerule<?php echo   $jtype; ?>','fareruleButton<?php echo   $jtype; ?>')" fareruleButton<?php echo   $jtype; ?>="true"><span>Fare Rules</span>&nbsp;<i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none m0" farerule<?php echo   $jtype; ?>="true">
                                            <?php foreach ($ConfirmationBookingData['FareRule'] as $fare_rule) { ?>
                                                <div class="col-md-12">
                                                    <button class="ars-activelist fare-rules-tabs"><?php echo $fare_rule['Origin'] . "-" . $fare_rule['Destination']; ?></button>
                                                </div>
                                                <div class="col-md-12"><?php echo  $fare_rule['FareRuleDetail']; ?></div>
                                            <?php }  ?>
                                        </div>
                            <?php }
                                }
                            }  /* } */ ?>
                        </div>
                        <?php foreach ($bookingConfrimationData['ConfirmationBookingData'] as $jtype => $ConfirmationBookingData) {
                            $tripIndicator = $jtype == "OB" ? 0 : 1;
                            $TravelersInfos = $ConfirmationBookingData['TravelersInfo'];

                           /*  $code = $array['seat'][0]['Code']; */


                            /* foreach ($array['seat'] as $seat) {
                                echo $seat['Code'];
                            } */

                            
                            if ($TravelersInfos) { ?>
                                <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                                    <div class="travelimp__thanku--panelHeading">Passenger Details (<?php echo count($TravelersInfos) ?>)</div>
                                    <div class="travelimp__thanku--panelbody">
                                        <div class="table-responsive travelimp__thanku--responsivewrap">
                                            <table class="table table-bordered travelimp__thanku--tablewrap">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Passenger Name</th>
                                                        <th>PNR, Ticket No. &amp; Status</th>
                                                        <th>Meal</th>
                                                        <th>Baggage</th>
                                                        <th>Seat No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($TravelersInfos as $paxkey => $travelers) {
                                                        $mealInfo = "";
                                                        $baggageInfo = "";
                                                        $seatInfo = "";
                                                        if ($travelers) {
                                                            if (!empty($travelers['meal'])) {
                                                                $mealInfo = json_decode($travelers['meal'], true);
                                                            }
                                                            if (!empty($travelers['baggage'])) {
                                                                $baggageInfo = json_decode($travelers['baggage'], true);
                                                            }
                                                            if (!empty($travelers['seat'])) {
                                                                $seatInfo = json_decode($travelers['seat'], true);
                                                            }

                                                            
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td><?php echo $paxkey + 1; ?></td>
                                                            <td>
                                                                <span class="travelimp__thanku--paxwiseprint"></span><?php echo $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name']; ?> (<?php echo $travelers['pax_type']; ?>)
                                                            </td>
                                                            <td>
                                                                <?php if (isset($bookingConfrimationData['pnr'][$tripIndicator])) { ?>
                                                                    <p class="m-0"><b>PNR</b>:<span class="art-pnrstatus"><?php echo $bookingConfrimationData['pnr'][$tripIndicator]; ?> </span></p>
                                                                <?php } ?>
                                                                <?php if ($travelers['ticket_number']) { ?>
                                                                    <p class="m-0"><b>Ticket Number</b>:<span class="art-pnrstatus"><?php echo $travelers['validating_airline']; ?><?php echo $travelers['ticket_number']; ?> </span></p>
                                                                <?php } ?>
                                                                <p class="m-0"><b>Status</b>:<span class="art-pnrstatus"> <?php echo $travelers['booking_status']; ?> </span></p>
                                                            </td>
                                                            <td><?php if ($mealInfo) {
                                                                    foreach ($mealInfo as $meal) {
                                                                        $mealData = "" . $meal['Origin'] . "-" . $meal['Destination'] . ": - " . $meal['AirlineDescription'] . "(" . $meal['Code'] . ")" . " ( QTY : " . $meal['Quantity'] . " )"; ?> <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $mealData; ?>"><?php echo limitTextChars($mealData, 30, true, true); ?> </span><?php }
                                                                                                                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                                                                                                                            echo $mealData = 'NA';
                                                                                                                                                                                                                                                                                                                                                                                        } ?></td>
                                                            
                                                            <td>
                                                                <?php if ($baggageInfo): ?>
                                                                    <?php foreach ($baggageInfo as $baggage): ?>
                                                                        <?php
                                                                            $airlineDescription = $baggage['AirlineDescription'] ?? '';
                                                                            $baggageData = $baggage['Origin'] . '-' . $baggage['Destination'] . ': - ' . $airlineDescription;
                                                                        ?>
                                                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $baggageData; ?>">
                                                                            <?php echo limitTextChars($baggageData, 30, true, true); ?>
                                                                        </span>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <?php echo 'NA'; ?>
                                                                <?php endif; ?>
                                                            </td>

                                                            <td>
                                                                <?php if ($seatInfo): ?>
                                                                    <?php foreach ($seatInfo as $seat): ?>
                                                                        <?php
                                                                            $airlineDescription = $seat['Code'] ?? '';  
                                                                            $seatData = $seat['Origin'] . '-' . $seat['Destination'] . ': - ' . $airlineDescription;

                                                                           
                                                                        ?>
                                                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $seatData; ?>">
                                                                            <?php echo $seatData; ?>
                                                                        </span><br>
                                                                         
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <?php echo 'NA'; ?>
                                                                <?php endif; ?>
                                                            </td>


                                                            <!--  <td>NA</td> -->
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                        <div class="d-none" faredetailview="true">
                            <div class="col-md-12 travelimp__thanku--panelHeadwrap" ng-if="onwardFarelength > 0 ">
                                <div class="travelimp__thanku--panelHeading ">{{onwardfaretext}} Fare Summary</div>
                                <ul class="travelimp__thanku--fareul ">
                                    <li class="travelimp__thanku--fareulborder" ng-repeat="(onwardFarekey, onwardFare) in FareInfoOB.FareBreakup">
                                        <p class="travelimp__thanku--fareullist"> <span>{{ onwardFare.LabelText}}</span> <span> {{CurrencySymbol}} {{onwardFare.Value }}</span></p>
                                    </li>
                                    <li class="travelimp__thanku--fareulborder">
                                        <p class="travelimp__thanku--fareullist"><span><span>{{FareInfoOB.TotalAmount.LabelText}} </span></span><span>{{CurrencySymbol}} {{FareInfoOB.TotalAmount.Value}}</span></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 travelimp__thanku--panelHeadwrap" ng-if="returnFarelength > 0 ">
                                <div class="travelimp__thanku--panelHeading ">{{returnfaretext}} Fare Summary</div>
                                <ul class="travelimp__thanku--fareul ">
                                    <li class="travelimp__thanku--fareulborder" ng-repeat="(returnFarekey, returnFare) in FareInfoIB.FareBreakup">
                                        <p class="travelimp__thanku--fareullist"><span>{{ returnFare.LabelText}}</span> <span> {{CurrencySymbol}} {{returnFare.Value }} </span> </p>
                                    </li>
                                    <li class="travelimp__thanku--fareulborder">
                                        <p class="travelimp__thanku--fareullist"><span><span>{{FareInfoIB.TotalAmount.LabelText}} </span></span><span>{{CurrencySymbol}} {{FareInfoIB.TotalAmount.Value}}</span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading ">Important Information</div>
                            <ul class="travelimp__termswrap--termsul">
                                <li class="travelimp__termswrap--termslist">1. You should carry a print-out of your booking and present for check-in.</li>
                                <li class="travelimp__termswrap--termslist">2. Date &amp; Time is calculated based on the local time of city/destination.</li>
                                <li class="travelimp__termswrap--termslist">3. Use the Reference Number for all Correspondence with us.</li>
                                <li class="travelimp__termswrap--termslist">4. Use the Airline PNR for all Correspondence directly with the Airline</li>
                                <li class="travelimp__termswrap--termslist">5. For departure terminal please check with airline first.</li>
                                <li class="travelimp__termswrap--termslist">6. Please CheckIn atleast 2 hours prior to the departure for domestic flight and 3 hours prior to the departure of international flight.</li>
                                <li class="travelimp__termswrap--termslist">7. For rescheduling/cancellation within 4 hours of departure time contact the airline directly</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php if ($bookingConfrimationData['bookingStatusString'] != "Processing" && $bookingConfrimationData['bookingStatusString'] != "Failed" &&  $bookingConfrimationData['bookingStatusString'] != "Processing,Processing" &&  $bookingConfrimationData['bookingStatusString'] != "Failed,Failed") { ?>
                        <div class="travelimp__thanku--rightside">
                            <ul class="travelimp__thanku--moreoptions">
                                <!--   <li class="travelimp__thanku--morelist">
                                <span class="travelimp__thanku--morecontent" ng-click="getTicketInvoiceModel('DownloadPdfTicket')"> Download as PDF</span>
                                <span class="travelimp__thanku--moreicons fa fa-download"></span>
                            </li> -->
                                <li class="travelimp__thanku--morelist" ng-click="getTicketInvoiceModel('PrintTicket')">
                                    <span class="travelimp__thanku--morecontent">Print Ticket</span>
                                    <span class="travelimp__thanku--moreicons fa fa-print"></span>
                                </li>
                                <li class="travelimp__thanku--morelist" ng-click="getTicketInvoiceModel('EmailTicket')">
                                    <span class="travelimp__thanku--morecontent"> Email Ticket</span>
                                    <span class="travelimp__thanku--moreicons fa fa-envelope"></span>
                                </li>
                                <?php if (empty($bookingConfrimationData['InvoiceOption'])) { ?>
                                    <li class="travelimp__thanku--morelist">
                                        <a class="travelimp__thanku--anchorlink" href="<?php echo  site_url('flight/get-invoice-ticket?type=CustomerInvoice&booking_ref_number=' . $bookingConfrimationData['bookingRefNumberString']) ?>">
                                            <span class="travelimp__thanku--morecontent"> Invoice</span>
                                            <span class="travelimp__thanku--moreicons fa fa-file-alt"></span>
                                        </a>
                                    </li>
                                <?php  } else { ?>
                                    <li class="travelimp__thanku--morelist">
                                        <span class="travelimp__thanku--morecontent" ng-click="getTicketInvoiceModel('CustomerInvoice')"> Invoice</span>
                                        <span class="travelimp__thanku--moreicons fa fa-file-alt"></span>
                                    </li>
                                <?php } ?>
                                <!--  <li class="travelimp__thanku--morelist">
                                <a class="travelimp__thanku--anchorlink" href="#">
                                    <span class="travelimp__thanku--morecontent">Go to Cart Details</span>
                                    <span class="travelimp__thanku--moreicons fa fa-shopping-cart"></span>
                                </a>
                            </li> -->
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal fade" id="FlightUpdateMarkupDiscountModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index:999999!important;">
                <div class="modal-dialog">
                    <div class="modal-content modal_content">
                        <div class="modal-header modal_header">
                            <h5 class="modal-title">Please Wait</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="hotel-main">
                                <!----- start loading ---->
                                <div class="row" ng-if="flightUpdateMarkupDiscountLoading==true">
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
                                <div class="row" ng-if="flightUpdateMarkupDiscountLoading==false">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <h5 class="mt-4"> {{errormessage}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <?php if ($bookingConfrimationData['bookingStatusString'] != "Processing" &&  $bookingConfrimationData['bookingStatusString'] != "Processing,Processing" &&  $bookingConfrimationData['bookingStatusString'] != "Failed,Failed") { ?>
                <div class="modal fade " id="FlighTicketVoucherOptionModelModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered ">
                        <div class="modal-content modal-content_confirmation ">
                            <div class="modal-header modal-header_confirmation">
                                <h5 class="modal-title modal-title_confirmation">{{FlighTicketVoucherTitle}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body modal-body_confirmation">
                                <form action="<?php echo  site_url('flight/get-invoice-ticket') ?>" method="get" name="printTicketInvoiceForm">
                                    <input type="hidden" name="booking_ref_number" value="<?php echo $bookingConfrimationData['bookingRefNumberString'];  ?>">
                                    <input type="hidden" name="type" value="{{type}}">
                                    <?php if ($bookingConfrimationData['TicketOption']) {
                                        foreach ($bookingConfrimationData['TicketOption'] as $option) { ?>
                                            <div class="form-check form-check-inline" ng-if="PrintTicket==1">
                                                <input class="form-check-input form-check-input_confirmation" type="radio" value="<?php echo  $option ?>" name="ticketinvoicejourney" <?php echo  $option == "Both" ? "checked" : ""; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1"> <?php echo  $option ?></label>
                                            </div>
                                    <?php }
                                    } ?>
                                    <div class="form-check" ng-if="EmailTicket==1">
                                        <label class="form-check-label">Enter Email</label>
                                        <input class="form-control" type="text" placeholder="Enter Email" name="email">
                                    </div>
                                    <div class="form-check" ng-if="SmsTicket==1">
                                        <label class="form-check-label">Enter Mobile Number</label>
                                        <input class="form-control" type="text" placeholder="Enter Mobile Number" name="mobile_number">
                                    </div>
                                    <?php if ($bookingConfrimationData['InvoiceOption']) {
                                        foreach ($bookingConfrimationData['InvoiceOption'] as $option) { ?>
                                            <div class="form-check form-check-inline" ng-if="CustomerInvoice==1 || AgencyInvoice==1">
                                                <input class="form-check-input form-check-input_confirmation" type="radio" id="inlineCheckbox1" value="<?php echo  $option ?>" name="ticketinvoicejourney" <?php echo  $option == "Onward" ? "checked" : ""; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1"> <?php echo  $option ?></label>
                                            </div>
                                    <?php }
                                    } ?>
                                    <div class="form-check">
                                        <div class="text-center print-ticket-button"><button type="submit" class="btn btn-success">Submit</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Modal ----end---->

    </section>


<?php } ?>



<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<!------Start Flight Confirmation modal------>
<script>
    let url = "<?php echo  site_url(); ?>"
    var appFlightDetail = angular.module('flightDetailApp', []);
    appFlightDetail.controller('flightDetailCtrl', function($scope, $http) {

        $scope.ConfirmationBookingFareBreakUpData = '<?php echo  json_encode($bookingConfrimationData['FareBreakUpData']); ?>';
        $scope.ConfirmationBookingFareBreakUpData = angular.fromJson($scope.ConfirmationBookingFareBreakUpData);
      
        
        let faredetailview = document.querySelector('[faredetailview]');
        if (faredetailview.classList.contains('d-none')) {
            faredetailview.classList.remove('d-none');
        }
        $scope.FareInfoOB = {};
        $scope.FareInfoIB = {};
        $scope.CurrencySymbol = '';
        $scope.onwardtext = "";
        $scope.onwardFarelength = 0;
        $scope.returnFarelength = 0;
        if ($scope.ConfirmationBookingFareBreakUpData.OB) {
            $scope.FareInfoOB = $scope.ConfirmationBookingFareBreakUpData.OB;
            $scope.CurrencySymbol = $scope.ConfirmationBookingFareBreakUpData.OB.CurrencySymbol;
            $scope.onwardFarelength = Object.keys($scope.FareInfoOB).length;
        }
        if ($scope.ConfirmationBookingFareBreakUpData.IB) {
            $scope.onwardfaretext = "Onward";
            $scope.returnfaretext = "Return";
            $scope.FareInfoIB = $scope.ConfirmationBookingFareBreakUpData.IB;
            $scope.CurrencySymbol = $scope.ConfirmationBookingFareBreakUpData.IB.CurrencySymbol;
            $scope.returnFarelength = Object.keys($scope.FareInfoIB).length;
        }
        $scope.type = ""
        $scope.getTicketInvoiceModel = function(type) {
            var collection = document.getElementsByName("printTicketInvoiceForm");
            for (var i = 0; i < collection.length; i++) {
                collection[i].removeAttribute("tts-form");
                collection[i].setAttribute("method", "get");
            }
            $scope.type = type;
            $scope.FlighTicketVoucherTitle = "";
            $scope.CustomerInvoice = 0;
            $scope.AgencyInvoice = 0;
            $scope.SmsTicket = 0;
            $scope.EmailTicket = 0;
            $scope.PrintTicket = 0;
            $scope.DownloadPdfTicket = 0;
            var FlighTicketVoucherOptionModelModel = document.getElementById("FlighTicketVoucherOptionModelModel");
            if (FlighTicketVoucherOptionModelModel !== null) {
                new bootstrap.Modal(FlighTicketVoucherOptionModelModel).show();
            }
            if (type == 'DownloadPdfTicket') {
                $scope.DownloadPdfTicket = 1;
                $scope.FlighTicketVoucherTitle = "Download PDF";
            } else if (type == 'PrintTicket') {
                $scope.PrintTicket = 1;
                $scope.FlighTicketVoucherTitle = "Print Ticket";
            } else if (type == 'CustomerInvoice') {
                $scope.CustomerInvoice = 1;
                $scope.FlighTicketVoucherTitle = "Invoice For Customer";
            } else if (type == 'AgencyInvoice') {
                $scope.AgencyInvoice = 1;
                $scope.FlighTicketVoucherTitle = "Invoice For Agency";
            } else if (type == 'SmsTicket') {
                $scope.SmsTicket = 1;
                $scope.FlighTicketVoucherTitle = "Sms Ticket";
            } else if (type == 'EmailTicket') {
                var setcollection = document.getElementsByName("printTicketInvoiceForm");
                for (var i = 0; i < collection.length; i++) {
                    setcollection[i].setAttribute("tts-form", true);
                    setcollection[i].setAttribute("method", "post");
                }
                $scope.EmailTicket = 1;
                $scope.FlighTicketVoucherTitle = "Email Ticket";
            }
        }

    });
</script>