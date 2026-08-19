<style>
    .content .page-content .sale_bar .plusdesign {
        color: #ffb900;
        font-weight: 400;
    }

    .content .page-content .cart_info-field--title {
        color: #9c9c9c;
        margin-bottom: 10px;
    }

    .content .page-content .cart_info-field--detail {
        color: #242424;
    }

    .content .page-content .accordion-button {
        background: transparent !important;
        font-size: 15px;
        font-weight: 500;
    }

    .content .page-content .accordion-button:focus {
        box-shadow: none !important;
        outline: 0;
        border-color: #ccc;
    }

    .content .page-content .sale_bar h3 {
        font-size: 15px;
        font-weight: 500;
    }

    .content .page-content .accordion .accordion-item {
        margin-bottom: 1%;
    }

    .content .page-content .accordion .accordion-item .accordion-body h4 {
        font-size: 13px;
        font-weight: 500;
        color: #9c9c9c;
    }

    .content .page-content .accordion .accordion-item .accordion-body h4 span {
        color: #242424;
    }

    .content .page-content .accordion .accordion-item .accordion-body .flightRightWrapper .flightFare {
        font-size: 15px;
        font-weight: 500;
    }

    .content .page-content .accordion .accordion-button:not(.collapsed)::after {
        background-image: var(--bs-accordion-btn-icon) !important;
    }
</style>

