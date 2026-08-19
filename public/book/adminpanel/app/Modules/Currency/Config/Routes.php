<?php
/**
 * Define currency Routes
 */

$routes->group("currency", ["filter" => "auth"], function ($routes) {
    $routes->get('/', '\Modules\Currency\Controllers\Currency::index');
    $routes->post('add-currency-view', '\Modules\Currency\Controllers\Currency::add_currency_view');
    $routes->post('add-currency', '\Modules\Currency\Controllers\Currency::add_currency');
    $routes->post('remove-currency', '\Modules\Currency\Controllers\Currency::remove_currency');

    $routes->post('edit-currency-view/(:any)', '\Modules\Currency\Controllers\Currency::edit_currency_view');
    $routes->post('edit-currency/(:any)', '\Modules\Currency\Controllers\Currency::edit_currency');
    $routes->post('currency-status-change', '\Modules\Currency\Controllers\Currency::currency_status_change');
    $routes->post('default-update-status/(:any)', '\Modules\Currency\Controllers\Currency::default_status_update');


});