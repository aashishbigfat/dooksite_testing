<?php
/**
 * Define Flight Routes
 */
// require_once ("UploadTicketRoutes.php");
$routes->group("groupflight", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\GroupFlight\Controllers\GroupFlight::index');
    $routes->get('assign-update-flight-ticket/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::AssignUpdateflightTicket');

    /** flight Booking  routes Harish*/
    $routes->get('get-airports', '\Modules\GroupFlight\Controllers\GroupFlight::get_airports');
    $routes->get('get-airline', '\Modules\GroupFlight\Controllers\GroupFlight::get_airline');
    $routes->get('confirmation/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::confirmation');
    $routes->post('update-webpartner-markup-discount', '\Modules\GroupFlight\Controllers\GroupFlight::updateWebPartnerMarkupDiscount');
    $routes->get('get-invoice-ticket', '\Modules\GroupFlight\Controllers\GroupFlight::getInvoiceTicket');
    $routes->post('get-invoice-ticket', '\Modules\GroupFlight\Controllers\GroupFlight::getInvoiceTicket');

    $routes->get('get-airline-multiple', '\Modules\GroupFlight\Controllers\GroupFlight::get_airline_multiple');

    /** flight Booking routes Harish*/

    /** flight Booking Lists routes Harish*/
    $routes->get('bookings', '\Modules\GroupFlight\Controllers\GroupFlight::bookingLists');
    $routes->get('pending-bookings', '\Modules\GroupFlight\Controllers\GroupFlight::bookingLists');
    $routes->get('cancelled-bookings', '\Modules\GroupFlight\Controllers\GroupFlight::bookingLists');
    $routes->get('bookings-search', '\Modules\GroupFlight\Controllers\GroupFlight::bookingLists');
    $routes->get('details/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::bookingDetails');
    $routes->post('raise-amendment', '\Modules\GroupFlight\Controllers\GroupFlight::raiseAmendment');
    $routes->get('amendment-itinerary', '\Modules\GroupFlight\Controllers\GroupFlight::amendmentItinerary');
    $routes->post('raise-amendment-type', '\Modules\GroupFlight\Controllers\GroupFlight::raiseAmendmentType');
    $routes->get('amendment-details/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::amendmentDetails');
    $routes->get('amendment-detail/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::amendmentDetail');
    $routes->post('groupflight-amendment-cancellation-charge', '\Modules\GroupFlight\Controllers\GroupFlight::groupflightAmendmentCancellationCharge');
    $routes->post('flight-refund-close', '\Modules\GroupFlight\Controllers\GroupFlight::flightRefundClose');
    /** flight Booking Lists routes Harish*/

    /** MarkupDiscount discount routes*/
    $routes->get('flight-discount', '\Modules\GroupFlight\Controllers\GroupFlight::flight_discount');
    $routes->post('flight-discount-view', '\Modules\GroupFlight\Controllers\GroupFlight::flight_discount_view');
    $routes->post('add-discount', '\Modules\GroupFlight\Controllers\GroupFlight::add_discount');
    $routes->post('discount-status-change', '\Modules\GroupFlight\Controllers\GroupFlight::discount_status_change');
    $routes->post('remove-discount', '\Modules\GroupFlight\Controllers\GroupFlight::remove_discount');
    $routes->post('edit-discount-template/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::edit_discount_view');
    $routes->post('edit-discount/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::edit_discount');
    $routes->post('flight-discount-details/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::flight_discount_details');
    /** MarkupDiscount discount routes*/

    /** MarkupDiscount markup routes*/
    $routes->get('groupflight-markup', '\Modules\GroupFlight\Controllers\GroupFlight::flight_markup');
    $routes->post('flight-markup-view', '\Modules\GroupFlight\Controllers\GroupFlight::flight_markup_view');
    $routes->post('add-markup', '\Modules\GroupFlight\Controllers\GroupFlight::add_markup');
    $routes->post('markup-status-change', '\Modules\GroupFlight\Controllers\GroupFlight::markup_status_change');
    $routes->post('remove-markup', '\Modules\GroupFlight\Controllers\GroupFlight::remove_markup');
    $routes->post('edit-markup-template/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::edit_markup_view');
    $routes->post('edit-markup/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::edit_markup');
    $routes->post('flight-markup-details/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::flight_markup_details');
    /** MarkupDiscount markup routes*/
    $routes->get('get-credit-note', '\Modules\GroupFlight\Controllers\GroupFlight::getCreditNote');
    $routes->get('booking-calender', '\Modules\GroupFlight\Controllers\GroupFlight::groupflight_booking_calender');  
    /** flight Amendments Lists routes Praveen*/
    $routes->get('amendments-details/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::amendmentsDetails');
    $routes->get('groupflight-amendments', '\Modules\GroupFlight\Controllers\GroupFlight::GroupflightAmendmentLists');
    $routes->post('amendment-status-change', '\Modules\GroupFlight\Controllers\GroupFlight::amendmentStatusChange');
    $routes->get('groupflight-refunds', '\Modules\GroupFlight\Controllers\GroupFlight::groupflightRefundLists');
    /** flight Amendments Lists routes Praveen*/

    $routes->match(['GET'], 'get-update-flight-ticket-info/(:any)', '\Modules\GroupFlight\Controllers\GroupFlight::GetUpdateFlightTicketInfo');
    $routes->match(['POST'], 'flight-update-ticket-info', '\Modules\GroupFlight\Controllers\GroupFlight::UpdateFlightTicketInfo');

    $routes->get('get-seat-bookings/(:any)', '\Modules\Flight\Controllers\FlightListings::get_seat_bookings');

});
/**
 *Flight Ticket Update Routes End
 */

