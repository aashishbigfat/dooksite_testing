<?php
/**
 * Define Flight Routes
 */
    $routes->group("flight", function ($routes) {
    $routes->get('/', '\Modules\Flight\Controllers\Flight::index'); 
    $routes->get('get-airports', '\Modules\Flight\Controllers\Flight::get_airports');
    $routes->get('get-airline', '\Modules\Flight\Controllers\Flight::get_airline');
    /** flight discount-b2c routes*/
    $routes->get('flight-discount-b2c', '\Modules\Flight\Controllers\Flight::flight_discount_b2c');
    $routes->post('flight-discount-b2c-view', '\Modules\Flight\Controllers\Flight::flight_discount_b2c_view');
    $routes->post('add-discount-b2c', '\Modules\Flight\Controllers\Flight::add_discount_b2c');
    $routes->post('discount-b2c-status-change', '\Modules\Flight\Controllers\Flight::discount_b2c_status_change');
    $routes->post('remove-discount-b2c', '\Modules\Flight\Controllers\Flight::remove_discount_b2c');
    $routes->post('edit-discount-b2c-template/(:any)', '\Modules\Flight\Controllers\Flight::edit_discount_b2c_view');
    $routes->post('edit-discount-b2c/(:any)', '\Modules\Flight\Controllers\Flight::edit_discount_b2c');
    $routes->post('flight-discount-b2c-details/(:any)', '\Modules\Flight\Controllers\Flight::flight_discount_b2c_details');
    /** flight discount-b2c routes*/

    /** flight markup-b2c routes*/
    $routes->get('flight-markup-b2c', '\Modules\Flight\Controllers\Flight::flight_markup_b2c');
    $routes->post('flight-markup-b2c-view', '\Modules\Flight\Controllers\Flight::flight_markup_b2c_view');
    $routes->post('add-markup-b2c', '\Modules\Flight\Controllers\Flight::add_markup_b2c');
    $routes->post('markup-b2c-status-change', '\Modules\Flight\Controllers\Flight::markup_b2c_status_change');
    $routes->post('remove-markup-b2c', '\Modules\Flight\Controllers\Flight::remove_markup_b2c');
    $routes->post('edit-markup-b2c-template/(:any)', '\Modules\Flight\Controllers\Flight::edit_markup_b2c_view');
    $routes->post('edit-markup-b2c/(:any)', '\Modules\Flight\Controllers\Flight::edit_markup_b2c');
    $routes->post('flight-markup-b2c-details/(:any)', '\Modules\Flight\Controllers\Flight::flight_markup_b2c_details');
    /** flight markup-b2c routes*/

    /** flight Booking  routes Harish*/
    $routes->post('flight-check-search-validtion', '\Modules\Flight\Controllers\Flight::check_search_validation');
    $routes->get('search', '\Modules\Flight\Controllers\Flight::search');
    $routes->get('flight-result', '\Modules\Flight\Controllers\Flight::flightResult');
    $routes->post('fare-rule', '\Modules\Flight\Controllers\Flight::fareRule');
    $routes->post('fare-confirmation', '\Modules\Flight\Controllers\Flight::fareConfirmation');
    $routes->post('fare-confirmation-roundtrip', '\Modules\Flight\Controllers\Flight::fareConfirmationRoundtrip');
    $routes->get('flight-details', '\Modules\Flight\Controllers\Flight::flight_details');
    $routes->post('validate-travellers', '\Modules\Flight\Controllers\Flight::validate_travellers');
    $routes->get('payment-status/(:any)', '\Modules\Flight\Controllers\Flight::paymentStatus');
    $routes->post('initiate-booking', '\Modules\Flight\Controllers\Flight::flightBooking');
    $routes->get('error', '\Modules\Flight\Controllers\Flight::error');
    $routes->get('confirmation/(:any)', '\Modules\Flight\Controllers\Flight::confirmation');
    $routes->post('update-webpartner-markup-discount', '\Modules\Flight\Controllers\Flight::updateWebPartnerMarkupDiscount');
    $routes->get('get-invoice-ticket', '\Modules\Flight\Controllers\Flight::getInvoiceTicket');
    $routes->post('get-invoice-ticket', '\Modules\Flight\Controllers\Flight::getInvoiceTicket');
    $routes->get('get-meal-baggage-data', '\Modules\Flight\Controllers\Flight::GetMealBaggageData');
    /** flight Booking routes Harish*/

    /** flight Booking Lists routes Harish*/
    $routes->get('bookings', '\Modules\Flight\Controllers\Flight::bookingLists');
    $routes->get('bookings-search', '\Modules\Flight\Controllers\Flight::bookingLists');
    $routes->get('details/(:any)', '\Modules\Flight\Controllers\Flight::bookingDetails');
    $routes->post('raise-amendment', '\Modules\Flight\Controllers\Flight::raiseAmendment');
    $routes->get('amendment-itinerary', '\Modules\Flight\Controllers\Flight::amendmentItinerary');
    $routes->post('raise-amendment-type', '\Modules\Flight\Controllers\Flight::raiseAmendmentType');
    $routes->get('amendment-details/(:any)', '\Modules\Flight\Controllers\Flight::amendmentDetails');
    /** flight Booking Lists routes Harish*/

    $routes->get('get-credit-note', '\Modules\Flight\Controllers\Flight::getCreditNote');


    /** flight Amendments Lists routes Praveen*/
    $routes->get('amendments-details/(:any)', '\Modules\Flight\Controllers\Flight::amendmentsDetails');
    $routes->get('flight-amendments', '\Modules\Flight\Controllers\Flight::flightAmendmentLists');
    /** flight Amendments Lists routes Praveen*/

    $routes->post('get-coupan-list', '\Modules\Flight\Controllers\Flight::get_coupan_list');

    $routes->post('promocode', '\Modules\Flight\Controllers\Flight::promocode');

    $routes->post('remove-promocode', '\Modules\Flight\Controllers\Flight::removePromoCode');
    $routes->get('group-ticket', '\Modules\Flight\Controllers\Flight::groupTicket');
    $routes->get('new-flight-filter', '\Modules\Flight\Controllers\Flight::newflightfilter');
    $routes->get('new-flight-result', '\Modules\Flight\Controllers\Flight::newflightresult');
});