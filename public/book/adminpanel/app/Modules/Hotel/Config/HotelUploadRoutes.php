<?php 
/**
 * Define Hotel Upload Routes
 */
$routes->group("hotel-upload", ["filter" => "auth"], function ($routes) {
    $routes->get('', '\Modules\Hotel\Controllers\HotelUpload::index');
    $routes->post('hotel-info-save', '\Modules\Hotel\Controllers\HotelUpload::hotel_info_save');
    $routes->get('room-information', '\Modules\Hotel\Controllers\HotelUpload::room_information');
    $routes->post('add-room', '\Modules\Hotel\Controllers\HotelUpload::addroom');
    $routes->post('add-passanger', '\Modules\Hotel\Controllers\HotelUpload::addpassanger');
    $routes->post('room-info-save/(:any)', '\Modules\Hotel\Controllers\HotelUpload::room_info_save');
    $routes->get('review-detail', '\Modules\Hotel\Controllers\HotelUpload::review_detail');
    $routes->get('generate-hotel-voucher', '\Modules\Hotel\Controllers\HotelUpload::generate_hotel_voucher');
});