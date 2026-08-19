<?php

/**
 * Define  Routes For Payment Setting Create by abhay
 */

$routes->group("payment-setting", ["filter" => "auth", "namespace" => "\Modules\PaymentSetting\Controllers"], function ($routes) {
    $routes->get('/', 'Paymentsetting::index');
    $routes->post('edit-payment-setting-template/(:any)', 'Paymentsetting::edit_payment_setting_template');
    $routes->post('edit-payment-setting/(:any)', 'Paymentsetting::edit_payment_setting');
    $routes->post('status-change-payment-setting', 'Paymentsetting::api_supplier_status_change');
   
});
