<?php

/**
 * Define SuperAdminMarkupDiscount Routes
 */

$routes->group("coupon", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::index');
    $routes->get('get-airports', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::get_airports');
    $routes->get('get-airline', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::get_airline');

    /** SuperAdminFlightCoupon routes*/
    $routes->get('flight-coupon', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::flight_coupon');
    $routes->post('flight-coupon-view', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::flight_coupon_view');
    $routes->post('add-coupon', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::add_coupon');
    $routes->post('coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::coupon_status_change');
    $routes->post('remove-coupon', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::remove_coupon');
    $routes->match(['POST'], 'coupon-flight-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminFlightCoupon::coupon_flight_details');
    /** SuperAdminFlightCoupon routes*/

    /** SuperAdminHotelCoupon routes*/
    $routes->get('hotel-coupon', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::hotel_coupon');
    $routes->post('hotel-coupon-view', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::hotel_coupon_view');
    $routes->post('add-coupon-hotel', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::add_coupon_hotel');
    $routes->post('hotel-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::hotel_coupon_status_change');
    $routes->post('remove-hotel-coupon', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::remove_hotel_coupon');
    $routes->match(['POST'], 'coupon-hotel-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminHotelCoupon::coupon_hotel_details');
    /** SuperAdminHotelCoupon routes*/

    /** SuperAdminBusCoupon routes*/
    $routes->get('bus-coupon', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::bus_coupon');
    $routes->post('bus-coupon-view', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::bus_coupon_view');
    $routes->post('add-coupon-bus', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::add_coupon_bus');
    $routes->post('bus-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::bus_coupon_status_change');
    $routes->post('remove-bus-coupon', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::remove_bus_coupon');
    $routes->match(['POST'], 'coupon-bus-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminBusCoupon::coupon_bus_details');
    /** SuperAdminBusCoupon routes*/

    /** SuperAdminHolidayCoupon routes*/
    $routes->get('holiday-coupon', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::holiday_coupon');
    $routes->post('holiday-coupon-view', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::add_holiday_coupon_view');
    $routes->post('add-coupon-holiday', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::add_coupon_holiday');
    $routes->post('holiday-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::holiday_coupon_status_change');
    $routes->post('remove-holiday-coupon', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::remove_holiday_coupon');
    $routes->match(['POST'], 'coupon-holiday-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminHolidayCoupon::coupon_holiday_details');
    /** SuperAdminHolidayCoupon routes*/


    /** SuperAdminTourGuideCoupon routes*/
    $routes->get('tour-guide-coupon', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::tour_guide_coupon');
    $routes->post('tour-guide-coupon-view', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::add_tour_guide_coupon_view');
    $routes->post('add-coupon-tour-guide', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::add_coupon_tour_guide');
    $routes->post('tour-guide-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::tour_guide_coupon_status_change');
    $routes->post('remove-tour-guide-coupon', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::remove_tour_guide_coupon');
    $routes->match(['POST'], 'coupon-tourguide-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminTourGuideCoupon::coupon_tourguide_details');
    /** SuperAdminTourGuideCoupon routes*/

    /** SuperAdminActivitiesCoupon routes*/
    $routes->get('activities-coupon', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::tour_activities_coupon');
    $routes->post('activities-coupon-view', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::add_activities_coupon_view');
    $routes->post('add-coupon-activities', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::add_coupon_activities');
    $routes->post('activities-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::activities_coupon_status_change');
    $routes->post('remove-activities-coupon', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::remove_activities_coupon');
    $routes->match(['POST'], 'coupon-activities-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminActivitiesCoupon::coupon_activities_details');
    /** SuperAdminActivitiesCoupon routes*/


    /** SuperAdminVisaCoupon routes*/
    $routes->get('visa-coupon', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::visa_coupon');
    $routes->post('visa-coupon-view', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::add_visa_coupon_view');
    $routes->post('add-coupon-visa', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::add_coupon_visa');
    $routes->post('visa-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::visa_coupon_status_change');
    $routes->post('remove-visa-coupon', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::remove_visa_coupon');
    $routes->match(['POST'], 'coupon-visa-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminVisaCoupon::coupon_visa_details');
    /** SuperAdminActivitiesCoupon routes*/

    /** SuperAdminCarCoupon routes*/
    $routes->get('car-coupon', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::car_coupon');
    $routes->post('car-coupon-view', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::car_coupon_view');
    $routes->post('add-coupon-car', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::add_coupon_car');
    $routes->post('car-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::car_coupon_status_change');
    $routes->post('remove-car-coupon', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::remove_car_coupon');
    $routes->match(['POST'], 'coupon-car-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminCarCoupon::coupon_car_details');
    /** SuperAdminCarCoupon routes*/

    /** SuperAdminCruiseCoupon routes*/
    $routes->get('cruise-coupon', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::cruise_coupon');
    $routes->post('cruise-coupon-view', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::cruise_coupon_view');
    $routes->post('add-coupon-cruise', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::add_coupon_cruise');
    $routes->post('cruise-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::cruise_coupon_status_change');
    $routes->post('remove-cruise-coupon', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::remove_cruise_coupon');
    $routes->match(['POST'], 'coupon-cruise-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminCruiseCoupon::coupon_cruise_details');
    /** SuperAdminCruiseCoupon routes*/


    /** SuperAdminUmrahCoupon routes*/
    $routes->get('umrah-coupon', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::umrah_coupon');
    $routes->post('umrah-coupon-view', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::add_umrah_coupon_view');
    $routes->post('add-coupon-umrah', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::add_coupon_umrah');
    $routes->post('umrah-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::umrah_coupon_status_change');
    $routes->post('remove-umrah-coupon', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::remove_umrah_coupon');
    $routes->match(['POST'], 'coupon-umrah-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminUmrahCoupon::coupon_umrah_details');
    /** SuperAdminUmrahCoupon routes*/


    /** SuperAdminHajjCoupon routes*/
    $routes->get('hajj-coupon', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::hajj_coupon');
    $routes->post('hajj-coupon-view', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::add_hajj_coupon_view');
    $routes->post('add-coupon-hajj', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::add_coupon_hajj');
    $routes->post('coupon-hajj-details/(:any)', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::coupon_hajj_details');
    $routes->post('hajj-coupon-status-change', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::hajj_coupon_status_change');
    $routes->post('remove-hajj-coupon', '\Modules\Coupon\Controllers\SuperAdminHajjCoupon::remove_hajj_coupon');
    /** SuperAdminHajjCoupon routes*/



    /** SuperAdminCouponLogs routes*/
    $routes->get('coupon-log', '\Modules\Coupon\Controllers\SuperAdminCouponLog::coupon_log');
});
