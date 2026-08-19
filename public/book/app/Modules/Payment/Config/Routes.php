<?php

/**
 * Define Pages Routes
 */
$routes->group('payment', function ($routes) {
    $routes->get('opt/(:any)', '\Modules\Payment\Controllers\Payment::index');
    $routes->get('fpt/(:any)', '\Modules\Payment\Controllers\Payment::flightPayment');
    $routes->get('proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::getPaymentsProceedAmounts');
    $routes->get('proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::proceed_payment');
    $routes->get('flight-proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::flight_proceed_payment');
    $routes->get('make-payment/(:any)', '\Modules\Payment\Controllers\Payment::makePayment');
    $routes->get('payment-error', '\Modules\Payment\Controllers\Payment::payment_error');
    $routes->match(['POST', 'GET'], 'response', '\Modules\Payment\Controllers\Payment::response');
    $routes->post('makepaymentesponse', '\Modules\Payment\Controllers\Payment::makepaymentesponse');
    $routes->post('notifyurl', '\Modules\Payment\Controllers\Payment::notifyurl');
    $routes->post('callback-url', '\Modules\Payment\Controllers\Payment::callbackUrl');
});
