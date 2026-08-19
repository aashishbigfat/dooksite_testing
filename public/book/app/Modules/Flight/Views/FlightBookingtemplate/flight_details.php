<style>
    .ui-widget.ui-widget-content.calendarOuter {
        margin-top: 0;
        z-index: 1020 !important;
    }
</style>

<!-- <style>
   .tts-meal-section {
       background: #fff;
       border: 1px solid rgba(156, 170, 179, 0.28);
       -webkit-box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
       box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
       padding: 10px 15px;
       border-radius: 5px;
   }
   
   .flightLeftWrapper .flightHeadWrap {
       border-radius: 5px 5px 0px 0px !important;
       padding: unset !important;
       margin: unset !important;
       font-size: 18px !important;
       border-bottom: unset !important;
   }
   </style> -->
<div class="content" ng-app="flightDetailApp" ng-controller="flightDetailCtrl">
    <div class="container">
        <?php
        if (isset(session()->get('wl_customer')['id'])) {
            $wl_customer_id = session()->get('wl_customer')['id'];
            $email = session()->get('wl_customer')['email_id'];
            $mobile_no = session()->get('wl_customer')['mobile_no'];
        } else {
            $wl_customer_id = '';
        }

        $adult_dob_requird_airline = array('I5');
        $airlinecodesarray = array();
        $adultDob = false;
        $customerEmailId  =  isset($wl_customer_info['email_id']) ? $wl_customer_info['email_id'] : "";
        $customerMNumber  =  isset($wl_customer_info['mobile_no']) ? $wl_customer_info['mobile_no'] : "";
        $originOB = '';
        $destinationOB = '';

        $originIB = '';
        $destinationIB = '';


        if (isset($confirmationResponse['Error']['ErrorCode']) && $confirmationResponse['Error']['ErrorCode'] == 0) { ?>
            <div class="row">
                <div class="col-lg-9">
                    <div class="flight-details">
                        <div class="flight-booking-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Review Your Flight Details</h6>
                                <?php if (isset($_GET['token']) && $_GET['token'] != "") { ?>
                                    <a href="<?php echo site_url('flight/search?token=' . $_GET['token']) ?>">
                                        < Back To Search </a>
                                        <?php } else { ?>
                                            <a href="javascript:void(0);" onclick="history.back()">
                                                < Back To Search </a>
                                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        foreach ($confirmationResponse['flightConfrimationResponse'] as $journeykey => $journeys) {
                            foreach ($journeys['Result']['Segments'] as $tripkey => $trips) {
                                $Duration = array_column($trips, "Duration");
                                $TotalDurationMin = array_sum($Duration);
                                $firstSegment = reset($trips);
                                $lastegment = end($trips);
                                $airlinecodesarray[] = $firstSegment['Airline']['AirlineCode'];
                                $TotalDurationMin = $firstSegment['TotalDuration'];
                                if ($journeykey == 'OB') {
                                    $originOB = $firstSegment['Origin']['CityName'];
                                    $destinationOB = $lastegment['Destination']['CityName'];
                                }
                                if ($journeykey == 'IB') {
                                    $originIB = $firstSegment['Origin']['CityName'];
                                    $destinationIB = $lastegment['Destination']['CityName'];
                                }

                        ?>
                                <div class="flight-booking-item">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <h6 class="mb-0">
                                            <?php $journeyType = ($journeykey == 'OB' && $tripkey == 0) ? "DEPART" : "RETURN";
                                            $originCity = $firstSegment['Origin']['CityName'];
                                            $destinationCity = $lastegment['Destination']['CityName'];
                                            $stops = count($trips) - 1;
                                            $stopText = $stops == 0 ? "Non Stop" : "$stops Stops";
                                            $duration = get_convertToHoursMinsfromMinDuration($TotalDurationMin);
                                            ?>
                                            <?= $journeyType ?>
                                            <span><?= $originCity ?> <i class="fa-light fa-plane"></i> <?= $destinationCity ?> </span>
                                            <!-- <span class="d-block"><?= $originCity ?> <?= $stopText ?> <?= $duration ?></span> -->
                                        </h6>
                                        <a class="farerule-btn" href="javascript:void(0);" onclick="getFareRule('<?= $journeys['SearchTokenId'] ?>', '<?= $journeys['Result']['ResultIndex']; ?>')">
                                            Fare Rules
                                        </a>
                                    </div>
                                    <?php //pr($trips); 
                                    ?>
                                    <div class="flight-booking-info">
                                        <?php if ($trips) {
                                            foreach ($trips as $segmentIndicatorkey => $segment) { ?>
                                                <div class="flight-booking-content row">
                                                    <div class="flight-booking-airline col-lg-3">
                                                        <div class="flight-airline-img">
                                                            <img src="<?php echo site_url('uploads/airline-images/'); ?><?php echo $segment['Airline']['AirlineCode']; ?>.png" alt="<?php echo $segment['Airline']['AirlineName']; ?>" class="airline-logo me-2">
                                                        </div>
                                                        <h5 class="flight-airline-name">
                                                            <?php echo $segment['Airline']['AirlineName']; ?>
                                                            <span><?php echo $segment['Airline']['AirlineCode']; ?> -<?php echo $segment['Airline']['FlightNumber']; ?></span>
                                                        </h5>
                                                    </div>
                                                    <div class="flight-booking-time col-lg-9">
                                                        <div class="start-time">
                                                            <div class="start-time-icon">
                                                                <i class="fal fa-plane-departure"></i>
                                                            </div>
                                                            <div class="start-time-info">
                                                                <h6 class="start-time-text"> <?php echo get_flight_time($segment['Origin']['DepartTime']); ?> <span>(<?php echo get_flight_date($segment['Origin']['DepartTime']); ?>)</span></h6>
                                                                <span class="flight-destination"><?php echo $segment['Origin']['CityName']; ?> <b>(<?php echo $segment['Origin']['CityCode']; ?>)</b></span>
                                                                <span class="start-Depart-text d-block"><?php echo $segment['Origin']['AirportName']; ?></span>
                                                                <span class="start-Depart-text d-block">
                                                                    <?php if ($segment['Origin']['Terminal'] != "") { ?>
                                                                        Terminal <?php echo $segment['Origin']['Terminal']; ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flight-stop">
                                                            <span class="flight-stop-number "><?= $stopText ?></span>
                                                            <div class="flight-stop-arrow"></div>
                                                            <div class="flight-booking-duration">
                                                                <span class="duration-text"><?= $duration ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="end-time">
                                                            <div class="start-time-icon">
                                                                <i class="fal fa-plane-arrival"></i>
                                                            </div>
                                                            <div class="start-time-info">
                                                                <h6 class="start-time-text"> <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?> <span>(<?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>)</span></h6>
                                                                <span class="flight-destination"><?php echo $segment['Destination']['CityName']; ?> <b>(<?php echo $segment['Destination']['CityCode']; ?>)</b></span>
                                                                <span class="flight-destination d-block"><?php echo $segment['Destination']['AirportName']; ?></span>
                                                                <span class="flight-destination d-block">
                                                                    <?php if ($segment['Destination']['Terminal'] != "") { ?>
                                                                        Terminal <?php echo $segment['Destination']['Terminal']; ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="flight-indicator-content mt-3 d-flex align-items-center justify-content-between">
                                                            <span>
                                                                <?php if ($journeys['Result']['IsRefundable']) { ?>
                                                                    <span class="partialRef text-success">Refundable </span>
                                                                <?php } else { ?>
                                                                    <span class="partialRef text-danger">Non Refundable</span>
                                                                <?php } ?>
                                                            </span>
                                                            <span><b>Cabin Class:</b> <?php echo $segment['CabinClass']; ?></span>
                                                            <span><b>Fare Class :</b> <?php echo isset($segment['Airline']['FareClass']) && $segment['Airline']['FareClass'] != "" ? $segment['Airline']['FareClass'] : "-"; ?></span>
                                                            <span><b>Aircraft :</b> <?php echo $segment['Craft']; ?></span>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="flight-indicator-content mt-3 d-flex align-items-center justify-content-between">
                                                            <span><i class="fa-solid fa-suitcase"></i> <b>(Adult) Check-In :</b> <?php echo $segment['CheckInBaggage']; ?></span>
                                                            <span><b>Child: </b> <?php echo $segment['CabinBaggage']; ?></span>
                                                            <span><b>Infant: </b> <?php echo $segment['CabinBaggage']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <!---layover code--->
                                                        <div class="layover">
                                                            <span class="layover-label">LAYOVER :</span>
                                                            <span class="layover-time"><?php echo $segment['Layover']; ?></span>
                                                        </div>
                                                        <!---layover code--->
                                                    </div>
                                                </div>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                        <?php }
                        }
                        foreach ($airlinecodesarray as $airlinecodes) {

                            if (in_array($airlinecodes, $adult_dob_requird_airline) || $IsPassportMandatory || $IsAdultDOBMandatory) {
                                $adultDob = true;
                                break;
                            }
                        }

                        if ($IsADTDOBRequired && !$adultDob) {
                            $adultDob = true;
                        }

                        ?>
                        <form action="<?php echo site_url('flight/validate-travellers'); ?>" method="post" tts-form="true" name="flight-booking">
                            <!----traveller---->
                            <div class="flight-booking-item">
                                <div class="mb-3 border-bottom pb-3">
                                    <h6 class="mb-0">Traveller Details</h6>
                                </div>
                                <p> Please make sure you enter the Name as per your Government photo id. </p>
                                <input type="hidden" name="add_gst_detail" id="add_gst_detail" value="<?php echo $IsGSTMandatory; ?>">
                                <input type="hidden" name="rtype" value="<?php echo isset($_GET['rtype']) ? $_GET['rtype'] : ""; ?>">
                                <input type="hidden" name="pancard_requird" value="<?php echo $IsPanMandatory; ?>">
                                <input type="hidden" name="adult_dob" value="<?php echo $adultDob; ?>">
                                <input type="hidden" name="document_id_requird" value="<?php echo $IsDocumentIdMandatory; ?>">
                                <input type="hidden" name="passport_requird" value="<?php echo $IsPassportMandatory; ?>">
                                <input type="hidden" name="SearchTokenId" value="<?php echo $_GET['token'] ?>">
                                <input type="hidden" name="farecode" value="<?php echo $_GET['farecode'] ?>">
                                <input type="hidden" name="farecodereturn" value="<?php echo isset($_GET['farecodereturn']) ? $_GET['farecodereturn'] : ""; ?>">
                                <?php
                                $paxDobFormat = array('Adult' => "adult_dob_date", "Child" => "child_dob_date", "Infant" => "infant_dob_date");
                                foreach ($searchPaxInfo as $paxKey => $noOfPax) {
                                    if ($noOfPax) { ?>
                                        <div class="card shadow-none">
                                            <div class="card-header">
                                                <span class="title ps-0"><?php echo $paxKey; ?> X <?php echo $noOfPax ?></span>
                                            </div>
                                            <div class="card-body">
                                                <?php for ($paxCount = 1; $paxCount <= $noOfPax; $paxCount++) { ?>
                                                    <div class="row gy-3 align-items-center">
                                                        <div class="col-lg-2 col-12">
                                                            <?php echo $paxKey; ?><?php echo $paxCount; ?>
                                                        </div>
                                                        <div class="col-lg-2 col-12 ">
                                                            <select class="form-select form-control" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][title]" data-validation="required" data-validation-error-msg="Title is required">
                                                                <option value="">Title</option>
                                                                <?php if ($paxKey == "Adult") { ?>
                                                                    <option value="Mr">Mr</option>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Mrs">Mrs</option>
                                                                <?php } else { ?>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Mstr">Mstr</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-12 ">
                                                            <input type="text" class="form-control" placeholder="First Name" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][first_name]" data-validation="required alphanumeric" data-validation-error-msg-required="First Name is required" data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                        </div>
                                                        <div class="col-lg-3 col-12 ">
                                                            <input type="text" class="form-control" placeholder="Last Name" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][last_name]" data-validation="required alphanumeric" data-validation-error-msg-required="Last Name is required" data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                        </div>
                                                        <?php if ($paxKey == "Adult") {
                                                            $adult_dob_validation = "";
                                                            if ($adultDob) {
                                                                $adult_dob_validation = 'data-validation="required"';
                                                            }

                                                        ?>
                                                            <div class="col-lg-2 col-12 ">
                                                                <input type="text" class="form-control" placeholder="DOB" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][dob]" <?php echo $adult_dob_validation; ?> data-validation-error-msg-required="DOB is required" <?php echo $paxDobFormat[$paxKey]; ?>="true" readonly>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-lg-2 col-12 ">
                                                                <input type="text" class="form-control" placeholder="DOB" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][dob]" data-validation="required" data-validation-error-msg-required="DOB is required" <?php echo $paxDobFormat[$paxKey]; ?>="true" readonly>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($IsPanMandatory) { ?>
                                                            <div class="col-lg-2 col-12 ">
                                                                <input type="text" class="form-control" placeholder="PAN Card" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][pancard]" data-validation="required alphanumeric" data-validation-error-msg-required="PAN Card is required" data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($IsDocumentIdMandatory) { ?>
                                                            <div class="col-lg-2 col-md-3 col-12">
                                                                <input type="text" class="form-control" placeholder="Document Id" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][documentid]" data-validation="required alphanumeric" data-validation-error-msg-required="Document Id is required" data-validation-error-msg-alphanumeric="Please enter a valid Document Id">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($IsGDS) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12 ">
                                                            </div>
                                                            <div class="col-lg-4 col-12 ">
                                                                <a href="javascript:void(0);" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>" aria-expanded="false" aria-controls="collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>"><b>Add
                                                                        Frequent Flyer Number</b></a>
                                                            </div>
                                                        </div>
                                                        <div class="row collapse" id="collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>">
                                                            <div class="col-lg-2 col-12 ">
                                                            </div>
                                                            <div class="col-lg-2 col-12 ">
                                                                <input type="text" class="form-control" placeholder="Airline" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][frequent_fly_airline]" oninput="this.value = this.value.toUpperCase()">
                                                            </div>
                                                            <div class="col-lg-2 col-12 ">
                                                                <input type="text" class="form-control" placeholder="Number" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][frequent_fly_number]">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php $Passportrequired = "";
                                                    if ($IsPassportMandatory) {
                                                        $Passportrequired = "required";
                                                    }
                                                    if ($IsPassportMandatory || !($IsDomestic)) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12 ">
                                                                Passport Detail
                                                            </div>
                                                            <div class="col-lg-2 col-md-4 col-5">
                                                                <select class="form-select form-control select_search" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][nationality]""
                                                        data-validation=" <?php echo $Passportrequired; ?> "
                                                        data-validation-error-msg=" Nationality">
                                                                    <option value="">Nationality</option>
                                                                    <?php if ($dial_code) {
                                                                        foreach ($dial_code as $code) { ?>
                                                                            <option value="<?php echo $code['iso2']; ?>">
                                                                                <?php echo $code['name']; ?>
                                                                            </option>
                                                                    <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control" placeholder="Passport Number" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_no]" data-validation="<?php echo $Passportrequired; ?> alphanumeric" data-validation-error-msg-required="Passport Number is required" data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                            </div>
                                                            <div class="col-lg-2 col-12">
                                                                <input type="text" class="form-control" placeholder="Issue Date" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_issue_date]" data-validation="<?php echo $Passportrequired; ?>" data-validation-error-msg-required="Passport Issue Date is required" flight_pass_issue="true" readonly>
                                                            </div>
                                                            <div class="col-lg-2 col-12">
                                                                <input type="text" class="form-control" placeholder="Expire Date" name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_expire_date]" data-validation="<?php echo $Passportrequired; ?>" data-validation-error-msg-required="Passport Expire Date is required" flight_pass_expiry="true" readonly>
                                                            </div>
                                                        </div>
                                                <?php }
                                                    if ($paxCount != $noOfPax) {
                                                        echo "<hr>";
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                <?php }
                                } ?>

                            </div>

                            <!----Contact---->
                            <div class="flight-booking-item">
                                <div class="mb-3 border-bottom pb-3">
                                    <h6 class="mb-0">Contact Details</h6>
                                </div>
                                <p>Your ticket and flight details will be shared here</p>
                                <div class="row gy-3 align-items-center">
                                    <div class="col-lg-4">
                                        <select class="form-select form-control select_search" name="dial_code" data-validation="required" data-validation-error-msg="Dial Code is required">
                                            <option value="">Dial Code</option>
                                            <?php if ($dial_code) {
                                                foreach ($dial_code as $code) { ?>
                                                    <option value="<?php echo $code['phonecode']; ?>" <?php if ($code['phonecode'] == 91) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $code['name']; ?>
                                                        ( <?php echo $code['phonecode']; ?>)
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number" data-validation="required number length" data-validation-length="7-15" value="<?php echo $customerMNumber;  ?>" data-validation-error-msg-required="Please enter Mobile Number" data-validation-error-msg-number="Please enter a valid Mobile Number" data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="email" placeholder="Email" data-validation="required email" value="<?php echo $customerEmailId; ?>" data-validation-error-msg-required="Please enter Email" data-validation-error-msg-email="Please enter a valid Email" />
                                    </div>
                                </div>
                            </div>
                            <!----GST---->
                            <?php if ($GSTAllowed) { ?>
                                <div class="flight-booking-item">
                                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <h6 class="mb-0">Use GSTIN for this booking(<?php echo $IsGSTMandatory == "true" ? "Required" : "Optional"; ?>)</h6>
                                        <?php if (!$IsGSTMandatory) { ?>
                                            <button class="btn btn-warning btn-sm shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-flight-gst" aria-expanded="false" aria-controls="collapse-bus-gst" onclick="gst_detail(this,'add_gst_detail')">ADD</button>
                                        <?php } ?>
                                    </div>
                                    <p>Claim credit of GST charges. Your taxes may get updated post submitting your GST details.</p>
                                    <div class="collapse <?php if ($IsGSTMandatory) {
                                                                echo "show";
                                                            } ?>" id="collapse-flight-gst">

                                        <div class="row gy-3">
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[number]" placeholder="GST Number" data-validation="required alphanumeric" data-validation-error-msg-required="Please enter GST Number" data-validation-error-msg-alphanumeric="Please enter a valid GST Number">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[name]" placeholder="Registered Company Name" data-validation="required" data-validation-error-msg-required="Please enter Company Name" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[phone]" placeholder="Registered Contact No" data-validation="required number" data-validation-length="7-15" data-validation-error-msg-required="Please enter Contact No" data-validation-error-msg-number="Please enter a valid Contact No" data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[email]" placeholder="Registered Email" data-validation="required email" data-validation-error-msg-required="Please enter Email" data-validation-error-msg-email="Please enter a valid Email" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[address]" placeholder="Registered Address" data-validation="required" data-validation-error-msg-required="Please enter Address" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="flight-booking-item">
                                    <div class="mb-3 border-bottom pb-3">
                                        <h6 class="mb-0">Use GSTIN for this booking(<?php echo $IsGSTMandatory == "true" ? "Required" : "Optional"; ?>)</h6>
                                    </div>

                                    <div class="collapse show" id="collapse-flight-gst">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-4 col-12 ">
                                                <h6 class="text-danger"> GST not applicable on this booking</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!----Addon---->
                            <div class="flight-booking-item" style="display:none;" addon-service-flight="true">
                                <div class="mb-3 border-bottom pb-3">
                                    <h6 class="mb-0">Addon Services</h6>
                                </div>
                                <div class="accordion" id="accordionssr">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_ssr" aria-expanded="true" aria-controls="collapse_ssr">Add Baggage, Meal to Your Travel </button>
                                        </h2>
                                        <div id="collapse_ssr" class="accordion-collapse collapse show" data-bs-parent="#accordionssr">
                                            <div class="accordion-body" ng-cloak>
                                                <div class="ssr_loading" ng-if="ssr_loading">
                                                    <div class="text-center">
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" hideloader="true" aria-hidden="true" focusable="false" width="50" height="50" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" class="rotating" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                                                <circle cx="12" cy="20" r="1" fill="#626262" />
                                                                <circle cx="12" cy="4" r="1" fill="#626262" />
                                                                <circle cx="6.343" cy="17.657" r="1" fill="#626262" />
                                                                <circle cx="17.657" cy="6.343" r="1" fill="red" />
                                                                <circle cx="4" cy="12" r="1.001" fill="green" />
                                                                <circle cx="20" cy="12" r="1" fill="#626262" />
                                                                <circle cx="6.343" cy="6.344" r="1" fill="#626262" />
                                                                <circle cx="17.657" cy="17.658" r="1" fill="#626262" />
                                                            </svg>
                                                        </p>
                                                        <p>Please wait a few seconds</p>
                                                    </div>
                                                </div>
                                                <div class="ssr-content" ng-if="ssr_loading==false">
                                                    <div class="" ng-repeat="(ssrjkey,item) in ssr_data">
                                                        <p class="m-0" ng-if="ssrjkey==0">ONWARD</p>
                                                        <p class="m-0" ng-if="ssrjkey==1">Return</p>
                                                        <hr />
                                                        <div class="ssr-pax-wise" ng-repeat="(paxkey,paxitem) in item">
                                                            <div class="row gy-3" ng-repeat="(nopax,paxsubitem) in paxitem">
                                                                <div class="col-lg-12">
                                                                    <h6>{{paxkey}} {{nopax}}</h6>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <h6 ng-if="paxsubitem['Meal']">Meal</h6>
                                                                    <div class="row gy-3" ng-repeat="(tripkey,meal) in paxsubitem['Meal']">
                                                                        <div class="col-md-6" ng-repeat="(mealkey,mealsector) in meal">
                                                                            <label ng-if="mealkey!=0">{{mealkey.split('-')[0]}}
                                                                                <span class="ars-arright">→</span>
                                                                                {{mealkey.split('-')[1]}}</label>
                                                                            <?php if ($IsGDS == false) { ?>
                                                                                <select class="form-select" ng-model="selectmeal" ng-change="select_ssr_info('Meal',ssrjkey,paxkey,nopax,tripkey,mealkey,this.selectmeal)" name="ssr[meal][{{ssrjkey}}][{{paxkey}}][{{nopax}}][{{tripkey}}][{{mealkey}}]">
                                                                                    <option value="">Select Meal </option>
                                                                                    <option ng-repeat="subitem in mealsector" value="{{subitem['Code']}}@@{{subitem['Price']}}@@{{subitem['Quantity']}}">
                                                                                        {{subitem['AirlineDescription']}}
                                                                                        {{subitem['CurrencySymbol']}} {{subitem['Price']}}
                                                                                    </option>
                                                                                </select>
                                                                            <?php } ?>
                                                                            <?php if ($IsGDS) { ?>
                                                                                <select class="form-select" ng-model="selectmeal" ng-change="select_ssr_info('Meal',ssrjkey,paxkey,nopax,tripkey,mealkey,this.selectmeal)" name="ssr[meal][{{ssrjkey}}][{{paxkey}}][{{nopax}}][{{tripkey}}][{{mealkey}}]">
                                                                                    <option value="">Select Meal </option>
                                                                                    <option ng-repeat="subitem in mealsector" value="{{subitem['Code']}}@@{{subitem['Price']}}@@{{subitem['Quantity']}}">
                                                                                        {{subitem['AirlineDescription']}}
                                                                                    </option>
                                                                                </select>
                                                                            <?php } ?>
                                                                            <?php if ($IsGDS == false) { ?>
                                                                                <div id="Meal-{{ssrjkey}}-{{paxkey}}-{{nopax}}-{{tripkey}}-{{mealkey}}">
                                                                                    <span><b>Meal Qunatity :</b> 0 Platter</span>,
                                                                                    <span><b>Meal Charges :</b> {{CurrencySymbol}} 0</span>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <h6 ng-if="paxsubitem['Baggage']">Baggage</h6>
                                                                    <div ng-repeat="(tripkey,baggage) in paxsubitem['Baggage']">
                                                                        <div ng-repeat="(baggkey,baggagesector) in baggage">
                                                                            <label>{{baggkey.split('-')[0]}} <span class="ars-arright">→</span>{{baggkey.split('-')[1]}}</label>
                                                                            <select class="form-select fs-12" ng-model="selectbaggage" ng-change="select_ssr_info('Baggage',ssrjkey,paxkey,nopax,tripkey,baggkey,this.selectbaggage)" name="ssr[baggage][{{ssrjkey}}][{{paxkey}}][{{nopax}}][{{tripkey}}][{{baggkey}}]">
                                                                                <option value="">Select Baggage </option>
                                                                                <option ng-repeat="subitem in baggagesector" value="{{subitem['Code']}}@@{{subitem['Price']}}@@{{subitem['Weight']}}">
                                                                                    {{subitem['Weight']}} Kg <span ng-if="subitem['Price']!=0">{{subitem['CurrencySymbol']}}
                                                                                        {{subitem['Price']}}</span>
                                                                                    </span>
                                                                                </option>
                                                                            </select>
                                                                            <div id="Baggage-{{ssrjkey}}-{{paxkey}}-{{nopax}}-{{tripkey}}-{{baggkey}}">
                                                                                <span class="fs-12">Baggage Weight : 0 Kg,</span>
                                                                                <span class="fs-12">Baggage Charges : {{CurrencySymbol}} 0</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div ng-repeat="(sjkey,traveller) in travellerJson">
                                                        <div ng-repeat="(spaxkey,detail) in traveller">
                                                            <div ng-repeat="(spaxnokey,paxdetail) in detail">
                                                                <div ng-repeat="(spaxsegkey,paxsegdetail) in paxdetail">

                                                                    <div ng-if="searchRequest['JourneyType']==1 || searchRequest['JourneyType']==3">
                                                                        <input type="hidden" class="form-control" name="ssr[seat][0][{{spaxkey}}][{{spaxnokey}}][{{sjkey}}][{{spaxsegkey}}]" value="{{paxsegdetail['Key']}}" ng-if="paxsegdetail['Key']">
                                                                    </div>


                                                                    <div ng-if="searchRequest['JourneyType']==2 && searchRequest['IsDomestic']===false">
                                                                        <input type="hidden" class="form-control" name="ssr[seat][0][{{spaxkey}}][{{spaxnokey}}][{{sjkey}}][{{spaxsegkey}}]" value="{{paxsegdetail['Key']}}" ng-if="paxsegdetail['Key']">
                                                                    </div>

                                                                    <div ng-if="searchRequest['JourneyType']==2 && searchRequest['IsDomestic']===true">
                                                                        <input type="hidden" class="form-control" name="ssr[seat][{{sjkey}}][{{spaxkey}}][{{spaxnokey}}][0][{{spaxsegkey}}]" value="{{paxsegdetail['Key']}}" ng-if="paxsegdetail['Key']">
                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php echo  view('Modules/Flight/Views\FlightBookingtemplate/seat.php') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!$processedbutton) { ?>
                                <div class="flightGstNumber">
                                    <h6 class="text-center text-danger"> Please contact to administrator. </h6>
                                </div>
                            <?php } ?>

                            <?php
                            if ($processedbutton) { ?>
                                <div class="continuePayment text-end">
                                    <button type="submit" class="payment-btn">Continue Payment</button>
                                </div>
                            <?php } ?>

                        </form>
                    </div>
                </div>
                <div class="col-lg-3 d-none" faredetailview="true">
                    <div class="sticky-top">
                        <div class="flight-booking-item " ng-if="onwardFarelength > 0 ">
                            <div class="mb-3 border-bottom pb-3">
                                <h6 class="mb-0">{{onwardfaretext}} Fare Summary</h6>
                            </div>
                            <div class="flightFareTypes">
                                <div ng-repeat="(onwardFarekey, onwardFare) in FareInfoOB.FareBreakup">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span>{{ onwardFare.LabelText}}</span>
                                        <span>{{CurrencySymbol}} {{onwardFare.Value}}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-3">
                                    <span><strong>{{FareInfoOB.TotalAmount.LabelText}}</strong></span>
                                    <span><strong>{{CurrencySymbol}} {{FareInfoOB.TotalAmount.Value+obmealprice+obbaggageprice+obseatprice}}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="flight-booking-item" ng-if="returnFarelength > 0 ">
                            <div class="mb-3 border-bottom pb-3">
                                <h6 class="mb-0">{{returnfaretext}} Fare Summary</h6>
                            </div>
                            <div class="flightFareTypes">
                                <div ng-repeat="(returnFarekey, returnFare) in FareInfoIB.FareBreakup">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span>{{returnFare.LabelText}}</span>
                                        <span>{{CurrencySymbol}} {{returnFare.Value}}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-3">
                                    <span><strong>{{FareInfoIB.TotalAmount.LabelText}} </strong></span>
                                    <span><strong>{{CurrencySymbol}} {{FareInfoIB.TotalAmount.Value+ibmealprice+ibbaggageprice+ibseatprice}} </strong></span>
                                </div>
                            </div>
                        </div>
                        <!------------------- Start For Coupon List -------------->
                        <div class="flight-booking-item" ng-if="showCoupon">
                            <div class="mb-3 border-bottom pb-3">
                                <h6 class="mb-0">Promo Code</h6>
                            </div>
                            <div class="promo-list">
                                <form ng-submit="submitCouponCode()">
                                    <label class="form-label">Enter Your Promo Code</label>
                                    <div class="form-group d-flex">
                                        <input type="text" ng-model="CouponCode" class="form-control rounded-0 rounded-start" name="couponCode" placeholder="Apply Promo Code">
                                        <button type="submit" class="btn btn-danger rounded-0 rounded-end applyCode" ng-if="Applycoupon==null">Apply</button>
                                        <button type="submit" class="btn btn-danger rounded-0 rounded-end applyCode" ng-if="Applycoupon!=null" ng-click="removecoupon()">Remove</button>
                                    </div>
                                    <span ng-if="CouponErrorMessage" class="text-danger"> {{CouponErrorMessage}}</span>
                                    <span ng-if="CouponMessage" class="text-success"> {{CouponMessage}}</span>
                                </form>
                                <ul class="promo-options">
                                    <li class="promoList" ng-repeat="item in CouponListData track by $index">
                                        <div class="form-check">
                                            <input class="form-check-input fcoupon" type="radio" value="{{item['code']}}" id="coupon_id{{$index}}" ng-click="SelectCouponCode($event.target.value)">
                                            <label class="form-check-label" for="coupon_id{{$index}}">{{item['code']}}</label>
                                        </div>
                                        <p class="mb-0 text-muted">{{item['coupon_desc']}}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!------------------- End For Coupon List -------------->
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo isset($confirmationResponse['Error']['ErrorMessage']) ? $confirmationResponse['Error']['ErrorMessage'] : "No Result Found."; ?>
                    </h4>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<input type="hidden" value="<?php echo trim(explode("T", $searchRequest['AirSegments'][0]['PreferredTime'])[0]); ?>" flightpassportMinDate="true">
<div class="modal fade" id="error-modal" tabindex="-1" aria-labelledby="error-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong tts-error-message="true"></strong>
            </div>
        </div>
    </div>
</div>
<!------Start Flight fare rule modal------>
<div class="modal fade bd-example-modal-lg" id="FareRulesModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal_content">
            <div class="modal-header modal_header">
                <h5 class="modal-title">Fare Rules</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="hotel-main">
                    <!----- start loading ---->
                    <div class="row" fareRuleDeatailLoading="true">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="loader mt-3 ">
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
                    <div class="row" fareruleData="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------End Flight Fare Rule modal------>
<?php $SearchTokenId = $confirmationResponse['flightConfrimationResponse']['OB']['SearchTokenId'];
$fareCode = $_GET['farecode'];
$farecodereturn = (isset($_GET['farecodereturn'])) ? $_GET['farecodereturn'] : '';
?>
<!-- flight oneway result page ends here -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
    let url = "<?php echo site_url(); ?>";
    let searchRequest = <?php echo json_encode($searchRequest); ?>;
    var appFlightDetail = angular.module('flightDetailApp', []);
    appFlightDetail.controller('flightDetailCtrl', function($scope, $http) {
        $scope.searchRequest = <?php echo json_encode($searchRequest); ?>;
        $scope.CouponListData = '<?php echo json_encode($couponlist); ?>';
        $scope.CouponListData = JSON.parse($scope.CouponListData);
        $scope.CouponCode = '';
        $scope.CurrencySymbol = '<?php echo $CurrencySymbol; ?>';
        $scope.CurrencyCode = '<?php echo $CurrencyCode; ?>';
        $scope.CouponLoading = false;
        $scope.Applycoupon = null;
        $scope.CouponErrorMessage = '';
        $scope.showCoupon = '<?php echo $b2c_coupon; ?>';
        $scope.CouponID = '';
        $scope.FareConfirmationData = '<?php echo json_encode($confirmationResponse['FareBreakUpData']); ?>';


        $scope.FareConfirmationData = angular.fromJson($scope.FareConfirmationData);
        $scope.FareConfirmationDetailErrorCode = 0;
        if ($scope.FareConfirmationDetailErrorCode == 0) {
            let faredetailview = document.querySelector('[faredetailview]');
            if (faredetailview.classList.contains('d-none')) {
                faredetailview.classList.remove('d-none');
            }
            $scope.FareInfoOB = {};
            $scope.FareInfoIB = {};
            $scope.onwardtext = "";
            $scope.onwardFarelength = 0;
            $scope.returnFarelength = 0;
            if ($scope.FareConfirmationData.OB) {
                $scope.FareInfoOB = $scope.FareConfirmationData.OB;
                $scope.onwardFarelength = Object.keys($scope.FareInfoOB).length;
            }
            if ($scope.FareConfirmationData.IB) {
                $scope.onwardfaretext = "Onward";
                $scope.returnfaretext = "Return";
                $scope.FareInfoIB = $scope.FareConfirmationData.IB;
                $scope.returnFarelength = Object.keys($scope.FareInfoIB).length;
            }


        }
        $scope.ssr_loading = true;
        $scope.ssr_errorcode = 0;
        $scope.ssr_errormessage = '';
        $scope.ssr_data = [];
        $scope.obmealprice = 0;
        $scope.ibmealprice = 0;
        $scope.obbaggageprice = 0;
        $scope.ibbaggageprice = 0;


        $scope.CurrencySymbol = 0;
        $scope.currencyCode = 0;
        $scope.decimalPoint = 5;


        $scope.get_ssr_info = function() {
            $http({
                method: "GET",
                url: url + "flight/get-meal-baggage-data?" + "<?php echo http_build_query($_GET); ?>"
            }).then(function mySuccess(response) {
                /* console.log(response); */
                $scope.ssr_loading = false;
                $scope.ssr_errorcode = response['data']['ErrorCode'];
                $scope.ssr_errormessage = response['data']['ErrorMessage'];
                $scope.ssr_data = response['data']['SSRData'];
                /*---- Genearte Pax Detail for Seats---*/
                $scope.travellerJson = response['data']['SeatPaxData'];
                /*---- Genearte Pax Detail for Seats---*/

                $scope.SeatData = response.data.SeatData;

                setTimeout(() => {
                    jQuery(document).on('mouseover', ".available", function() {
                        jQuery(this).children(".seatdetails-tooltip").css("display", "block");
                    })
                    jQuery(document).on('mouseleave', ".available", function() {
                        jQuery(this).children(".seatdetails-tooltip").css("display", "none");
                    })
                }, 10);
                $scope.CurrencySymbol = response['data']['CurrencySymbol'];
                $scope.currencyCode = response['data']['currencyCode'];
                $scope.decimalPoint = response['data']['decimalPoint'];
                if ($scope.ssr_errorcode == 0) {
                    $("[addon-service-flight]").show();
                }
            });
        }

        $scope.get_ssr_info();

        let selssrdata = [];
        $scope.select_ssr_info = function(ssrtype, ssrjkey, paxkey, nopax, tripkey, segkey, value) {
            let matchstring = ssrtype + '-' + ssrjkey + '-' + paxkey + '-' + nopax + '-' + tripkey + '-' +
                segkey;

            let price = 0;
            let optionstring = '';
            if (value) {
                let optionvalue = value.split("@@");
                price = parseFloat(optionvalue[1]);
                optionstring = optionvalue[2];
            }

            let arr = {
                'ssrtype': ssrtype,
                'ssrjkey': ssrjkey,
                'paxkey': paxkey,
                'nopax': nopax,
                'tripkey': tripkey,
                'segkey': segkey,
                'price': price,
                'optionstring': optionstring,
                'matchstring': matchstring
            }
            selssrdata[matchstring] = arr;

            let obmealprice = 0;
            let ibmealprice = 0;
            let obbaggageprice = 0;
            let ibbaggageprice = 0;
            Object.keys(selssrdata).forEach(function(key) {
                if (selssrdata[key]['ssrtype'] == 'Meal') {
                    if (selssrdata[key]['ssrjkey'] == 0) {
                        obmealprice += selssrdata[key]['price'];
                    }
                    if (selssrdata[key]['ssrjkey'] == 1) {
                        ibmealprice += selssrdata[key]['price'];
                    }

                    if (selssrdata && selssrdata[key]['price']) {
                        if (document.getElementById(selssrdata[key]['matchstring'])) {
                            document.getElementById(selssrdata[key]['matchstring']).innerHTML =
                                "<span class='fs-12'>Meal Qunatity : " + selssrdata[key]['optionstring'] + " Platter</span> <span class='fs-12'>Meal Charges : " + $scope.CurrencySymbol + " " + selssrdata[key]['price'] + "</span>";
                        }

                    } else {
                        if (document.getElementById(selssrdata[key]['matchstring'])) {
                            document.getElementById(selssrdata[key]['matchstring']).innerHTML =
                                "<span class='fs-12'>Meal Qunatity : 0 Platter</span> <span class='fs-12'>Meal Charges :" + $scope.CurrencySymbol + "</span>";
                        }
                    }

                }
                if (selssrdata[key]['ssrtype'] == 'Baggage') {
                    if (selssrdata[key]['ssrjkey'] == 0) {
                        obbaggageprice += selssrdata[key]['price'];
                    }
                    if (selssrdata[key]['ssrjkey'] == 1) {
                        ibbaggageprice += selssrdata[key]['price'];
                    }

                    if (selssrdata && selssrdata[key]['price']) {
                        if (document.getElementById(selssrdata[key]['matchstring'])) {
                            document.getElementById(selssrdata[key]['matchstring']).innerHTML =
                                "<span class='fs-12'>Baggage Weight : " + selssrdata[key][
                                    'optionstring'
                                ] +
                                " Kg</span> <span class='fs-12'>Baggage Charges : " + $scope.CurrencySymbol + " " + selssrdata[
                                    key]['price'] + "</span>";
                        }
                    } else {
                        if (document.getElementById(selssrdata[key]['matchstring'])) {
                            document.getElementById(selssrdata[key]['matchstring']).innerHTML =
                                "<span class='fs-12'>Baggage Weight : 0 Kg</span> <span class='fs-12'>Baggage Charges : " + $scope.CurrencySymbol + "</span>";
                        }
                    }
                }

            });
            if (Object.keys($scope.FareInfoOB).length != 0) {
                $scope.FareInfoOB['FareBreakup']['Meal']['Value'] = obmealprice;
                $scope.FareInfoOB['FareBreakup']['Baggage']['Value'] = obbaggageprice;
                //$scope.FareInfoOB['TotalAmount']['Value']=parseFloat($scope.FareInfoOB['TotalAmount']['Value'])+parseFloat(obmealprice)+parseFloat(obbaggageprice);
            }
            if (Object.keys($scope.FareInfoIB).length != 0) {
                $scope.FareInfoIB['FareBreakup']['Meal']['Value'] = ibmealprice;
                $scope.FareInfoIB['FareBreakup']['Baggage']['Value'] = ibbaggageprice;

                //$scope.FareInfoIB['TotalAmount']['Value']=parseFloat($scope.FareInfoOB['TotalAmount']['Value'])+parseFloat(obmealprice)+parseFloat(obbaggageprice);
            }

            $scope.obmealprice = obmealprice;
            $scope.ibmealprice = ibmealprice;
            $scope.obbaggageprice = obbaggageprice;
            $scope.ibbaggageprice = ibbaggageprice;


        }
        $scope.seatactivetab = 0;
        $scope.selectseattab = function(jkey, segkey, Segment) {
                $scope.CLOSETootip();
                $scope.tootipstyle = {};
                $scope.seatactivetab = jkey + '' + segkey;
                $scope.finalselectedseat(jkey, Segment);

            },

            $scope.paxseatarray = [];
        $scope.tootipstyle = {};
        $scope.clickedseat = {};
        $scope.seatClicked = function(event, sObj) {
                if (sObj['SeatClass'].includes('booked')) {
                    return false;
                }

                var pos = getposition(event.target, "flightbox");
                $scope.tootipstyle = {
                    top: pos.top + "px",
                    left: pos.left + "px",
                    display: "block"
                };
                $scope.clickedseat = sObj;

            },
            $scope.selectseat = function(event, clickedseat, detail, Segment, jkey, segkey, paxkey, paxcountkey) {

                let segmentkey = Segment.Origin + '-' + Segment.Destination;
                if ($scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey]['Code']) {
                    if ($scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey]['Code'] == clickedseat['Code']) {
                        $scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey] = [];
                    } else {
                        let selectedindex = $scope.getPassengerIndex(jkey, clickedseat);
                        if (selectedindex != '') {
                            //remove previous seat
                            let previouskey = selectedindex.split('_');
                            $scope.travellerJson[previouskey[0]][previouskey[1]][previouskey[2]][previouskey[3]] = [];

                            //add again seat
                            $scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey] = clickedseat;

                        } else {
                            $scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey] = clickedseat;
                        }
                    }
                } else {

                    let selectedindex = $scope.getPassengerIndex(jkey, clickedseat);
                    if (selectedindex != '') {
                        //remove previous seat
                        let previouskey = selectedindex.split('_');
                        $scope.travellerJson[previouskey[0]][previouskey[1]][previouskey[2]][previouskey[3]] = [];

                        //add again seat
                        $scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey] = clickedseat;
                    } else {
                        //new seat;
                        $scope.travellerJson[jkey][paxkey][paxcountkey][segmentkey] = clickedseat;
                    }
                }
                $scope.finalselectedseat(jkey, Segment);
            },
            $scope.paxseatselected = [];
        $scope.paxseatob = [];
        $scope.paxseatib = [];
        $scope.obseatprice = 0;
        $scope.ibseatprice = 0

        let priceob = 0;
        let priceib = 0;
        $scope.finalselectedseat = function(jkey, Segment) {

                let segmentkey = Segment.Origin + '-' + Segment.Destination;
                let j = [];
                let s = [];
                let c = [];
                let price = 0;
                Object.keys($scope.travellerJson[jkey]).forEach(function(paxkey) {
                    Object.keys($scope.travellerJson[jkey][paxkey]).forEach(function(noofpax) {
                        Object.keys($scope.travellerJson[jkey][paxkey][noofpax]).forEach(function(noofseg) {

                            if ($scope.travellerJson[jkey][paxkey][noofpax][noofseg]['Price']) {
                                price += parseFloat($scope.travellerJson[jkey][paxkey][noofpax][noofseg]['Price']);
                            }

                            if (segmentkey == noofseg) {
                                c.push($scope.travellerJson[jkey][paxkey][noofpax][noofseg]['Code']);
                                s[noofseg] = c;
                                //j[jkey]=s;
                                if (jkey == 0) {
                                    $scope.paxseatob = s;
                                }
                                if (jkey == 1) {
                                    $scope.paxseatib = s;
                                }
                            }
                        });
                    });
                });
                $scope.paxseatselected = j;
                if (jkey == 0) {
                    $scope.paxseatselected = $scope.paxseatob;

                    $scope.FareInfoOB['FareBreakup']['Seat']['Value'] = price;
                    $scope.obseatprice = price;
                    priceob = price;
                }
                if (jkey == 1) {
                    $scope.paxseatselected = $scope.paxseatib;
                    if ($scope.FareInfoIB['FareBreakup']) {
                        $scope.FareInfoIB['FareBreakup']['Seat']['Value'] = price;
                        $scope.ibseatprice = price;
                    }
                    priceib = price;
                }

                if ($scope.searchRequest['JourneyType'] == 2 && $scope.searchRequest['IsDomestic'] === false) {
                    $scope.obseatprice = parseFloat(priceob) + parseFloat(priceib);
                    $scope.FareInfoOB['FareBreakup']['Seat']['Value'] = $scope.obseatprice;
                }
            },
            $scope.getPassengerIndex = function(jkey, clickedseat) {
                let segkey = clickedseat['Origin'] + '-' + clickedseat['Destination'];
                let selectedindex = "";
                Object.keys($scope.travellerJson[jkey]).forEach(function(paxkey) {
                    Object.keys($scope.travellerJson[jkey][paxkey]).forEach(function(noofpax) {
                        Object.keys($scope.travellerJson[jkey][paxkey][noofpax]).forEach(function(noofseg) {

                            if (noofseg == segkey && $scope.travellerJson[jkey][paxkey][noofpax][noofseg]['Code'] == clickedseat['Code']) {
                                selectedindex = jkey + '_' + paxkey + '_' + noofpax + '_' + noofseg;
                            }
                        });
                    });
                });

                return selectedindex;
            },
            $scope.CLOSETootip = function() {
                $scope.tootipstyle = {
                    display: "none"
                };
            }

        function getposition(elem, cont) {
            var classes = elem.offsetParent.classList.toString();
            var parent = elem;
            var top = 0;
            var left = 0;
            while (!classes.includes(cont)) {
                top += parent.offsetTop;
                left += parent.offsetLeft;
                parent = parent.offsetParent;
                classes = parent.classList.toString();
            }

            var selelement = document.querySelector('.seatdetails');
            var wanted_height = getHeight(selelement);
            var boxh = wanted_height + 40;

            var scrolloffset = document.getElementsByClassName("flightboxplan")[0].scrollLeft;
            $scope.tootipleft = left;
            return {
                top: top - boxh,
                left: left - scrolloffset - 102
            };
        }
        $scope.SelectCouponCode = function(value) {
            $scope.CouponCode = value;
            $scope.Applycoupon = value;
            $scope.CouponMessage = '';
            $scope.CouponErrorMessage = '';

            $scope.CouponLoading = true;
            let request = {
                'couponCode': $scope.CouponCode,
                'SearchTokenId': "<?php echo $SearchTokenId; ?>",
                'FareCode': "<?php echo $fareCode; ?>",
                'farecodereturn': "<?php echo $farecodereturn; ?>",
                'CouponId': $scope.CouponID
            }
            let callUrl = url + 'flight/promocode';
            $scope.callcoupon(request, callUrl);
        }
        $scope.submitCouponCode = function() {
            $scope.CouponCode = $("[name='couponCode']").val();
            $scope.CouponMessage = '';
            $scope.CouponErrorMessage = '';
            if ($scope.CouponCode != '') {
                $scope.CouponLoading = true;
                let request = {
                    'couponCode': $scope.CouponCode,
                    'SearchTokenId': "<?php echo $SearchTokenId; ?>",
                    'FareCode': "<?php echo $fareCode; ?>",
                    'farecodereturn': "<?php echo $farecodereturn; ?>",
                    'CouponId': $scope.CouponID
                }
                let callUrl = url + 'flight/promocode';
                $scope.callcoupon(request, callUrl);

            } else {
                $scope.CouponErrorMessage = 'Please Enter Coupon Code';
            }
        }
        $scope.removecoupon = function() {
            let request = {
                'SearchTokenId': "<?php echo $SearchTokenId; ?>",
                'FareCode': "<?php echo $fareCode; ?>",
                'farecodereturn': "<?php echo $farecodereturn; ?>",
                'CouponId': $scope.CouponID
            }
            let callUrl = url + 'flight/remove-promocode';
            $scope.callcoupon(request, callUrl);
            $scope.CouponCode = '';
            $scope.Applycoupon = null;

            $scope.CouponMessage = '';
            $scope.CouponErrorMessage = '';

            $('.fcoupon').prop('checked', false);
        }

        $scope.callcoupon = function(request, callUrl) {
            $http({
                method: "POST",
                url: callUrl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: $.param(request)
            }).then(function mySuccess(response) {
                $scope.CouponLoading = false;
                if (response.data.StatusCode == 0) {
                    $scope.CouponMessage = response.data.Message;
                    $scope.CouponID = response.data.CouponID;
                    if (response.data.FareBreakUpData) {
                        $scope.FareConfirmationData = response.data.FareBreakUpData;
                        $scope.FareConfirmationData = angular.fromJson($scope.FareConfirmationData);
                        if ($scope.FareConfirmationData.OB) {
                            $scope.FareInfoOB = $scope.FareConfirmationData.OB;
                        }
                        if ($scope.FareConfirmationData.IB) {
                            $scope.FareInfoIB = $scope.FareConfirmationData.IB;
                            $scope.TotalAmount = $scope.FareInfoIB.TotalAmount.Value + $scope.FareInfoOB.TotalAmount.Value;
                        }
                    }
                    if (Object.keys($scope.FareInfoOB).length != 0) {
                        $scope.FareInfoOB['FareBreakup']['Meal']['Value'] = $scope.obmealprice;
                        $scope.FareInfoOB['FareBreakup']['Baggage']['Value'] = $scope.obbaggageprice;
                        $scope.FareInfoOB['FareBreakup']['Seat']['Value'] = $scope.obseatprice;

                    }
                    if (Object.keys($scope.FareInfoIB).length != 0) {
                        $scope.FareInfoIB['FareBreakup']['Meal']['Value'] = $scope.ibmealprice;
                        $scope.FareInfoIB['FareBreakup']['Baggage']['Value'] = $scope.ibbaggageprice;
                        $scope.FareInfoOB['FareBreakup']['Seat']['Value'] = $scope.ibseatprice;
                    }

                } else {
                    $scope.CouponErrorMessage = response.data.ErrorMessage.couponCode;
                }
            });
        }
    });

    function getHeight(el) {
        var el_style = window.getComputedStyle(el),
            el_display = el_style.display,
            el_position = el_style.position,
            el_visibility = el_style.visibility,
            el_max_height = el_style.maxHeight.replace('px', '').replace('%', ''),

            wanted_height = 0;
        // if its not hidden we just return normal height
        if (el_display !== 'none' && el_max_height !== '0') {
            return el.offsetHeight;
        }

        // the element is hidden so:
        // making the el block so we can meassure its height but still be hidden
        el.style.position = 'absolute';
        el.style.visibility = 'hidden';
        el.style.display = 'block';

        wanted_height = el.offsetHeight;

        // reverting to the original values
        el.style.display = el_display;
        el.style.position = el_position;
        el.style.visibility = el_visibility;

        return wanted_height;
    }
</script>
<script>
    function net_fare_toggle(data) {
        if (data.checked) {
            $('.ppk-net-fare').show();
        } else {
            $('.ppk-net-fare').hide();
        }
    }
</script>

<script>
    /*    $(document).ready(function () {
        $("#").datepicker({
            beforeShow: function (input, inst) {
                $(".ui-datepicker").addClass('calendarOuter');
            }
        });
    }); */
</script>