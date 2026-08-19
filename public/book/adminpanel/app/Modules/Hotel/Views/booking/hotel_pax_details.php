<div class="flightDetailWrapper">
    <div class="container">
        <div class="row">
            <?php if (isset($blockResponse['Error']['ErrorCode']) && $blockResponse['Error']['ErrorCode'] == 0) {
                $hotelInfo = $hotelInfoResponse['Result'];
                $HotelRoomsDetails = $blockResponse['Result']['HotelRoomsDetails'];
                $isPANMandatory = array();
                $isPassportMandatory = array();
                $publishedFare = 0;
                $offeredFare = 0;
                $CommEarned = 0;
                $TDS = 0;
                $PANMandatory = 0;
                $PassportMandatory = 0;
                ?>
                <div class="col-lg-9 col-md-12 col-12">
                <form action="<?php echo site_url('hotel/validate-travellers'); ?>" method="post" method="post"
                      tts-form="true" name="hotel-booking">
                    <div class="flightLeftWrapper">
                        <div class="flightHeadWrap">
                            <p>Review Your Hotel Details</p>
                            <!-- <a href="javascript:void(0);">
                            &lt; Back To Search </a> -->
                        </div>

                        <div class="flightBookDetail">
                            <div class="flightPoint hotelpoint">
                                <div class="row align-items-center ">
                                    <div class="col-lg-12 col-md-12 col-12 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h4><?php echo get_uc_text_format($hotelInfo['HotelName']); ?>
                                                , <?php echo get_uc_text_format($hotelInfo['CountryName']); ?>
                                                <a href="javascript:voide(0);"><span class="d-block"><i
                                                                class="fa fa-map-marker"></i> <?php echo get_uc_text_format($hotelInfo['Address']); ?>
                                                    </span></a>
                                            </h4>
                                        </div>
                                        <?php if (isset($hotelInfo['HotelReviewRatings']) && $hotelInfo['HotelReviewRatings'] != "") { ?>
                                            <div>
                                                <p class="partialRef">
                                                    <?php if (isset($hotelInfo['HotelReviewUrl']) && $hotelInfo['HotelReviewUrl'] != "") { ?>
                                                        <a href="<?php echo $hotelInfo['HotelReviewUrl']; ?>"
                                                           target="_blank">Review
                                                            Rating <?php echo $hotelInfo['HotelReviewRatings']; ?> </a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);">Review
                                                            Rating <?php echo $hotelInfo['HotelReviewRatings']; ?> </a>
                                                    <?php } ?>
                                                </p>
                                            </div>
                                        <?php } ?>
                                        <div class="text-end">
                                            <p class="partialRef text-danger">
                                                <span>
                                                    <?php for ($star = 1; $star <= $hotelInfo['StarRating']; $star++) { ?>
                                                        <i class="fa fa-star"></i>
                                                    <?php } ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hoteldetail">
                                <div class="row align-items-center ">
                                    <div class="col-lg-3 col-md-3 col-12">
                                        <?php if (isset($hotelInfo['Images']) and $hotelInfo['Images'][0] != "unknown") { ?>
                                            <img src="<?php echo $hotelInfo['Images'][0] ?>" class="img-fluid">
                                        <?php } else { ?>
                                            <img src="<?php echo site_url('webroot/img/resort.png') ?>"
                                                 class="img-fluid">
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-12 ">
                                        <div class="my-3">
                                            <ul class="d-flex align-items-center justify-content-between">
                                                <li>
                                                    <h6>Check-in</h6>
                                                    <h3><?php echo date('M,Y', strtotime($searchRequest['CheckInDate'])) ?> </h3>
                                                    <h4><?php echo date('d,D', strtotime($searchRequest['CheckInDate'])) ?></h4>
                                                </li>
                                                <li>
                                                    <h6>Nights</h6>
                                                    <h5><?php echo $night = getDateDiffrence($searchRequest['CheckInDate'], $searchRequest['CheckOutDate']); ?></h5>
                                                </li>
                                                <li>
                                                    <h6>Check-out</h6>
                                                    <h3><?php echo date('M,Y', strtotime($searchRequest['CheckOutDate'])) ?> </h3>
                                                    <h4><?php echo date('d,D', strtotime($searchRequest['CheckOutDate'])) ?></h4>
                                                </li>
                                            </ul>
                                            <ul class="d-flex align-items-center justify-content-between">
                                                <li>
                                                    <h6>Rooms & Guests</h6>
                                                    <h3><?php echo $searchRequest['NoOfRooms']; ?>
                                                        Room <?php echo $roomGuests = getNoguest($searchRequest['RoomGuests']) ?>
                                                        Guest</h3>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12 ">
                                        <?php if ($HotelRoomsDetails) { ?>
                                            <ul class="hoteldetail_list_ul">
                                                <li>
                                                    <h6>Room Type</h6>
                                                </li>
                                                <li>
                                                    <h6>No.of Guests</h6>
                                                </li>
                                                <li>
                                                    <h6>Cancellation Policy</h6>
                                                </li>
                                            </ul>
                                            
                                            <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {

                                                $publishedFare = $publishedFare + $HotelRooms['Price']['PublishedPrice'];
                                                $offeredFare = $offeredFare + $HotelRooms['Price']['OfferedPrice'];
                                                $CommEarned = $CommEarned + $HotelRooms['Price']['AgentCommission'] + $HotelRooms['Price']['Discount'];
                                                $TDS = $TDS + $HotelRooms['Price']['TDS'];
                                                ?>
                                                <ul class="hoteldetail_list_ul">
                                                    <li>
                                                        <h6><?php echo get_uc_text_format($HotelRooms['RoomTypeName']); ?></h6>
                                                    </li>
                                                    <li>
                                                        <h6><?php echo $searchRequest['RoomGuests'][$roomKey]['Adult']; ?>
                                                            Adult <?php echo $searchRequest['RoomGuests'][$roomKey]['Child'] > 0 ? " / " . $searchRequest['RoomGuests'][$roomKey]['Child'] . " Child " : ""; ?></h6>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class=""
                                                           onclick='OpenHotelCommonModal("<?php echo $roomKey ?>","CancellationPolicy")'>Cancellation
                                                            Policy</a>
                                                    </li>
                                                </ul>
                                                
                                                <?php $isPANMandatory[] = $HotelRooms['IsPANMandatory'];
                                                $isPassportMandatory[] = $HotelRooms['IsPassportMandatory'];
                                            }
                                            $isPANMandatory = array_unique($isPANMandatory);
                                            $isPassportMandatory = array_unique($isPassportMandatory);
                                            $PANMandatory = $isPANMandatory[0] == 1 ? 1 : 0;
                                            $PassportMandatory = $isPassportMandatory[0] == 1 ? 1 : 0;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flightTraveller mt-4 mb-4">
                            <p>Traveller Details</p>

                            <div class="flightTravellerDetail">
                                <p>
                                    Please make sure you enter the Name as per your Government
                                    photo id.
                                </p>
                                <?php
                                foreach ($searchRequest['RoomGuests'] as $roomGuestKey => $roomGuest) { ?>

                                    <div class="pax-repeat-div my-3">
                                        <div class="card">
                                            <div class="card-header card_header">
                                                <span class="title ps-0">Room <?php echo $roomkey = $roomGuestKey + 1; ?></span>
                                            </div>
                                            <div class="card-body">
                                            <input type="hidden" name="ResultIndex" value="<?php echo $_GET['rindex'] ?>">
                                            <input type="hidden" name="SearchTokenId" value="<?php echo $_GET['token'] ?>">
                                            <input type="hidden" name="hcode" value="<?php echo $_GET['hcode'] ?>">
                                            <input type="hidden" name="rtype" value="<?php echo $_GET['rtype'] ?>">
                                                <input type="hidden" name="pancard_requird"
                                                       value="<?php echo $PANMandatory; ?>">
                                                <input type="hidden" name="passport_requird"
                                                       value="<?php echo $PassportMandatory; ?>">
                                                <?php for ($adult = 1; $adult <= $roomGuest['Adult']; $adult++) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12 mb-3 fw-bold">
                                                            Adult <?php echo $adult; ?>
                                                        </div>
                                                        <div class="col-lg-2 col-12 mb-3">
                                                            <select class="form-select form-control"
                                                                    name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][title]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Title</option>
                                                                <option value="Mr">Mr</option>
                                                                <option value="Ms">Ms</option>
                                                                <option value="Mrs">Mrs</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <input type="text" class="form-control"
                                                                   placeholder="First Name"
                                                                   name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][first_name]"
                                                                   data-validation="required alphanumeric"
                                                                   data-validation-error-msg-required="First Name is required"
                                                                   data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Last Name"
                                                                   name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][last_name]"
                                                                   data-validation="required alphanumeric"
                                                                   data-validation-error-msg-required="Last Name is required"
                                                                   data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                        </div>
                                                        <?php if ($PANMandatory) { ?>
                                                            <div class="col-lg-2 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="PAN Card"
                                                                       name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][pancard]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="PAN Card is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($PassportMandatory) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                Passport Detail
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Number"
                                                                       name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_no]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Number is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Issue Date"
                                                                       name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_issue_date]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Issue Date is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date" hotel_pass_issue =  "true" readonly>
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Expire Date"
                                                                       name="pax[<?php echo $roomkey; ?>][Adult][<?php echo $adult; ?>][passport_expire_date]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Expire Date is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date" hotel_pass_expiry =  "true" readonly>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    if ($adult != $roomGuest['Adult']) {
                                                        echo "<hr>";
                                                    }
                                                } ?>

                                                <?php for ($child = 1; $child <= $roomGuest['Child']; $child++) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12 mb-3 fw-bold">
                                                            Child <?php echo $child; ?>
                                                        </div>
                                                        <div class="col-lg-2 col-12 mb-3">
                                                            <select class="form-select form-control"
                                                                    name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][title]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Title</option>
                                                                <option value="Ms">Ms</option>
                                                                <option value="Master">Master</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <input type="text" class="form-control"
                                                                   placeholder="First Name"
                                                                   name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][first_name]"
                                                                   data-validation="required alphanumeric"
                                                                   data-validation-error-msg-required="First Name is required"
                                                                   data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Last Name"
                                                                   name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][last_name]"
                                                                   data-validation="required alphanumeric"
                                                                   data-validation-error-msg-required="Last Name is required"
                                                                   data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                        </div>
                                                        <?php if ($PANMandatory) { ?>
                                                            <div class="col-lg-2 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="PAN Card"
                                                                       name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][pancard]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="PAN Card is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                            </div>
                                                        <?php } ?>
                                                        <div class="col-lg-2 col-12">
                                                            <input type="text" class="form-control" placeholder="Age"
                                                                   name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][age]"
                                                                   data-validation="required number"
                                                                   data-validation-error-msg-required="Age is required"
                                                                   data-validation-error-msg-number="Please enter a valid Age"
                                                                   value="<?php echo "Age- " . $roomGuest['ChildAge'][($child - 1)]; ?> Yr"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                    <?php if ($PassportMandatory) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                Passport Detail
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Number"
                                                                       name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_no]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Number is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Issue Date"
                                                                       name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_issue_date]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Issue Date is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date" hotel_pass_issue =  "true" readonly>
                                                            </div>
                                                            <div class="col-lg-3 col-12">
                                                                <input type="text" class="form-control"
                                                                       placeholder="Passport Expire Date"
                                                                       name="pax[<?php echo $roomkey; ?>][Child][<?php echo $child; ?>][passport_expire_date]"
                                                                       data-validation="required alphanumeric"
                                                                       data-validation-error-msg-required="Passport Expire Date is required"
                                                                       data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date" hotel_pass_expiry =  "true" readonly>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                    if ($child != $roomGuest['Child']) {
                                                        echo "<hr>";
                                                    }
                                                } ?>

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="flightTravellerContact mb-4">
                            <p>Contact Details</p>

                            <div class="flightContactMail">
                                <div class="mailHead">
                                    <img class="flightContactImg"
                                         src="<?php echo site_url('webroot/img/svg_icon/flight-detail-mail.svg'); ?>"
                                         alt="mail">
                                    <p>Your ticket and flight details will be shared here</p>
                                </div>
                                <div class="row mt-3">

                                    <div class="col-lg-2 col-md-4 col-5">
                                        <select class="form-select form-control select_search" name="dial_code"
                                                data-validation="required"
                                                data-validation-error-msg="Dial Code is required">
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

                                    <div class="col-lg-4 col-md-4 col-7 ">
                                        <input type="text" class="form-control mb-3" name="mobile_number"
                                               placeholder="Mobile Number" data-validation="required number length"
                                               data-validation-length="7-15"
                                               value="<?php echo $web_partner_details['mobile_no'] ?>"
                                               data-validation-error-msg-required="Please enter Mobile Number"
                                               data-validation-error-msg-number="Please enter a valid Mobile Number"
                                               data-validation-error-msg-length="Please enter 7-15 digit mobile number."/>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <input type="text" class="form-control mb-3" name="email" placeholder="Email"
                                               data-validation="required email"
                                               value="<?php echo $web_partner_details['login_email'] ?>"
                                               data-validation-error-msg-required="Please enter Email"
                                               data-validation-error-msg-email="Please enter a valid Email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="flightGstNumber mt-4">
                                <h6>
                                    Use GSTIN for this booking(Optional)

                                    <button class="btn btn-outline-danger float-end" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse-bus-gst"
                                            aria-expanded="false" aria-controls="collapse-bus-gst"
                                            onclick="gst_detail(this,'add_gst_detail')">ADD
                                    </button>

                                </h6>
                                <span>Claim credit of GST charges. Your taxes may get updated post submitting your GST
                                    details.</span>
                                <div class="collapse" id="collapse-bus-gst">
                                    <div class="card card-body">
                                        <div class="row mt-3">
                                            <input type="hidden" name="add_gst_detail" id="add_gst_detail"
                                                   value="false">
                                            <div class="col-lg-4 col-md-4 col-12 mb-3">
                                                <input type="text" class="form-control" name="gst[number]"
                                                       placeholder="GST Number" data-validation="required alphanumeric"
                                                       data-validation-error-msg-required="Please enter GST Number"
                                                       data-validation-error-msg-alphanumeric="Please enter a valid GST Number">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[name]"
                                                       placeholder="Registered Company Name" data-validation="required"
                                                       data-validation-error-msg-required="Please enter Company Name"/>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[phone]"
                                                       placeholder="Registered Contact No"
                                                       data-validation="required number" data-validation-length="7-15"
                                                       data-validation-error-msg-required="Please enter Contact No"
                                                       data-validation-error-msg-number="Please enter a valid Contact No"
                                                       data-validation-error-msg-length="Please enter 7-15 digit mobile number."/>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[email]"
                                                       placeholder="Registered Email" data-validation="required email"
                                                       data-validation-error-msg-required="Please enter Email"
                                                       data-validation-error-msg-email="Please enter a valid Email"/>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <input type="text" class="form-control" name="gst[address]"
                                                       placeholder="Registered Address" data-validation="required"
                                                       data-validation-error-msg-required="Please enter Address"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="continuePayment mb-4">
                            <button>
                            <button type="submit" class="btn btn-link">Continue Payment</button>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="flightRightWrapper">
                        <p class="flightFare">Fare Summary</p>
                        <div class="flightFareTypes">
                            <div id="accordion">
                                <div class="card card1">
                                    <div class="card-header card_header">
                                        <div class="row ">
                                            <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>Published Price</li>
                                                </ul>
                                                <ul>
                                                    <li>₹ <?php echo $publishedFare; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header card_header">
                                        <div class="row ">
                                            <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>Offered Price</li>
                                                </ul>
                                                <ul>
                                                    <li>₹ <?php echo $offeredFare; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header card_header">
                                        <div class="row ">
                                            <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>Comm Earned (-)</li>
                                                </ul>
                                                <ul>
                                                    <li>₹ <?php echo $CommEarned; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header card_header">
                                        <div class="row ">
                                            <div class="col-lg-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>TDS (+)</li>
                                                </ul>
                                                <ul>
                                                    <li>₹ <?php echo $TDS; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header card_header">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li><strong>Total Amount</strong></li>
                                                </ul>
                                                <ul>
                                                    <li>
                                                        <span><strong>₹ <?php echo($offeredFare + $TDS); ?></strong></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <img src="<?php echo site_url('webroot/img/no-hotel-found.png'); ?>">
                    <h5 class="mt-4"><?php echo $blockResponse['Error']['ErrorMessage']; ?></h5>
                    <a href="<?php echo site_url('hotel'); ?>" class="btn btn-outline-danger">SELECT ANOTHER HOTEL</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!------Start Hotel Common modal------>
<div class="modal fade" id="HotelCommonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal_content">
            <div class="modal-header modal_header">
                <h5 class="modal-title">Cancellation Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="hotel-main">
                    <div class="row">
                        <div class="col-md-12" contentContainer="true">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------End Hotel Common modal------>
<!-- error Modal -->
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
<!-- error Modal -->
<script>
 function OpenHotelCommonModal(roomkey, type) {
    document.cookie = "roomkey = " + roomkey;
         var cancellationPolicy    = "<?php  $roomkey= isset($_COOKIE['roomkey'])?$_COOKIE['roomkey']:''; echo isset($HotelRoomsDetails[$roomkey]['CancellationPolicy'])?$HotelRoomsDetails[$roomkey]['CancellationPolicy']:''; ?>";
        $("#HotelCommonModal").modal('show');
        $("[contentContainer]").html(cancellationPolicy);
    } 
</script>


