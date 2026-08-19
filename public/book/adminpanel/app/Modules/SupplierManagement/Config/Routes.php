<?php
/**
 * Define SupplierManagement Routes
 */

$routes->group("supplier-management", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\SupplierManagement\Controllers\SupplierManagement::index'); 
    $routes->post('add-flight-api-mgt-template', '\Modules\SupplierManagement\Controllers\SupplierManagement::add_flight_api_mgt_template');
    $routes->post('add-flight-api-mgt', '\Modules\SupplierManagement\Controllers\SupplierManagement::add_flight_api_mgt');
    $routes->post('remove-flight-api-mgt', '\Modules\SupplierManagement\Controllers\SupplierManagement::remove_flight_api_mgt');
    $routes->post('edit-flight-api-mgt-template/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_flight_api_mgt_template');
    $routes->post('edit-flight-api-mgt/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_flight_api_mgt');
    


    $routes->post('flight-api-mgt-status-change', '\Modules\SupplierManagement\Controllers\SupplierManagement::flight_api_mgt_status_change');

    $routes->get('api-supplier', '\Modules\SupplierManagement\Controllers\SupplierManagement::api_supplier');
    $routes->post('api-supplier-status-change', '\Modules\SupplierManagement\Controllers\SupplierManagement::api_supplier_status_change');
    $routes->post('edit-api-supplier-template/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_api_supplier_template');
    $routes->post('edit-api-supplier/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_api_supplier');
});