$routes->group("flight-ticket-upload", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], 'get-update-flight-ticket-info/(:any)', '\Modules\Flight\Controllers\FlightTicketUpload::GetUpdateFlightTicketInfo');

    $routes->match(['POST'], 'flight-update-ticket-info', '\Modules\Flight\Controllers\FlightTicketUpload::UpdateFlightTicketInfo');

});


/**
 *Flight Offline Routes
 */

$routes->group("flightoffline", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], '/', '\Modules\Flight\Controllers\FlightOffline::index');

    $routes->post('flight-offline-view', '\Modules\Flight\Controllers\FlightOffline::flight_offline_view');

    $routes->post('flight-offline', '\Modules\Flight\Controllers\FlightOffline::flight_offline');

    $routes->post('edit-flight-offline-view/(:any)', '\Modules\Flight\Controllers\FlightOffline::edit_flight_offline_view');

    $routes->post('edit-flight-offline/(:any)', '\Modules\Flight\Controllers\FlightOffline::edit_flight_offline');

    $routes->post('remove-flight-offline', '\Modules\Flight\Controllers\FlightOffline::remove_flight_offline');

    $routes->post('flight-offline-status-change', '\Modules\Flight\Controllers\FlightOffline::flight_offline_status_change');

});


/**
 *Flight Settings Routes
 */

$routes->group("flightsettings", ["filter" => "auth"], function ($routes) {
    /** flight airport routes*/
    $routes->match(['GET'], '/', '\Modules\Flight\Controllers\FlightSettings::index');
    $routes->post('add-airport-template', '\Modules\Flight\Controllers\FlightSettings::add_airport_template');
    $routes->post('add-airport', '\Modules\Flight\Controllers\FlightSettings::add_airport');
    $routes->post('edit-airport-template/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airport_view');
    $routes->post('edit-airports/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airports');
    $routes->post('remove-airport', '\Modules\Flight\Controllers\FlightSettings::remove_airport');

    /** flight airline routes*/
    $routes->match(['GET'], 'flight-airlines-list', '\Modules\Flight\Controllers\FlightSettings::airlines_list');
    $routes->post('add-airline-template', '\Modules\Flight\Controllers\FlightSettings::add_airline_template');
    $routes->post('add-airline', '\Modules\Flight\Controllers\FlightSettings::add_airline');
    $routes->post('remove-airline', '\Modules\Flight\Controllers\FlightSettings::remove_airline');
    $routes->post('edit-airline-template/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airline_view');
    $routes->post('edit-airline/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airline');
});


/*API Flight Fare Type */

$routes->group("flightfaretype", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\Flight\Controllers\FlightFareType::index');
    $routes->post('add-faretype-template', '\Modules\Flight\Controllers\FlightFareType::add_faretype_template');
    $routes->post('add-faretype', '\Modules\Flight\Controllers\FlightFareType::add_faretype');
    $routes->post('edit-faretype-template/(:any)', '\Modules\Flight\Controllers\FlightFareType::edit_faretype_view');
    $routes->post('edit-faretype/(:any)', '\Modules\Flight\Controllers\FlightFareType::edit_faretype');
    $routes->post('remove-faretype', '\Modules\Flight\Controllers\FlightFareType::remove_faretype');
});

/* API Flight Fare Type End */

/*WebCheckIn */

$routes->group("web-check-in", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\Flight\Controllers\WebCheckIn::index');
    $routes->post('add-web-check-in-template', '\Modules\Flight\Controllers\WebCheckIn::add_web_check_in_template');
    $routes->post('add-web-check-in', '\Modules\Flight\Controllers\WebCheckIn::add_web_check_in');
    $routes->post('edit-web-check-in-template/(:any)', '\Modules\Flight\Controllers\WebCheckIn::edit_web_check_in_view');
    $routes->post('edit-web-check-in/(:any)', '\Modules\Flight\Controllers\WebCheckIn::edit_web_check_in');
    $routes->post('remove-web-check-in', '\Modules\Flight\Controllers\WebCheckIn::remove_web_check_in');
});

/* WebCheckIn */