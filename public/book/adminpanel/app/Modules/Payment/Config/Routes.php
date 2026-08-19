<?php
/**
 * Define Pages Routes
 */
$routes->group("payment", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'],'opt/(:any)', '\Modules\Payment\Controllers\Payment::index');
    $routes->match(['GET'],'fpt/(:any)', '\Modules\Payment\Controllers\Payment::flightPayment');
    $routes->match(['POST'],'response', '\Modules\Payment\Controllers\Payment::response');
    $routes->match(['POST'],'makepaymentesponse', '\Modules\Payment\Controllers\Payment::makepaymentesponse');
    $routes->match(['GET'],'proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::proceed_payment');
    $routes->match(['GET'],'flight-proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::flight_proceed_payment');
    $routes->match(['GET'],'make-payment/(:any)', '\Modules\Payment\Controllers\Payment::makePayment');
    $routes->match(['GET'],'payment-error', '\Modules\Payment\Controllers\Payment::payment_error');

});
