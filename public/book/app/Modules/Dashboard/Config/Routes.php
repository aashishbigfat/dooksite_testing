<?php

/**
 * Define  Routes For Dashboard
 */

$routes->group("dashboard", ["filter" => "auth"], function ($routes) {
  $routes->get('', '\Modules\Dashboard\Controllers\Dashboard::index');

  $routes->post('state', '\Modules\Dashboard\Controllers\Dashboard::state');

  $routes->post('city', '\Modules\Dashboard\Controllers\Dashboard::city');

  $routes->post('update-customer-profile/(:any)', '\Modules\Dashboard\Controllers\Dashboard::update_customer_profile');

  $routes->post('change-password/(:any)', '\Modules\Dashboard\Controllers\Dashboard::change_password');

  /*bus route start*/
  $routes->get('bus-booking-list', '\Modules\Dashboard\Controllers\Dashboard::bus_booking_list');
  $routes->get('details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::bus_booking_details');
  /*bus route end*/


  /** flight Booking Lists routes praveen*/
  $routes->get('flight-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::flight_booking_list');
  $routes->get('bookings-search', '\Modules\Dashboard\Controllers\Dashboard::bookingLists');
  $routes->get('details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::bookingDetails');
  $routes->post('raise-amendment', '\Modules\Dashboard\Controllers\Dashboard::raiseAmendment');
  $routes->get('amendment-itinerary', '\Modules\Dashboard\Controllers\Dashboard::amendmentItinerary');
  $routes->post('raise-amendment-type', '\Modules\Dashboard\Controllers\Dashboard::raiseAmendmentType');
  $routes->get('amendment-details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::amendmentDetails');
  /** flight Booking Lists routes praveen*/



  /** Hotel list routes praveen*/
  $routes->get('hotel-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::hotel_booking_list');
  $routes->get('confirmation/(:any)', '\Modules\Dashboard\Controllers\Dashboard::confirmation');
  $routes->get('details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::bookingDetails');
  $routes->post('raise-amendment', '\Modules\Dashboard\Controllers\Dashboard::raiseAmendment');
  /**end Hotel Booking routes praveen*/

  /** Holiday list routes praveen*/
  $routes->get('confirmation/(:any)', '\Modules\Dashboard\Controllers\Dashboard::confirmation');
  $routes->post('get-invoice-ticket', '\Modules\Dashboard\Controllers\Dashboard::get_invoice_ticket');
  $routes->get('holiday-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::holiday_booking_list');
  $routes->get('holiday-booking-details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::holiday_booking_detail');
  $routes->post('update-web-partner-markup-discount', '\Modules\Dashboard\Controllers\Dashboard::update_web_partner_markup_discount');
  /**end Holiday Booking routes praveen*/

  /** visa list routes praveen*/
  $routes->get('confirmation/(:any)', '\Modules\Dashboard\Controllers\Dashboard::confirmation');
  $routes->get('visa-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::visa_booking_list');

  /**end visa Booking routes praveen*/


  /** car list routes praveen*/

  $routes->get('car-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::car_booking_list');

  /**end car Booking routes praveen*/

  $routes->get('cruise-booking-list', '\Modules\Dashboard\Controllers\Dashboard::cruise_booking_list');

  /** tour guide list routes shiv*/

  $routes->get('tour-guide-booking-list', '\Modules\Dashboard\Controllers\Dashboard::tour_guide_booking_list');
  $routes->get('tour-details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::tour_booking_detail');


  /** tour guide list routes shiv*/

  // Activity list routes
  $routes->get('activities-booking-list', '\Modules\Dashboard\Controllers\Dashboard::activities_booking_list');

  /** Agent Account Lists routes start*/
  $routes->get('account-logs-list', '\Modules\Dashboard\Controllers\Dashboard::account_logs_list');

  $routes->post('account-details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::view_remark');

  /** Agent Account Lists routes end*/


  /** flight Booking Lists routes praveen*/
  $routes->get('biketour-bookings-list', '\Modules\Dashboard\Controllers\Dashboard::biketour_booking_list');
  $routes->get('details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::bookingDetails');
  $routes->post('raise-amendment', '\Modules\Dashboard\Controllers\Dashboard::raiseAmendment');
  $routes->get('amendment-itinerary', '\Modules\Dashboard\Controllers\Dashboard::amendmentItinerary');
  $routes->post('raise-amendment-type', '\Modules\Dashboard\Controllers\Dashboard::raiseAmendmentType');
  $routes->get('amendment-details/(:any)', '\Modules\Dashboard\Controllers\Dashboard::amendmentDetails');
  /** flight Booking Lists routes praveen*/
});
