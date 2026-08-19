<?php
if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
    $whitelabel_user_status = "active";
} else {
    $whitelabel_user_status = "inactive";
}
$whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];


?>
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo site_url("dashboard"); ?>" class="sidebar-brand">
            Admin<span></span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>


    <div class="sidebar-body ps">
        <ul class="nav" id="navigation">
            <li class="nav-item p_active <?php echo active_nav('Dashboard') ?>">
                <a href="<?php echo site_url("dashboard"); ?>" class="nav-link">
                    <i class="fa fa-dashboard"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Setting", "Setting_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#settings" role="button" aria-expanded="false"
                            aria-controls="settings">
                            <i class="fa fa-cog"></i>
                            <span class="link-title">Settings</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="settings">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("setting"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        Company Settings</a>
                                </li>

                                <?php if (permission_access("Setting", "user_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("setting/user-management"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> User Management</a>
                                    </li>
                                <?php } ?>



                                <?php if (permission_access("Setting", "offline_supplier_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("offline-issue-supplier"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Offline Supplier</a>
                                    </li>
                                <?php } ?>

                                <?php if (isset($whitelabel_setting_data['is_direct_website']) && $whitelabel_setting_data['is_direct_website'] == "active") { ?>

                                    <?php if (permission_access("Setting", "airport_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightsettings"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Airport List</a>
                                        </li>
                                    <?php } ?>

                                    <?php if (permission_access("Setting", "airlines_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightsettings/flight-airlines-list"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Airline List</a>
                                        </li>
                                    <?php } ?>

                                    <?php if ($whitelabel_setting_data['multi_currency'] == 'active') { ?>
                                        <?php //if (permission_access("Currency", "currency_list")) { 
                                        ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("currency"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                                Currency</a>
                                        </li>
                                        <?php //} 
                                        ?>
                                    <?php } ?>

                                    <?php if (permission_access("Setting", "flight_fare_type_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("flightfaretype"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Flight Fare Type</a>
                                        </li>
                                    <?php } ?>


                                    <?php if (permission_access("APISuppliers", "APISuppliers_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("supplier-management/api-supplier"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Supplier Setting</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($whitelabel_setting_data['payment_gateway_type'] == 'webpartner' && (isset($whitelabel_setting_data) && $whitelabel_setting_data['payment_gateway_name'] !== 'Default')) { ?>
                                    <?php if (permission_access("Setting", "payment_setting_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("payment-setting"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Payment Gateway Setting</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>



                                <?php if (permission_access("Setting", "convenience_fee_list") && (isset($whitelabel_setting_data) && $whitelabel_setting_data['payment_gateway_name'] !== 'Default')) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("convenience-fee"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Convenience Fee</a>
                                    </li>
                                <?php } ?>

                                <?php if (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active") { ?>
                                    <?php if (permission_access("Setting", "bank_account_list")) { ?>
                                        <li class="nav-item">
                                            <a href="<?php echo site_url("bankaccounts"); ?>" class="nav-link"><i
                                                    class="fa fa-circle"></i> Bank Account</a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if(0){ ?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("notification"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Agent Notification</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>

            <?php }
            } ?>

            <?php if ($whitelabel_user_status == "active" && ((isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") || (isset($whitelabel_setting_data['b2b_business']) && $whitelabel_setting_data['b2b_business'] == "active"))) { ?>

                <?php if (permission_access("Accounts", "Accounts_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#accounts" role="button" aria-expanded="false"
                            aria-controls="accounts">
                            <i class="fa fa-user"></i>
                            <span class="link-title">Accounts Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="accounts">
                            <ul class="nav sub-menu">
                                 <?php if(0) {?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("accounts/wl-payment-history"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Agent Payment History</a>
                                </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("sale-result"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Sales Report</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("webpartneraccounts/credit-notes"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Credit Notes</a>
                                </li>
                                <?php if (permission_access("OnlineTransaction", "OnlineTransaction_Module")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("online-transaction"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i>Online Transaction</a>
                                    </li>
                                <?php } ?>
                                <?php if (isset($whitelabel_setting_data['is_direct_website']) && $whitelabel_setting_data['is_direct_website'] == "inactive") { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("accounts/payment-processing"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Make Payment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("accounts/payment-history"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i>Payment History</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") { ?>
                <?php if (permission_access("Flight", "Flight_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#flight" role="button" aria-expanded="false"
                            aria-controls="flight">
                            <i class="fa fa-plane"></i>
                            <span class="link-title">Flight Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="flight">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-markup"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Markup</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-discount"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/bookings"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Booking List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/booking-calender"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Booking Calender</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-amendments"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Amendment List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight/flight-refunds"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Refund List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight-ticket-upload"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Upload Ticket</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("coupon/flight-coupon"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Flight Coupon List</a>
                                </li>


                                <li class="nav-item">
                                    <a href="<?php echo site_url("flight-top-routes"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Flight Top Routes</a>
                                </li>
                                <?php if (permission_access("WebCheckIn", "WebCheckIn_Module")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("web-check-in"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i>Web Check-in </a>
                                    </li>
                                <?php } ?>
                                <?php if (isset($whitelabel_setting_data['is_direct_website']) && $whitelabel_setting_data['is_direct_website'] == "active") { ?>

                                    <li class="nav-item">
                                        <a href="<?php echo site_url("flightoffline"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Offline Flight</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("supplier-management"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Supplier Management </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="<?php echo site_url("flight-ticket-import"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Import PNR </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_extranet_module']) && $whitelabel_setting_data['flight_extranet_module'] == "active") { ?>
                <?php if (permission_access("FlightExtranet", "FlightExtranet_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#private-fare" role="button" aria-expanded="false"
                            aria-controls="FlightExtranet">
                            <i class="fa fa-plane"></i>
                            <span class="link-title">Flight Extranet Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="private-fare">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/add-private-fare-page"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Private Fare </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/private-fare-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Private Fare List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/fare-rule"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Fare Rule</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("private-fare/fare-rule-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Fare Rule List</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['groupflight_Module']) && $whitelabel_setting_data['groupflight_Module'] == "active") { ?>
                <?php //if (permission_access("GroupFlight", "groupflight_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#groupflight" role="button" aria-expanded="false"
                            aria-controls="flight">
                            <i class="fa fa-plane"></i>
                            <span class="link-title">Group Flight Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="groupflight">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/groupflight-markup"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Group Flight Markup</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/groupflight-discount"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Group Flight Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/bookings"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Group Flight Booking List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/booking-calender"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Group Flight Booking Calender</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/groupflight-amendments"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Group Amendment List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("groupflight/groupflight-refunds"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Group Flight Refund List</a>
                                </li>

                                <!-- <li class="nav-item">
                                    <a href="<?php //echo site_url("coupon/Group flight-coupon"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Group Flight Coupon List</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php //} ?>


            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") { ?>
                <?php if (permission_access("Hotel", "Hotel_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#hotel" role="button" aria-expanded="false"
                            aria-controls="hotel">
                            <i class="fa fa-building"></i>
                            <span class="link-title">Hotel Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="hotel">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel/hotel-markup-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Markup</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel/hotel-discount-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel/bookings"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Booking List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel/hotel-amendments"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Amendment
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel/hotel-refunds"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Refund
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel-upload"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel Upload</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("coupon/hotel-coupon"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Hotel Coupon List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_extranet_module']) && $whitelabel_setting_data['hotel_extranet_module'] == "active") { ?>
                <?php if (permission_access("HotelExtranet", "HotelExtranet_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#Hotel_extranet" role="button" aria-expanded="false"
                            aria-controls="Hotel_extranet">
                            <i class="fa fa-hotel"></i>
                            <span class="link-title">Hotel Extranet Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="Hotel_extranet">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel-extranet/add-hotel"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Add Hotel</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel-extranet/hotel-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Hotel List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("hotel-extranet/hotel-extranet-settings"); ?>"
                                        class="nav-link"><i class="fa fa-circle"></i> Hotel Settings</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                <?php if (permission_access("Coupon", "Coupon_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#coupon" role="button" aria-expanded="false"
                            aria-controls="coupon">
                            <i class="fa-solid fa-tag"></i>
                            <span class="link-title"> Coupon Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="coupon">
                            <ul class="nav sub-menu">
                                <!-- <li class="nav-item">
                                    <a href="<?php echo site_url("coupon/flight-coupon"); ?>" class="nav-link"><i class="fa fa-circle"></i>Flight Coupon List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("coupon/hotel-coupon"); ?>" class="nav-link"><i class="fa fa-circle"></i>Hotel Coupon List</a>
                                </li> --->
                                
                                <li class="nav-item">
                                    <a href="<?php echo site_url("coupon/coupon-log"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>Coupon Log</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                <?php if (permission_access("Customer", "Customer_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("customer"); ?>" class="nav-link">
                            <i class="fa fa-user-circle"></i>
                            <span class="link-title">Customer Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Slider", "Slider_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("slider"); ?>" class="nav-link">
                            <i class="fa-solid fa-images"></i>
                            <span class="link-title">Slider Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Offers", "Offers_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("offers"); ?>" class="nav-link">
                            <i class="fa fa-gift"></i>
                            <span class="link-title">Offers Management</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
          
            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Page", "Page_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#PageManagement" role="button" aria-expanded="false"
                            aria-controls="PageManagement">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="link-title">Page Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="PageManagement">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("pages"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        <span>Page List</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("pages/menu-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> <span>Menu</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Query", "Query_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#Querymanagement" role="button"
                            aria-expanded="false" aria-controls="Querymanagement">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="link-title">Query Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="Querymanagement">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="<?php echo site_url("query"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        <span>Contact us List</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("query/wedding-query-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i>
                                        <span>Wedding Query List</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['b2c_business']) && $whitelabel_setting_data['b2c_business'] == "active") { ?>
                <?php if (permission_access("Blog", "Blog_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#blog" role="button" aria-expanded="false"
                            aria-controls="blog">
                            <i class="fa fa-blog"></i>
                            <span class="link-title">Blog Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="blog">
                            <ul class="nav sub-menu">
                                <?php if (permission_access("Blog", "blog_category_list")) { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo site_url("blog/blog-category-list"); ?>" class="nav-link"><i
                                                class="fa fa-circle"></i> Blog Category List</a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("blog"); ?>" class="nav-link"><i class="fa fa-circle"></i> Blog
                                        List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Feedback", "Feedback_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("feedback"); ?>" class="nav-link"> <i class="fa fa-comments"
                                aria-hidden="true"></i><span class="link-title"> Feedback Management</span></a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Career", "Career_Module")) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#career" role="button" aria-expanded="false"
                            aria-controls="career">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span class="link-title">Career Management</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-chevron-down link-arrow">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="career">
                            <ul class="nav sub-menu">


                                <li class="nav-item">
                                    <a href="<?php echo site_url("career/career-categories-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Career Categories List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url("career"); ?>" class="nav-link"><i class="fa fa-circle"></i>
                                        Career List</a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?php echo site_url("career/job-applications-list"); ?>" class="nav-link"><i
                                            class="fa fa-circle"></i> Career Job Applications List</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Newsletter", "Newsletter_Module")) { ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url("newsletter"); ?>" class="nav-link"> <i class="fa fa-newspaper"
                                aria-hidden="true"></i> <span class="link-title">Newsletter List</span> </a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if ($whitelabel_user_status == "active") { ?>
                <?php if (permission_access("Logs", "Logs_Module")) { ?>
                    <li class="nav-item <?php echo active_nav('logs') ?>">
                        <a href="<?php echo site_url("logs"); ?>" class="nav-link"> <i class="fa-solid fa-history"></i> <span
                                class="link-title">Logs Management</span></a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>
</nav>