<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-6">
                        <h3> Hotel Booking Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h3>
                    </div>
                    <div class="tts-col-6 text_right">
                        <a href="#"><i class="fa fa-print"></i> <span class="plusdesign">Invoice</span></a>
                    </div>
                </div>
            </div>
            <div class="accordion" id="hotelbookingaccordionpanels">
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                        Booking Basic Detail
                    </button>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                         aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div class="tts_row">
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title">Booking ID : <span
                                                class="cart_info-field--detail"> <?php echo $bookingDetail['booking_ref_number']; ?></span>
                                    </p>
                                </div>
                                <?php if ($bookingDetail['confirmation_no']) { ?>
                                    <div class="tts-col-3">
                                        Hotel Confirmation No: <?php echo $bookingDetail['confirmation_no']; ?>
                                    </div>
                                <?php } ?>
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title">Amount : <span
                                                class="cart_info-field--detail">₹ <?php echo $bookingDetail['total_price']; ?></span>
                                    </p>
                                </div>
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title"> Booking Status : <span
                                                class="cart_info-field--detail"><?php echo $bookingDetail['booking_status']; ?></span>
                                    </p>
                                </div>
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title">Channel Type : <span
                                                class="cart_info-field--detail"><?php echo $bookingDetail['booking_channel']; ?></span>
                                    </p>
                                </div>
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title">Created On : <span
                                                class="cart_info-field--detail"><?php echo date_created_format($bookingDetail['created']); ?></span>
                                    </p>
                                </div>
                                <div class="tts-col-3">
                                    <p class="cart_info-field--title">Booked By : <span
                                                class="cart_info-field--detail"><?php echo $bookingDetail['staff_name']; ?></span>
                                    </p>
                                </div>
                                <div class="tts-col-3">
                                    <a href="<?php echo site_url('/hotel/confirmation/') . $bookingDetail['booking_ref_number']; ?>"
                                       target="_blank">Booking Summary</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsetwo" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapsetwo">
                        Notes
                    </button>
                    <div id="panelsStayOpen-collapsetwo" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingtwo">
                        <div class="accordion-body">
                            <div class="tts_row">
                                <div class="tts-col-12">
                                    <h4>
                                        Name : <span><?php /*echo $bookingDetail['lead_passenger_name']; */?></span>
                                    </h4>
                                    <h4>
                                        Email : <span><?php /*echo $bookingDetail['contact_email_id']; */?></span>
                                    </h4>
                                    <h4>
                                        Mobile : <span><?php /*echo $bookingDetail['contact_number']; */?></span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsethree" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapsethree">
                        Amendments &nbsp; &nbsp; &nbsp; &nbsp;
                        <div class="text-end"><a href="javascript:void(0);" data-bs-toggle="modal"
                                                 data-bs-target="#hotel-raise-amendment">Raise Amendments</a></div>
                    </button>
                    <div id="panelsStayOpen-collapsethree" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingthree">


                        <?php if ($amendment_list) {
                            foreach ($amendment_list as $amendment) {
                                ?>
                                <div class="accordion-body">
                                    <div class="row">


                                        <div class="tts-col-3">
                                            <p class="cart_info-field--title">Generation Time : <span
                                                        class="cart_info-field--detail"><?php echo date_created_format($amendment['created']); ?></span>
                                            </p>
                                        </div>
                                        <div class="tts-col-3">
                                            <p class="cart_info-field--title">Amendment Id : <span
                                                        class="cart_info-field--detail"><?php echo $amendment['id']; ?></span>
                                            </p>
                                        </div>
                                        <div class="tts-col-3">
                                            <p class="cart_info-field--title">User : <span
                                                        class="cart_info-field--detail"><?php echo $amendment['staff_name']; ?></span>
                                            </p>
                                        </div>

                                        <div class="tts-col-3">
                                            <p class="cart_info-field--title">Status : <span
                                                        class="cart_info-field--detail"><?php echo ucfirst($amendment['amendment_status']); ?></span>
                                            </p>
                                        </div>
                                        <?php if ($amendment['remark_from_web_partner']) { ?>
                                            <div class="tts-col-3">
                                                <p class="cart_info-field--title">Remark : <span
                                                            class="cart_info-field--detail"><?php echo $amendment['remark_from_web_partner']; ?></span>
                                                </p>
                                            </div>
                                        <?php } ?>

                                        <?php if ($amendment['remark_from_super_admin']) { ?>
                                            <div class="tts-col-3">
                                                <p class="cart_info-field--title">Remark : <span
                                                            class="cart_info-field--detail"><?php echo $amendment['remark_from_super_admin']; ?></span>
                                                </p>
                                            </div>
                                        <?php } ?>


                                        <div class="tts-col-3">
                                            <p class="cart_info-field--title">Type : <span
                                                        class="cart_info-field--detail"><?php echo ucwords(str_replace('_', ' ', $amendment['amendment_type'])); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            <?php }
                        } ?>

                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsefour" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapsefour">
                        Hotel Details
                    </button>
                    <div id="panelsStayOpen-collapsefour" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingfour">
                        <div class="accordion-body">
                            <div class="row">
                                <?php
                                $HotelRoomsDetails = json_decode($bookingDetail['hotel_rooms_details'], true);
                                ?>
                                <div class="col-lg-9 col-md-12 col-12">
                                    <div class="flightLeftWrapper">
                                        <div class="flightBookDetail">
                                            <div class="flightPoint hotelpoint">
                                                <div class="row align-items-center ">
                                                    <div class="col-lg-12 col-md-12 col-12 d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <h4><?php echo $bookingDetail['hotel_name']; ?>
                                                                <a href="javascript:voide(0);"><span class="d-block"><i
                                                                                class="fa fa-map-marker"></i> <?php echo $bookingDetail['address1']; ?>
                                             </span></a>
                                                            </h4>
                                                        </div>
                                                        <div class="text-end">
                                                            <p class="partialRef text-danger">
                                             <span>
                                             <?php for ($star = 1; $star <= $bookingDetail['star_rating']; $star++) { ?>
                                                 <i class="fa fa-star"></i>
                                             <?php } ?>
                                             </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hoteldetail p-2">
                                                <div class="row align-items-center ">
                                                    <div class="col-lg-12 col-md-12 col-12 ">
                                                        <div class="my-3">
                                                            <ul class="d-flex align-items-center justify-content-between">
                                                                <li>
                                                                    <h6>Check-in</h6>
                                                                    <h3><?php echo date('M,Y', strtotime($bookingDetail['check_in_date'])) ?> </h3>
                                                                    <h4><?php echo date('d,D', strtotime($bookingDetail['check_out_date'])) ?></h4>
                                                                </li>
                                                                <li>
                                                                    <h6>Nights</h6>
                                                                    <h5><?php echo $night = $bookingDetail['no_of_nights']; ?></h5>
                                                                </li>
                                                                <li class="text-end">
                                                                    <h6>Check-out</h6>
                                                                    <h3><?php echo date('M,Y', strtotime($bookingDetail['check_out_date'])) ?> </h3>
                                                                    <h4><?php echo date('d,D', strtotime($bookingDetail['check_out_date'])) ?></h4>
                                                                </li>
                                                            </ul>
                                                            <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                                <li>
                                                                    <h6>ROOMS & GUESTS</h6>
                                                                    <h3><?php echo $bookingDetail['no_of_rooms'];
                                                                        $guest = json_decode($bookingDetail['room_guests'], true);
                                                                        ?>
                                                                        Room <?php echo $roomGuests = getNoguest($guest); ?>
                                                                        Guest
                                                                    </h3>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-12 ">
                                                        <?php if ($HotelRoomsDetails) { ?>
                                                            <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                                <li>
                                                                    <h6>Room Type</h6>
                                                                </li>
                                                                <li>
                                                                    <h6>Amenities</h6>
                                                                </li>
                                                                <li>
                                                                    <h6> Guests</h6>
                                                                </li>
                                                            </ul>
                                                            <hr>
                                                            <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {
                                                                ?>
                                                                <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                                    <li>
                                                                        <h6><?php echo $HotelRooms['RoomTypeName']; ?></h6>
                                                                    </li>
                                                                    <li>
                                                                        <h6><?php if ($HotelRooms['Amenities']) { ?> Incl : </b><?php foreach ($HotelRooms['Amenities'] as $Amenities) { ?>
                                                                                <span>
                                                <?php echo $Amenities; ?>,
                                                </span>
                                                                            <?php }
                                                                            } ?>
                                                                        </h6>
                                                                    </li>
                                                                    <li>
                                                                        <?php foreach ($HotelRooms['HotelPassenger'] as $HotelPassenger) { ?>
                                                                            <span>
                                             <b> <?php echo $HotelPassenger['PaxType'] == 1 ? "Adult" : "Child"; ?> </b> : <?php echo $HotelPassenger['Title'] . " " . $HotelPassenger['FirstName'] . " " . $HotelPassenger['LastName']; ?>
                                             </span>
                                                                        <?php } ?>
                                                                    </li>
                                                                </ul>
                                                                <hr>
                                                                <?php
                                                            }
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flightBookDetail">
                                            <p>Cancellation Policy</p>
                                            <div class="col-lg-12 col-md-12 col-12 ">
                                                <?php if ($HotelRoomsDetails) { ?>
                                                    <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                        <li>
                                                            <h6>Room Type</h6>
                                                        </li>
                                                        <li>
                                                            <h6>Cancellation Policy</h6>
                                                        </li>
                                                    </ul>
                                                    <hr>
                                                    <?php foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) {
                                                        ?>
                                                        <ul class="mt-3 d-flex align-items-center justify-content-between">
                                                            <li>
                                                                <h6><?php echo $HotelRooms['RoomTypeName']; ?></h6>
                                                            </li>
                                                            <li>
                                                                <?php echo $HotelRooms['CancellationPolicy']; ?>
                                                            </li>
                                                        </ul>
                                                        <hr>
                                                        <?php
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
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
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($bookingDetail['paymentInfo']) && $bookingDetail['paymentInfo']) {
                    $paymentInfo = json_decode($bookingDetail['paymentInfo'], true);
                    ?>
                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapsefive" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapsefive">
                            Payment Info
                        </button>
                        <div id="panelsStayOpen-collapsefive" class="accordion-collapse collapse"
                             aria-labelledby="panelsStayOpen-headingfive">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="responcive_table table_box_shadow">
                                            <table class="table-strip divName">
                                                <thead>
                                                <tr>
                                                    <th>Ref. Number</th>
                                                    <th>Remark</th>
                                                    <th>Credit</th>
                                                    <th>Debit</th>
                                                    <th>Type</th>
                                                    <th>Created</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if (!empty($paymentInfo) && is_array($paymentInfo)) {
                                                    foreach ($paymentInfo as $data) {
                                                        ?>
                                                        <tr>
                                                            <td> <?php echo $data['acc_ref_number']; ?></td>
                                                            <td><?php echo $data['remark']; ?></td>
                                                            <td> ₹ <?php echo $data['credit']; ?></td>
                                                            <td> ₹ <?php echo $data['debit']; ?></td>
                                                            <td><?php echo ucfirst($data['action_type']); ?></td>
                                                            <td>
                                                                <?php echo date_created_format($data['created']); ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } else {
                                                    echo "<tr> <td colspan='6' class='text_center'><b>No Booking Found</b></td></tr>";
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapsesix" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapsesix">
                        Contact Detail
                    </button>
                    <div id="panelsStayOpen-collapsesix" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingsix">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>
                                        Name : <span><?php echo $bookingDetail['lead_passenger_name']; ?></span>
                                    </h4>
                                    <h4>
                                        Email : <span><?php echo $bookingDetail['contact_email_id']; ?></span>
                                    </h4>
                                    <h4>
                                        Mobile: <span><?php echo $bookingDetail['contact_number']; ?></span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="hotel-raise-amendment" tabindex="-1" aria-labelledby="hotel-raise-amendmentLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hotel-raise-amendmentLabel">AMENDMENTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('hotel/raise-amendment'); ?>" method="post"
                  tts-form="true" name="hotel-raise-amendment">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Amendment Type</label>
                        <select class="form-select" name="amendment_type">
                            <option value="">Amendment Type</option>
                            <option value="cancellation">Cancellation</option>
                            <option value="full_refund">Full Refund</option>
                            <option value="cancellation_quotation">Cancellation Quotation</option>
                            <option value="correction">Correction</option>
                        </select>
                        <input type="hidden" name="booking_ref_number"
                               value="<?php echo $bookingDetail['booking_ref_number']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Remark:</label>
                        <textarea class="form-control" name="remark" rows="3" cols="15"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Raise</button>
                </div>
            </form>
        </div>
    </div>
</div>