<?php
/**
 * Define Home Routes
 */
$routes->group("", function ($routes) {
    $routes->get('/', '\Modules\Home\Controllers\Home::index'); 
    $routes->get('trending-Indian-holidays-destinations', '\Modules\Home\Controllers\Home::Trending_Indian_Holidays_Destinations');
    $routes->get('trending-International-holidays-destinations', '\Modules\Home\Controllers\Home::Trending_International_Holidays_Destinations');

    $routes->post('change-website-currency', '\Modules\Home\Controllers\Home::changewebsiteCurrency');
});
