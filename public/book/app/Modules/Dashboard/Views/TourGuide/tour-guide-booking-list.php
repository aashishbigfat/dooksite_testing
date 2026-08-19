<section class="btravTripsBannerWrapper">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <ol>
                <li>My Account</li>
                <li>My Bookings</li>
                <li>Tourguide Bookings</li>
            </ol>
        </div>
    </div>
</section>
<section class="BookingStatus">
    <div class="container">
        <div class="row">
            <!--sidebar-->
            <?php echo view('\Modules\Dashboard\Views\side-bar'); ?>
            <div class="col-lg-9">
                <div class="BookingStatusWrapper">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true"><i class="fa fa-suitcase"></i> Upcoming Check-Ins
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-Bookings-tab" data-bs-toggle="pill"
                                data-bs-target="#all-Bookings" type="button" role="tab" aria-controls="all-Bookings"
                                aria-selected="false">All Bookings
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-Amendments-tab" data-bs-toggle="pill"
                                data-bs-target="#all-Amendments" type="button" role="tab" aria-controls="all-Amendments"
                                aria-selected="false">Amendments
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-cancelled-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-cancelled" type="button" role="tab"
                                aria-controls="pills-cancelled" aria-selected="false"><i class="fa fa-ban"></i>
                                Cancelled
                            </button>
                        </li>

                    </ul>

                </div>
                <div class="BookingStatusWrappertabs">
                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <?php if (!empty($upcoming_lists)) { ?>
                                <div class="table-responsive-md">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ref. No.</th>
                                                <th scope="col">Guide Name</th>
                                                <th scope="col">Travel Date</th>
                                                <th scope="col">Monument Duration</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Booking Status</th>
                                                <th scope="col">Payment Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($upcoming_lists) && is_array($upcoming_lists)) {
                                                foreach ($upcoming_lists as $data) {
                                                    $convertBookingCurrencyRate = convertBookingCurrencyRate($data['total_price'], $data['booking_currency'], $data['default_currency'], $data['currency_rate']);
                                                   
                                                    $TotalConvertPrice = $convertBookingCurrencyRate['ConvertPrice'];
                                                    $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];

                                                    $TotalPrice = $CurrencySymbol . ' ' . $TotalConvertPrice;

                                                    if ($data['booking_status'] == 'Confirmed') {
                                                        $class = 'text-success';
                                                    } else {
                                                        $class = 'text-danger';
                                                    }

                                                    if ($data['payment_status'] == 'Successful') {
                                                        $payment_class = 'text-success';
                                                    } else {
                                                        $payment_class = 'text-danger';
                                                    }
                                                    ?>
                                                    <tr scope="row">
                                                        <td>
                                                            <a href="<?php echo site_url('tourguide/confirmation/') . $data['booking_ref_number']; ?>"
                                                                target="_blank">
                                                                <?php echo $data['booking_ref_number']; ?>
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <?php echo display_custom_date_format($data['travel_date']); ?>
                                                        </td>

                                                        <td>
                                                            <?php echo $data['guide_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $data['monument_duration']; ?>
                                                        </td>
                                                        <td> 
                                                            <?php echo $TotalPrice; ?>
                                                        </td>

                                                        <td>
                                                            <span class="<?php echo $class ?>">
                                                                <?php echo ucfirst($data['booking_status']); ?>
                                                            </span>
                                                        </td>


                                                        <td>
                                                            <span class="<?php echo $payment_class ?>">
                                                                <?php echo ucfirst($data['payment_status']); ?>
                                                            </span>
                                                        </td>
                                                        <?php if ($data['payment_status'] == 'Successful') { ?>
                                                                <td>
                                                                    <a href="javascript:void(0);" onclick='raise_amendment("<?php echo $data['booking_ref_number']; ?>")'>Raise Amendments</a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td>-</td>
                                                        <?php } ?>  
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr> <td colspan='9' class='text-center'><b>No Booking Found</b></td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <p class="pagiantion_text">Page
                                                    <?= $pager->getCurrentPage() ?>
                                                    of
                                                    <?= $pager->getPageCount() ?>, total
                                                    <?= $pager->getTotal() ?>
                                                    records
                                                    found
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($pager): ?>
                                                    <?= $pager->links() ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <img src="<?php echo site_url('webroot/img/completed-empty.png') ?>"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-lg-9 text-start">
                                        <p class="emptyErr--heading">Looks empty, you've no upcoming bookings.</p>
                                        <p class="emptyErr--subHeading">When you book a trip, you will see your
                                            itinerary here.</p>
                                        <p>
                                            <a href="<?php echo site_url('tourguide') ?>">
                                                <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">
                                                    Plan
                                                    a trip
                                                </button>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="tab-pane fade" id="all-Bookings" role="tabpanel" aria-labelledby="all-Bookings-tab"
                            tabindex="0">
                            <?php if (!empty($all_list)) { ?>
                                <div class="table-responsive-md">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ref. No.</th>
                                                <th scope="col">Guide Name</th>
                                                <th scope="col">Travel Date</th>
                                                <th scope="col">Monument Duration</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Booking Status</th>
                                                <th scope="col">Payment Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($all_list) && is_array($all_list)) {
                                                foreach ($all_list as $data) {
                                                    $convertBookingCurrencyRate = convertBookingCurrencyRate($data['total_price'], $data['booking_currency'], $data['default_currency'], $data['currency_rate']);
                                                   
                                                    $TotalConvertPrice = $convertBookingCurrencyRate['ConvertPrice'];
                                                    $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];

                                                    $TotalPrice = $CurrencySymbol . ' ' . $TotalConvertPrice;

                                                    if ($data['booking_status'] == 'Confirmed') {
                                                        $class = 'text-success';
                                                    } else {
                                                        $class = 'text-danger';
                                                    }

                                                    if ($data['payment_status'] == 'Successful') {
                                                        $payment_class = 'text-success';
                                                    } else {
                                                        $payment_class = 'text-danger';
                                                    }
                                                    ?>
                                                    <tr scope="row">
                                                        <td>
                                                            <a href="<?php echo site_url('tourguide/confirmation/') . $data['booking_ref_number']; ?>"
                                                                target="_blank">
                                                                <?php echo $data['booking_ref_number']; ?>
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <?php echo $data['guide_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo display_custom_date_format($data['travel_date']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $data['monument_duration']; ?>
                                                        </td>

                                                        <td>
                                                            <?php echo $TotalPrice; ?>
                                                        </td>

                                                        <td>
                                                            <span class="<?php echo $class ?>">
                                                                <?php echo ucfirst($data['booking_status']); ?>
                                                            </span>
                                                        </td>



                                                        <td>
                                                            <span class="<?php echo $payment_class ?>">
                                                                <?php echo ucfirst($data['payment_status']); ?>
                                                            </span>
                                                        </td>
                                                        <?php if ($data['payment_status'] == 'Successful') { ?>
                                                                <td>
                                                                    <a href="javascript:void(0);" onclick='raise_amendment("<?php echo $data['booking_ref_number']; ?>")'>Raise Amendments</a>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td>-</td>
                                                        <?php } ?>  
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr> <td colspan='9' class='text_center'><b>No Booking Found</b></td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <p class="pagiantion_text">Page
                                                    <?= $pager->getCurrentPage() ?>
                                                    of
                                                    <?= $pager->getPageCount() ?>, total
                                                    <?= $pager->getTotal() ?>
                                                    records
                                                    found
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($pager): ?>
                                                    <?= $pager->links() ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <img src="<?php echo site_url('webroot/img/completed-empty.png') ?>"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-lg-9 text-start">
                                        <p class="emptyErr--heading">Looks empty, you've no completed bookings.</p>
                                        <p class="emptyErr--subHeading">Looks like You don�t have any completed
                                            trips.</p>
                                        <p>
                                            <a href="<?php echo site_url('tourguide') ?>">
                                                <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">
                                                    Plan
                                                    a trip
                                                </button>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="tab-pane fade" id="all-Amendments" role="tabpanel"
                            aria-labelledby="all-Amendments-tab" tabindex="0">
                            <?php if (!empty($amendment_list)) { ?>
                                <div class="table-responsive-md">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ref. No.</th>
                                                <th scope="col">Amendment Id</th>
                                                <th scope="col">Amendment Type</th>
                                                <th scope="col">Amendment Status</th>
                                                <th scope="col">Booking Status</th>
                                                <th scope="col"> Remark</th>
                                                <th scope="col">Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($amendment_list) && is_array($amendment_list)) {
                                                foreach ($amendment_list as $data) {
                                                    if ($data['booking_status'] == 'Confirmed') {
                                                        $class = 'text-success';
                                                    } else {
                                                        $class = 'text-danger';
                                                    }

                                                    if ($data['payment_status'] == 'Successful') {
                                                        $payment_class = 'text-success';
                                                    } else {
                                                        $payment_class = 'text-danger';
                                                    }
                                                    if ($data['amendment_status'] == 'approved') {
                                                        $amendment_status = 'text-success';
                                                    } else {
                                                        $amendment_status = 'text-danger';
                                                    }
                                                    ?>
                                                    <tr scope="row">
                                                        <td>
                                                            <a href="<?php echo site_url('tourguide/confirmation/') . $data['booking_ref_number']; ?>"
                                                                target="_blank">
                                                                <?php echo $data['booking_ref_number']; ?>
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <?php echo ucfirst($data['id']); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo ucfirst($data['amendment_type']); ?>
                                                        </td>


                                                        <td>
                                                            <span class="<?php echo $amendment_status ?>">
                                                                <?php echo ucfirst($data['amendment_status']); ?>
                                                            </span>
                                                        </td>



                                                        <td>
                                                            <span class="<?php echo $class ?>">
                                                                <?php echo ucfirst($data['booking_status']); ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <?php echo $data['remark_from_web_partner']; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo site_url('/tourguide/amendments-details/') . $ticketData = dev_encode($data['id']); ?>"
                                                                target="_blank"><i class="tts-icon eye"> View</i></a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr> <td colspan='9' class='text-center'><b>No Booking Found</b></td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <p class="pagiantion_text">Page
                                                    <?= $amendment_pager->getCurrentPage() ?>
                                                    of
                                                    <?= $amendment_pager->getPageCount() ?>, total
                                                    <?= $amendment_pager->getTotal() ?>
                                                    records
                                                    found
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($amendment_pager): ?>
                                                    <?= $amendment_pager->links(); ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <img src="<?php echo site_url('webroot/img/completed-empty.png') ?>"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-lg-9 text-start">
                                        <p class="emptyErr--heading">Looks empty, you've no completed bookings.</p>
                                        <p class="emptyErr--subHeading">Looks like You don�t have any completed
                                            trips.</p>
                                        <p>
                                            <a href="<?php echo site_url('tourguide') ?>">
                                                <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">
                                                    Plan
                                                    a trip
                                                </button>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>


                        <div class="tab-pane fade" id="pills-cancelled" role="tabpanel"
                            aria-labelledby="pills-cancelled-tab" tabindex="0">
                            <?php if (!empty($cancelled_lists)) { ?>
                                <div class="table-responsive-md">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ref. No.</th>
                                                <th scope="col">Guide Name</th>
                                                <th scope="col">Travel Date</th>
                                                <th scope="col">Monument Duration</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Booking Status</th>
                                                <th scope="col">Payment Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($cancelled_lists) && is_array($cancelled_lists)) {
                                                foreach ($cancelled_lists as $data) {

                                                    $convertBookingCurrencyRate = convertBookingCurrencyRate($data['total_price'], $data['booking_currency'], $data['default_currency'], $data['currency_rate']);
                                                    
                                                    $TotalConvertPrice = $convertBookingCurrencyRate['ConvertPrice'];
                                                    $CurrencySymbol = $convertBookingCurrencyRate['CurrencySymbol'];

                                                    $TotalPrice = $CurrencySymbol . ' ' . $TotalConvertPrice;

                                                    if ($data['booking_status'] == 'Confirmed') {
                                                        $class = 'text-success';
                                                    } else {
                                                        $class = 'text-danger';
                                                    }

                                                    if ($data['payment_status'] == 'Successful') {
                                                        $payment_class = 'text-success';
                                                    } else {
                                                        $payment_class = 'text-danger';
                                                    }
                                                    ?>
                                                    <tr scope="row">
                                                        <td>
                                                            <a href="<?php echo site_url('tourguide/confirmation/') . $data['booking_ref_number']; ?>"
                                                                target="_blank">
                                                                <?php echo $data['booking_ref_number']; ?>
                                                            </a>
                                                        </td>

                                                        <td>
                                                            <?php echo $data['guide_name']; ?>
                                                        </td>

                                                        <td>
                                                            <?php echo display_custom_date_format($data['travel_date']); ?>
                                                        </td>

                                                        <td>
                                                            <?php echo $data['monument_duration']; ?>
                                                        </td>

                                                        <td> 
                                                            <?php echo $TotalPrice; ?>
                                                        </td>

                                                        <td>
                                                            <span class="<?php echo $class ?>">
                                                                <?php echo ucfirst($data['booking_status']); ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <span class="<?php echo $payment_class ?>">
                                                                <?php echo ucfirst($data['payment_status']); ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                onclick='raise_amendment("<?php echo $data['booking_ref_number']; ?>")'>Raise
                                                                Amendments</a>
                                                        </td>

                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr> <td colspan='9' class='text-center'><b>No Booking Found</b></td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-6 text-start">
                                                <p class="pagiantion_text">Page
                                                    <?= $pager->getCurrentPage() ?>
                                                    of
                                                    <?= $pager->getPageCount() ?>, total
                                                    <?= $pager->getTotal() ?>
                                                    records
                                                    found
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($pager): ?>
                                                    <?= $pager->links() ?>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <img src="<?php echo site_url('webroot/img/completed-empty.png') ?>"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-lg-9 text-start">
                                        <p class="emptyErr--heading">Looks empty, you've no cancelled bookings.</p>
                                        <p class="emptyErr--subHeading">Great! Looks like you�ve no cancelled
                                            bookings.</p>
                                        <p>
                                            <a href="<?php echo site_url('tourguide') ?>">
                                                <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">
                                                    Plan
                                                    a trip
                                                </button>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
                <div class="BtravBookingtables">

                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="raise-amendment-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="raise-amendment-modalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="raise-amendment-modalLabel">AMENDMENTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('tourguide/raise-amendment'); ?>" method="post" tts-form="true"
                name="flight-raise-amendment">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Booking Ref Number</label>
                        <input type="text" name="booking_ref_number" tts-booking-ref-no="true" value=""
                            class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Amendment Type</label>
                        <select class="form-select" name="amendment_type" data-validation="required"
                            data-validation-error-msg-required="Please select Amendment Type">
                            <option value="">Amendment Type</option>
                            <option value="cancellation">Cancellation</option>
                            <option value="full_refund">Full Refund</option>
                            <option value="reissue">Re-Issue</option>
                            <option value="correction">Correction</option>
                            <option value="no_show">No Show</option>
                            <option value="cancellation_quotation">Cancellation Quotation</option>
                        </select>
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