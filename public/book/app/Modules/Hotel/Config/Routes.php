<?php

/**
 * Define Hotel Routes
 */
$routes->group("hotel", function ($routes) {
  /** Hotel Booking  routes*/
  $routes->get('city-list', '\Modules\Hotel\Controllers\Hotel::city_list');
  $routes->post('hotel-check-search-validtion', '\Modules\Hotel\Controllers\Hotel::check_search_validtion');
  $routes->get('/', '\Modules\Hotel\Controllers\Hotel::index');
  $routes->get('hotel-result', '\Modules\Hotel\Controllers\Hotel::hotel_result');
  $routes->get('get-hotel-lists', '\Modules\Hotel\Controllers\Hotel::get_hotel_lists');
  $routes->get('get-room-info', '\Modules\Hotel\Controllers\Hotel::get_room_info');
  $routes->get('get-hotel-info', '\Modules\Hotel\Controllers\Hotel::get_hotel_info');
  $routes->get('hotel-details', '\Modules\Hotel\Controllers\Hotel::hotel_details');
  $routes->get('hotel-rooms', '\Modules\Hotel\Controllers\Hotel::hotel_rooms');
  $routes->post('get-blockroom-info', '\Modules\Hotel\Controllers\Hotel::get_blockroom_info');
  $routes->post('validate-travellers', '\Modules\Hotel\Controllers\Hotel::validate_travellers');
  $routes->post('initiate-booking', '\Modules\Hotel\Controllers\Hotel::hotelBooking');
  $routes->get('payment-status/(:any)', '\Modules\Hotel\Controllers\Hotel::paymentStatus');
  $routes->get('print-ticket/(:any)', '\Modules\Hotel\Controllers\Hotel::printTicket');
  $routes->get('get-voucher-invoice', '\Modules\Hotel\Controllers\Hotel::getVoucherInvoice');
  $routes->post('get-voucher-invoice', '\Modules\Hotel\Controllers\Hotel::getVoucherInvoice');

  $routes->get('get-invoice-ticket', '\Modules\Hotel\Controllers\Hotel::getVoucherInvoice');


  $routes->get('error', '\Modules\Hotel\Controllers\Hotel::error');
  /**end Hotel Booking routes*/


  /** Hotel list routes*/
  $routes->get('bookings', '\Modules\Hotel\Controllers\Hotel::bookingLists');
  $routes->get('confirmation/(:any)', '\Modules\Hotel\Controllers\Hotel::confirmation');
  /* $routes->get('details/(:any)', '\Modules\Hotel\Controllers\Hotel::bookingDetails');*/
  $routes->post('raise-amendment', '\Modules\Hotel\Controllers\Hotel::raiseAmendment');
  /**end Hotel Booking routes*/

  /** Hotel Amendments Lists routes Karan*/
  $routes->get('amendments-details/(:any)', '\Modules\Hotel\Controllers\Hotel::amendmentsDetails');

  /** Hotel Amendments Lists routes Karan*/
  $routes->post('promocode', '\Modules\Hotel\Controllers\Hotel::promocode');

  $routes->post('remove-promocode', '\Modules\Hotel\Controllers\Hotel::removePromoCode');
});
