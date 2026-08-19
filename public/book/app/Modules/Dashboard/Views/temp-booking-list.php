<section class="btravTripsBannerWrapper">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <ol>
                <li>My Account</li>
                <li>My Bookings</li>
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
                                    aria-selected="true"><i class="fa fa-suitcase"></i> Upcomingcheck-Ins
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false"><i class="fa fa-ban"></i>
                                Cancelled
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false"><i
                                        class="fa fa-check-circle-o"></i> Checked-in
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-Bookings-tab" data-bs-toggle="pill"
                                    data-bs-target="#all-Bookings" type="button" role="tab"
                                    aria-controls="all-Bookings" aria-selected="false">All Bookings
                            </button>
                        </li>
                    </ul>

                </div>
                <div class="BookingStatusWrappertabs">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                             aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img src="<?php echo site_url('webroot/img/upcoming-empty.png') ?>"
                                         class="img-fluid">
                                </div>
                                <div class="col-lg-9 text-start">
                                    <p class="emptyErr--heading">Looks empty, you've no upcoming bookings.</p>
                                    <p class="emptyErr--subHeading">When you book a trip, you will see your itinerary
                                        here.</p>
                                    <p>
                                        <a href="#">
                                            <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">Plan
                                                a trip
                                            </button>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                             aria-labelledby="pills-profile-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img src="<?php echo site_url('webroot/img/cancelled-empty.png') ?>"
                                         class="img-fluid">
                                </div>
                                <div class="col-lg-9 text-start">
                                    <p class="emptyErr--heading">Looks empty, you've no cancelled bookings.</p>
                                    <p class="emptyErr--subHeading">Great! Looks like you’ve no cancelled bookings.</p>
                                    <p>
                                        <a href="#">
                                            <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">Plan
                                                a trip
                                            </button>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                             aria-labelledby="pills-contact-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img src="<?php echo site_url('webroot/img/upcoming-empty.png') ?>"
                                         class="img-fluid">
                                </div>
                                <div class="col-lg-9 text-start">
                                    <p class="emptyErr--heading">Looks empty, you've no upcoming bookings.</p>
                                    <p class="emptyErr--subHeading">When you book a trip, you will see your itinerary
                                        here.</p>
                                    <p>
                                        <a href="#">
                                            <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">Plan
                                                a trip
                                            </button>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="all-Bookings" role="tabpanel"
                             aria-labelledby="all-Bookings-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img src="<?php echo site_url('webroot/img/completed-empty.png') ?>"
                                         class="img-fluid">
                                </div>
                                <div class="col-lg-9 text-start">
                                    <p class="emptyErr--heading">Looks empty, you've no completed bookings.</p>
                                    <p class="emptyErr--subHeading">Looks like You don’t have any completed trips.</p>
                                    <p>
                                        <a href="<?php echo site_url('flight')?>">
                                            <button class="font14 latoBold blueText myTripBtn myTripBtn--primary ">Plan
                                                a trip
                                            </button>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="BtravBookingtables">

                </div>
            </div>
        </div>
    </div>
</section>