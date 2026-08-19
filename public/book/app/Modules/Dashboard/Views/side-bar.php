<?php
$router = service('router');
$class_name = $router->controllerName();
$methodName = $router->methodName(); //pr($methodName);

$name_first_char = ucfirst(substr(session()->get('wl_customer')['first_name'], 0, 1));

$name_last_char = ucfirst(substr(session()->get('wl_customer')['last_name'], 0, 1));

$profile_pic = session()->get('wl_customer')['profile_pic'];

?>
<div class="col-lg-3">
    <div class="dashboard_box">
        <div class="dashboard_img">
            <div class="proflPic">
                <?php if (!empty($profile_pic)) { ?>
                    <img src="<?php echo root_url . "uploads/customer/thumbnail/" . $profile_pic; ?>"
                        alt="<?php echo $name_first_char ?>" style="max-width: 100%; height: 100%;">
                <?php } else { ?>

                    <span class="proflPic_name"><?php ?><?php echo $name_first_char . $name_last_char ?></span>

                <?php } ?>
            </div>
        </div>
        <h4><?php echo ucfirst(session()->get('wl_customer')['first_name']) . ' ' . ucfirst(session()->get('wl_customer')['last_name']) ?>
        </h4>
        <ul class="flex-column dashboard_menu">
            <li class="nav-item">
                <a class="nav-link <?php if ($methodName == 'index') {
                    echo 'active';
                } ?>" href="<?php echo site_url('dashboard') ?>"><i class="fa fa-user"></i>
                    Profile</a>
            </li>
            <?php if (whitelabel['flight_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'flight_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/flight-bookings-list') ?>"><i
                            class="fa-solid fa-plane-departure"></i> Flight</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['hotel_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'hotel_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/hotel-bookings-list') ?>"><i
                            class="fa-solid fa-hotel"></i>Hotel</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['bus_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'bus_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/bus-booking-list') ?>"><i class="fa-solid fa-bus"></i>
                        Bus</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['holiday_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'holiday_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/holiday-bookings-list') ?>"><i
                            class="fa-solid fa-umbrella-beach"></i>Holidays</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['visa_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'visa_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/visa-bookings-list') ?>"><i
                            class="fa-solid fa-passport"></i>Visa</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['car_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'car_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/car-bookings-list') ?>"><i
                            class="fa-solid fa-car"></i>Car</a>
                </li>
            <?php } ?>

            <?php if (whitelabel['cruise_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'cruise_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/cruise-booking-list') ?>"><i
                            class="fa-solid fa-ship"></i>Cruise</a>
                </li>
            <?php } ?>

            <?php if (whitelabel['activities_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'activities_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/activities-booking-list') ?>"><i
                            class="fa-solid fa-binoculars"></i>Activities</a>
                </li>
            <?php } ?>

            <?php if (whitelabel['tourguide_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'tour_guide_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/tour-guide-booking-list') ?>"><svg width="22px"
                            height="22px" viewBox="0 -2 24 24" id="meteor-icon-kit__regular-guide" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M17 0C16.4477 0 16 0.44772 16 1V19C16 19.5523 16.4477 20 17 20C17.5523 20 18 19.5523 18 19V8.7808L23.2425 7.47014C23.6877 7.35885 24 6.95887 24 6.5V1C24 0.44772 23.5523 0 23 0H17zM22 5.71922L18 6.71922V2H22V5.71922z"
                                fill="#666"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7 11C9.20914 11 11 9.2091 11 7C11 4.79086 9.20914 3 7 3C4.79086 3 3 4.79086 3 7C3 9.2091 4.79086 11 7 11zM7 9C8.10457 9 9 8.1046 9 7C9 5.89543 8.10457 5 7 5C5.89543 5 5 5.89543 5 7C5 8.1046 5.89543 9 7 9z"
                                fill="#666"></path>
                            <path
                                d="M5 14C3.34315 14 2 15.3431 2 17V19C2 19.5523 1.55228 20 1 20C0.447715 20 0 19.5523 0 19V17C0 14.2386 2.23858 12 5 12H9C11.7614 12 14 14.2386 14 17V19C14 19.5523 13.5523 20 13 20C12.4477 20 12 19.5523 12 19V17C12 15.3431 10.6569 14 9 14H5z"
                                fill="#666"></path>
                        </svg> Tourguide</a>
                </li>
            <?php } ?>
            <?php if (whitelabel['biketour_module'] == 'active') { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($methodName == 'biketour_booking_list') {
                        echo 'active';
                    } ?>" href="<?php echo site_url('dashboard/biketour-bookings-list') ?>"><i
                            class="fa-solid fa-bicycle"></i>BikeTour</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($methodName == 'account_logs_list') {
                    echo 'active';
                } ?>" href="<?php echo site_url('dashboard/account-logs-list') ?>"><i
                        class="fa-solid fa-users"></i></i>Account Logs</a>
            </li>
        </ul>
    </div>
</div>