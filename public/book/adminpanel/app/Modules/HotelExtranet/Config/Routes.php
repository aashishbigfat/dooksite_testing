<?php
/**
 * Define Hotel Extranet Routes
 */
$routes->group("hotel-extranet", ["filter" => "auth"], function ($routes) {
    /** HotelExtranet amenity routes start*/
    $routes->get('hotel-extranet-settings', '\Modules\HotelExtranet\Controllers\HotelExtranet::property_type_list');
    $routes->match(['GET'], 'amenity-list', '\Modules\HotelExtranet\Controllers\HotelExtranet::amenity_list');
    $routes->post('add-amenity-template', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_amenity_view');
    $routes->post('add-amenity', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_amenity');
    $routes->post('remove-amenity', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_amenity');
    $routes->post('amenity-status-change', '\Modules\HotelExtranet\Controllers\HotelExtranet::amenity_status_change');
    $routes->post('edit-amenity-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_amenity_view');
    $routes->post('edit-amenity/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_amenity');
    /** HotelExtranet amenity routes end*/

    /** HotelExtranet property type routes start*/
    $routes->match(['GET'], 'property-type-list', '\Modules\HotelExtranet\Controllers\HotelExtranet::property_type_list');
    $routes->post('add-property-type-template', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_property_type_view');
    $routes->post('add-property-type', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_property_type');
    $routes->post('remove-property-type', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_property_type');
    $routes->post('property-type-status-change', '\Modules\HotelExtranet\Controllers\HotelExtranet::property_type_status_change');
    $routes->post('edit-property-type-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_property_type_view');
    $routes->post('edit-property-type/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_property_type');
    /** HotelExtranet property type routes end*/

    /** HotelExtranet hotel routes start*/
    $routes->match(['GET'], 'hotel-list', '\Modules\HotelExtranet\Controllers\HotelExtranet::hotel_list');
    $routes->match(['GET'], 'get-city', '\Modules\HotelExtranet\Controllers\HotelExtranet::get_city');
    $routes->get('add-hotel', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_hotel');
    $routes->post('add-hotel-save', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_hotel_save');
    $routes->post('remove-hotel', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_hotel');
    $routes->post('hotel-status-change', '\Modules\HotelExtranet\Controllers\HotelExtranet::hotel_status_change');
    $routes->get('edit-hotel/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_hotel_view');
    $routes->post('edit-hotel-save/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_hotel');
    /** HotelExtranet hotel routes end*/


    /** HotelExtranet addon routes start*/
    $routes->match(['GET'], 'addon-list/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::addon_list');
    $routes->post('add-addon-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_addon_view');
    $routes->post('add-addon/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_addon');
    $routes->post('remove-addon', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_addon');
    $routes->post('addon-status-change', '\Modules\HotelExtranet\Controllers\HotelExtranet::addon_status_change');
    $routes->post('edit-addon-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_addon_view');
    $routes->post('edit-addon/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_addon');
    /** HotelExtranet addon routes end*/

    /** HotelExtranet room routes start*/
    $routes->match(['GET'], 'room-list/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::room_list');
    $routes->post('add-room-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_room_view');
    $routes->post('add-room/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_room');
    $routes->post('remove-room', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_room');
    $routes->post('room-status-change', '\Modules\HotelExtranet\Controllers\HotelExtranet::room_status_change');
    $routes->post('edit-room-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_room_view');
    $routes->post('edit-room/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_room');
    /** HotelExtranet room routes end*/

    /**HotelExtranet room gallery routes*/
    $routes->post('room-gallery/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::room_gallery');
    $routes->post('add-room-gallery/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_room_gallery');
    $routes->post('remove-room-gallery', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_room_gallery');
    /**end HotelExtranet room gallery routes*/



    /** HotelExtranet room price routes start*/
    $routes->match(['GET'], 'room-price-list/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::room_price_list');
    $routes->post('add-room-price-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_room_price_view');
    $routes->post('add-room-price/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::add_room_price');
    $routes->post('remove-room-price', '\Modules\HotelExtranet\Controllers\HotelExtranet::remove_room_price');
    $routes->post('edit-room-price-template/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_room_price_view');
    $routes->post('edit-room-price/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::edit_room_price');
    /** HotelExtranet room price routes end*/

    /** HotelExtranet  Availability  routes start*/
    $routes->get('get-room-availability', '\Modules\HotelExtranet\Controllers\HotelExtranet::get_room_availability');
    $routes->post('room-availability-update/(:any)', '\Modules\HotelExtranet\Controllers\HotelExtranet::room_availability_update');
    /** HotelExtranet Availability end*/

});

