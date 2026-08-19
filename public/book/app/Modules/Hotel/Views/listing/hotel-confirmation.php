<?php if ($hotelInfo) {
    $HotelRoomsDetails = json_decode($hotelInfo['hotel_rooms_details'], true); ?>
    <div class="travelimp__thanku">
        <div class="container">
            <div class="row">
                <div class="travelimp__thanku--bookingSuccess d-flex align-items-center">
                    <img src="">
                    <div class="travelimp__thanku--statuscontent">
                        Booking<span
                            class="<?php echo $hotelInfo['booking_status'] == "Confirmed" ? "tts-text-success" : "tts-text-danger" ?> travelimp__thanku--statussize"><?php echo $hotelInfo['booking_status']; ?></span>
                        <p class="travelimp__thanku--bookingIdShow"><b>Booking Ref Number : </b><a
                                href="<?php echo site_url('/hotel/details/') . $hotelInfo['booking_ref_number']; ?>"
                                target="_blank"
                                class="travelimp__thanku--redirectid"><?php echo $hotelInfo['booking_ref_number']; ?></a>
                        </p>
                        <?php if ($hotelInfo['confirmation_no']) { ?>
                            <p class="travelimp__thanku--bookingIdShow"><b> Hotel Confirmation No : </b><a href=""
                                    class="travelimp__thanku--redirectid"><?php echo $hotelInfo['confirmation_no']; ?></a>
                            </p>
                        <?php } ?>
                        <?php if ($hotelInfo['last_cancellation_date']) { ?>
                            <p class="travelimp__thanku--bookingIdShow">
                                Last Cancellation
                                Date: <?php echo display_custom_date_format($hotelInfo['last_cancellation_date'], true); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="flight-confirmation" ng-app="HotelDetailApp" ng-controller="hotelDetailCtrl">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="travelimp__thanku--leftside">
                        <div class="travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--flightFlex">
                                <div class="travelimp__thanku--flightFlex">
                                    <div class="travelimp__thanku--panelHeading travelimp__thanku--panelHeading-positionHandle"><?php echo $hotelInfo['hotel_name']; ?>
                                        <span class="travelimp__thanku--arrowIcon"> <?php for ($star = 1; $star <= $hotelInfo['star_rating']; $star++) { ?>
                                                <i class="fa fa-star"></i>
                                            <?php } ?></span> <span
                                            class="travelimp__thanku--tripdate travelimp__thanku--tripdate-positionHandle"> <?php echo date('d,M, Y D', strtotime($hotelInfo['check_in_date'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--airportlist">Check-in</li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo date('M,Y', strtotime($hotelInfo['check_in_date'])) ?></li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo date('d,D', strtotime($hotelInfo['check_in_date'])) ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-2">
                                            <ul class="travelimp__segmentwrap--stoparrowul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--stoparrowlist"><?php echo $night = $hotelInfo['no_of_nights']; ?>
                                                    Nights
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-5">
                                            <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                                <li class="travelimp__segmentwrap--airportlist">Check-out</li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo date('M,Y', strtotime($hotelInfo['check_out_date'])) ?></li>
                                                <li class="travelimp__segmentwrap--airportname"><?php echo date('d,D', strtotime($hotelInfo['check_out_date'])) ?></li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <ul class="travelimp__segmentwrap--airportul travelimp__segmentwrap">
                                        <li class="travelimp__segmentwrap--airportlist">ROOMS & GUESTS</li>
                                        <li class="travelimp__segmentwrap--airportname"><?php echo $hotelInfo['no_of_rooms'];
                                                                                        $guest = json_decode($hotelInfo['room_guests'], true);
                                                                                        ?>
                                            Room <?php echo $roomGuests = getNoguest($guest); ?>
                                            Guest
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12">
                                    <div class="travelimp__segmentwrap--baggageInfo">
                                        <p class="">Address </p>
                                        <i class="fa fa-map-marker"></i> <?php echo $hotelInfo['address1']; ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="travelimp__segmentwrap--btn">
                                        <button type="button" class="btn btn-default asr-viewbtn"
                                            onclick="showHideroomcancellationPolicy('roomcancellationPolicy','roomcancellationPolicyButton')"
                                            roomcancellationPolicyButton="true"><span>Cancellation Policy</span>&nbsp;<i
                                                class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none m0" roomcancellationPolicy="true">
                                <div class="table-responsive travelimp__thanku--responsivewrap">
                                    <table class="table table-bordered travelimp__thanku--tablewrap">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Room Type</th>
                                                <th>Cancellation Policy</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($HotelRoomsDetails) {
                                                foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) { ?>
                                                    <tr>
                                                        <td><?php echo $roomKey + 1; ?></td>
                                                        <td>
                                                            <span class="travelimp__thanku--paxwiseprint"></span><?php echo $HotelRooms['RoomTypeName']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $HotelRooms['CancellationPolicy']; ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading">Hotel Rooms Details
                                (<?php echo $hotelInfo['no_of_rooms']; ?>)
                            </div>
                            <div class="travelimp__thanku--panelbody">
                                <div class="table-responsive travelimp__thanku--responsivewrap">
                                    <table class="table table-bordered travelimp__thanku--tablewrap">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Room Type</th>
                                                <th>Amenities</th>
                                                <th>Guests</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($HotelRoomsDetails) {
                                                foreach ($HotelRoomsDetails as $roomKey => $HotelRooms) { ?>
                                                    <tr>
                                                        <td><?php echo $roomKey + 1; ?></td>
                                                        <td>
                                                            <span class="travelimp__thanku--paxwiseprint"></span><?php echo $HotelRooms['RoomTypeName']; ?>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                <?php if ($HotelRooms['Amenities']) { ?> Incl : </b><?php foreach ($HotelRooms['Amenities'] as $Amenities) { ?>
                                                                        <span>
                                                                            <?php echo $Amenities; ?>,
                                                                        </span>
                                                                <?php }
                                                                                                                } ?>
                                                            </p>
                                                        </td>
                                                        <td> <?php foreach ($HotelRooms['HotelPassenger'] as $HotelPassenger) { ?>
                                                                <span>
                                                                    <b> <?php echo $HotelPassenger['PaxType'] == 1 ? "Adult" : "Child"; ?> </b> : <?php echo $HotelPassenger['Title'] . " " . $HotelPassenger['FirstName'] . " " . $HotelPassenger['LastName']; ?>
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading">Hotel Policy</div>
                            <div class="travelimp__thanku--panelbody">
                                <div class="table-responsive travelimp__thanku--responsivewrap">
                                    <?php echo html_entity_decode(strip_tags($hotelInfo['hotel_policy_detail'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading">Hotel Norms</div>
                            <div class="travelimp__thanku--panelbody">
                                <div class="table-responsive travelimp__thanku--responsivewrap">
                                    <?php echo html_entity_decode(strip_tags($hotelInfo['hotel_norms'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading ">Fare Summary</div>
                            <ul class="travelimp__thanku--fareul ">

                                <?php foreach ($farebreakup as $key => $breakUp):
                                   
                                ?>

                                    <li class="travelimp__thanku--fareulborder">
                                        <p class="travelimp__thanku--fareullist justify-content-between">
                                            <span><?= $breakUp['LabelText'] ?></span> <span><?php echo $CurrencySymbol; ?> <?php echo $breakUp['Value']; ?></span>
                                        </p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                            <div class="travelimp__thanku--panelHeading ">Important Information</div>
                            <ul class="travelimp__termswrap--termsul">
                                <li class="travelimp__termswrap--termslist">1. You should carry a print-out of your
                                    booking and present for check-in.
                                </li>
                                <li class="travelimp__termswrap--termslist">2. Date &amp; Time is calculated based on
                                    the local time of city/destination.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php if ($hotelInfo['booking_status'] == "Confirmed" || $hotelInfo['booking_status'] == "Hold") { ?>
                        <div class="travelimp__thanku--rightside">
                            <ul class="travelimp__thanku--moreoptions">
                                <li class="travelimp__thanku--morelist"
                                    ng-click="getVoucherInvoiceModel('PrintVoucher')">
                                    <span class="travelimp__thanku--morecontent">Print Voucher</span>
                                    <span class="travelimp__thanku--moreicons fa fa-print"></span>
                                </li>
                                <li class="travelimp__thanku--morelist"
                                    ng-click="getVoucherInvoiceModel('EmailVoucher')">
                                    <span class="travelimp__thanku--morecontent"> Email Voucher</span>
                                    <span class="travelimp__thanku--moreicons fa fa-envelope"></span>
                                </li>
                                <li class="travelimp__thanku--morelist">
                                    <a class="travelimp__thanku--anchorlink" href="<?php echo site_url('hotel/get-invoice-ticket?type=CustomerInvoice&booking_ref_number=' . $hotelInfo['booking_ref_number']) ?>">
                                        <span class="travelimp__thanku--morecontent"> Invoice For Customer</span>
                                        <span class="travelimp__thanku--moreicons fa fa-file-alt"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($hotelInfo['booking_status'] == "Confirmed" || $hotelInfo['booking_status'] == "Hold") { ?>
                <div class="modal fade " id="HotelInvoiceVoucherOptionModelModel" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered ">
                        <div class="modal-content modal-content_confirmation ">
                            <div class="modal-header modal-header_confirmation">
                                <h5 class="modal-title modal-title_confirmation">{{HotelVoucherInvoiceTitle}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body modal-body_confirmation">
                                <form action="<?php echo site_url('hotel/get-voucher-invoice') ?>" method="get"
                                    name="printVoucherInvoiceForm">
                                    <input type="hidden" name="booking_ref_number"
                                        value="<?php echo $hotelInfo['booking_ref_number']; ?>">
                                    <input type="hidden" name="type" value="{{type}}">
                                    <div class="form-check form-check-inline" ng-if="PrintVoucher==1">

                                    </div>
                                    <div class="form-check" ng-if="EmailVoucher==1">
                                        <label class="form-check-label">Enter Email</label>
                                        <input class="form-control" type="text" placeholder="Enter Email" name="email">
                                    </div>
                                    <div class="form-check" ng-if="SmsTicket==1">
                                        <label class="form-check-label">Enter Mobile Number</label>
                                        <input class="form-control" type="text" placeholder="Enter Mobile Number"
                                            name="mobile_number">
                                    </div>
                                    <div class="form-check">
                                        <div class="text-center print-ticket-button">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
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
    let url = "<?php echo site_url(); ?>";
    var appHotelDetail = angular.module('HotelDetailApp', []);
    appHotelDetail.controller('hotelDetailCtrl', function($scope, $http) {
        $scope.type = "";
        $scope.getVoucherInvoiceModel = function(type) {
            var collection = document.getElementsByName("printVoucherInvoiceForm");
            for (var i = 0; i < collection.length; i++) {
                collection[i].removeAttribute("tts-form");
                collection[i].setAttribute("method", "get");
            }
            $scope.type = type;
            $scope.HotelVoucherInvoiceTitle = "";
            $scope.CustomerInvoice = 0;
            $scope.AgencyInvoice = 0;
            $scope.SmsTicket = 0;
            $scope.EmailVoucher = 0;
            $scope.PrintVoucher = 0;
            $scope.DownloadPdfVoucher = 0;
            var HotelInvoiceVoucherOptionModelModel = document.getElementById("HotelInvoiceVoucherOptionModelModel");
            if (HotelInvoiceVoucherOptionModelModel !== null) {
                new bootstrap.Modal(HotelInvoiceVoucherOptionModelModel).show();
            }
            if (type == 'PrintVoucher') {
                $scope.PrintVoucher = 1;
                $scope.HotelVoucherInvoiceTitle = "Print Voucher";
            } else if (type == 'EmailVoucher') {
                var setcollection = document.getElementsByName("printVoucherInvoiceForm");
                for (var i = 0; i < collection.length; i++) {
                    setcollection[i].setAttribute("tts-form", true);
                    setcollection[i].setAttribute("method", "post");
                }
                $scope.EmailVoucher = 1;
                $scope.HotelVoucherInvoiceTitle = "Email Voucher";
            }
        }

    });
</script>