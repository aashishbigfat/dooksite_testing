<?php

require_once ('HotelUploadRoutes.php');

/**
 * Define Hotel Routes
 */
$routes->group("hotel", ["filter" => "auth"], function ($routes) {

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
  $routes->post('block-hotel', '\Modules\Hotel\Controllers\Hotel::blockHotel');
  $routes->get('error', '\Modules\Hotel\Controllers\Hotel::error');
  /**end Hotel Booking routes*/


  /** Hotel list routes*/
  $routes->get('bookings', '\Modules\Hotel\Controllers\Hotel::bookingLists');
  $routes->get('confirmation/(:any)', '\Modules\Hotel\Controllers\Hotel::confirmation');
  $routes->get('details/(:any)', '\Modules\Hotel\Controllers\Hotel::bookingDetails');
  $routes->post('raise-amendment', '\Modules\Hotel\Controllers\Hotel::raiseAmendment');
  /**end Hotel Booking routes*/

  /** Hotel Amendments Lists routes Karan*/
  $routes->get('hotel-refunds', '\Modules\Hotel\Controllers\Hotel::hotelRefundLists');
  $routes->get('amendments-details/(:any)', '\Modules\Hotel\Controllers\Hotel::amendmentsDetails');
  $routes->get('hotel-amendments', '\Modules\Hotel\Controllers\Hotel::hotelAmendmentLists');
  $routes->post('hotel-refund-close', '\Modules\Hotel\Controllers\Hotel::hotelRefundClose');
  /** Hotel Amendments Lists routes Karan*/

  $routes->get('get-credit-note', '\Modules\Hotel\Controllers\Hotel::getCreditNote');



  /** Hotel  markup routes start */
  $routes->get('hotel-markup-list', '\Modules\Hotel\Controllers\Hotel::hotel_markup_list');
  $routes->post('hotel-markup-view', '\Modules\Hotel\Controllers\Hotel::hotel_markup_view');
  $routes->post('add-hotel-markup', '\Modules\Hotel\Controllers\Hotel::add_hotel_markup');
  $routes->post('hotel-markup-status-change', '\Modules\Hotel\Controllers\Hotel::hotel_markup_status_change');
  $routes->post('remove-hotel-markup', '\Modules\Hotel\Controllers\Hotel::remove_hotel_markup');
  $routes->post('edit-admin-hotel-markup-template/(:any)', '\Modules\Hotel\Controllers\Hotel::edit_admin_hotel_markup_template');
  $routes->post('edit-admin-hotel-markup/(:any)', '\Modules\Hotel\Controllers\Hotel::edit_admin_hotel_markup');
  /** end Hotel  markup routes*/


  /** Hotel  discount routes start */
  $routes->get('hotel-discount-list', '\Modules\Hotel\Controllers\Hotel::hotel_discount_list');
  $routes->post('hotel-discount-view', '\Modules\Hotel\Controllers\Hotel::hotel_discount_view');
  $routes->post('add-hotel-discount', '\Modules\Hotel\Controllers\Hotel::add_hotel_discount');
  $routes->post('hotel-discount-status-change', '\Modules\Hotel\Controllers\Hotel::hotel_discount_status_change');
  $routes->post('remove-hotel-discount', '\Modules\Hotel\Controllers\Hotel::remove_hotel_discount');
  $routes->post('edit-admin-hotel-discount-template/(:any)', '\Modules\Hotel\Controllers\Hotel::edit_admin_hotel_discount_template');
  $routes->post('edit-admin-hotel-discount/(:any)', '\Modules\Hotel\Controllers\Hotel::edit_admin_hotel_discount');
  /** end Hotel  discount routes */

  $routes->get('assign-update-hotel-ticket/(:any)', '\Modules\Hotel\Controllers\Hotel::AssignUpdateHotelTicket');
  $routes->get('get-update-hotel-voucher-info/(:any)', '\Modules\Hotel\Controllers\Hotel::getUpdatehotelVoucherInfo');

  $routes->post('hotel-update-voucher-info', '\Modules\Hotel\Controllers\Hotel::UpdateHotelVoucherInfo');





  $routes->post('amendment-status-change', '\Modules\Hotel\Controllers\Hotel::amendmentStatusChange');
  $routes->post('hotel-amendment-cancellation-charge', '\Modules\Hotel\Controllers\Hotel::hotelAmendmentCancellationCharge');





});
