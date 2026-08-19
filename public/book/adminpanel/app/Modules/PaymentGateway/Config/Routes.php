<?php

$routes->group("payment-gateway", ["filter" => "auth"], function ($routes) {

    $routes->get('/', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::index');
    $routes->post('add-gateway-template', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::addGatewayView'); 
    $routes->post('add-gateway', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::addGateway'); 
    $routes->post('edit-gateway/(:any)', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::editGateway');
    $routes->post('update-gateway/(:any)', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::updateGateway');
    $routes->post('remove-gateway', '\Modules\PaymentGateway\Controllers\PaymentGatewayController::removeGateway'); 

});