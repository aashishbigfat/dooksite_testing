<?php
/**
 * Define Account Routes
 */
$routes->group("accounts", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], 'payment-processing', '\Modules\Accounts\Controllers\Accounts::MakePaymentView');
    $routes->match(['POST'], 'make-payment-processing', '\Modules\Accounts\Controllers\Accounts::MakePayment');
    $routes->match(['GET'], 'payment-history', '\Modules\Accounts\Controllers\Accounts::payment_history');
    $routes->post('payment-history-detail/(:any)', '\Modules\Accounts\Controllers\Accounts::payment_history_detail');

    $routes->match(['GET'], 'wl-payment-history', '\Modules\Accounts\Controllers\Accounts::wl_payment_history');

    $routes->post('wl-payment-history-detail/(:any)', '\Modules\Accounts\Controllers\Accounts::wl_payment_history_detail');
    $routes->post('wl-payment-status-change', '\Modules\Accounts\Controllers\Accounts::wl_payment_status_change');
   
});
