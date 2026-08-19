<?php


/**
 *Logs Routes
 */
$routes->group("logs", ["filter" => "auth"], function ($routes) {
    /*sms logs routes*/
    $routes->match(['GET'], '/', '\Modules\Logs\Controllers\Logs::sms_logs');
    $routes->post('remove-sms-logs', '\Modules\Logs\Controllers\Logs::remove_sms_logs');
    $routes->post('sms-log-details/(:any)', '\Modules\Logs\Controllers\Logs::sms_log_details');
    $routes->post('resend-sms/(:any)', '\Modules\Logs\Controllers\Logs::resend_sms');
    /*end sms logs routes*/

    /*login logs routes*/
    $routes->match(['GET'], 'login-logs', '\Modules\Logs\Controllers\Logs::login_logs');
    $routes->post('remove-login-logs', '\Modules\Logs\Controllers\Logs::remove_login_logs');
    /*end login logs routes*/

    /*email logs routes*/
    $routes->match(['GET'], 'email-logs', '\Modules\Logs\Controllers\Logs::email_logs');
    $routes->post('remove-email-logs', '\Modules\Logs\Controllers\Logs::remove_email_logs');
    $routes->post('email-log-details/(:any)', '\Modules\Logs\Controllers\Logs::email_log_details');
    $routes->post('resend-email/(:any)', '\Modules\Logs\Controllers\Logs::resend_email');
    /*end email logs routes*/


    /*coupon logs routes*/
    $routes->match(['GET'], 'coupon-log', '\Modules\Logs\Controllers\Logs::coupon_log');

    /*coupon logs routes*/


});
