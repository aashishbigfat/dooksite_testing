<?php
/**
 * Define WebPartnerAccount Routes
 */
$routes->group("sale-result", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], '/', '\Modules\SalesReport\Controllers\SalesReport::index');

    $routes->match(['POST'], 'get-report', '\Modules\SalesReport\Controllers\SalesReport::get_report');
   

});
