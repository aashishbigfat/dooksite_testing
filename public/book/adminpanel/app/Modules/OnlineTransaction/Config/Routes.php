<?php

/**

 * Define OnlineTransaction Routes

 */



$routes->group("online-transaction", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], '/', '\Modules\OnlineTransaction\Controllers\OnlineTransaction::index');



    $routes->post('transaction-details/(:any)', '\Modules\OnlineTransaction\Controllers\OnlineTransaction::transaction_details');



    $routes->post('transaction-status-change', '\Modules\OnlineTransaction\Controllers\OnlineTransaction::transaction_status_change');

    $routes->post('transaction-status-remark-change', '\Modules\OnlineTransaction\Controllers\OnlineTransaction::transaction_status_remark_change');

});