<?php
/**
 * Define Login Routes
 */
$routes->group("login", function($routes) {
    $routes->match(['GET', 'POST'],'check-user', '\Modules\Login\Controllers\Login::check_user');
    $routes->match(['POST'],'user-login', '\Modules\Login\Controllers\Login::user_login');
    $routes->match(['POST'],'user-signup', '\Modules\Login\Controllers\Login::user_signup');
    $routes->match(['GET', 'POST'],'login-modal', '\Modules\Login\Controllers\Login::login_modal');
    $routes->match(['POST'],'password-reset', '\Modules\Login\Controllers\Login::password_reset');
    $routes->match(['GET', 'POST'],'google-oauth', '\Modules\Login\Controllers\Login::google_oauth');
    $routes->match(['GET', 'POST'],'facebook-oauth', '\Modules\Login\Controllers\Login::facebook_oauth');
});
$routes->get('signout', '\Modules\Login\Controllers\Login::signout');
/* $routes->get('access-account/(:any)', '\Modules\Login\Controllers\Login::access_account'); */
$routes->get('access-account/(:any)', '\Modules\Login\Controllers\Login::access_account');
//google login 
$routes->post('authinit', '\Modules\Login\Controllers\Login::auth_init');