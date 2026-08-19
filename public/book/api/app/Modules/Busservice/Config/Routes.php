<?php
/**
 * Define Busservice Routes
 */

$routes->group("busservice/rest", ["filter" => "auth"], function ($routes) {
    $routes->post('search', '\Modules\Busservice\Controllers\Rest::Search');
    $routes->post('seatlayout', '\Modules\Busservice\Controllers\Rest::SeatLayOut');
    $routes->post('boardingpoint', '\Modules\Busservice\Controllers\Rest::BoardingPoint');
    $routes->post('blockseat', '\Modules\Busservice\Controllers\Rest::BlockSeat');
    $routes->post('book', '\Modules\Busservice\Controllers\Rest::Book');
    $routes->post('getbookingdetail', '\Modules\Busservice\Controllers\Rest::GetBookingDetail');
    $routes->post('cancelrequest', '\Modules\Busservice\Controllers\Rest::CancelRequest');
    $routes->post('updatebookingdetail', '\Modules\Busservice\Controllers\Rest::UpdateBookingDetail');
    $routes->get('get-logs/(:any)', '\Modules\Busservice\Controllers\Rest::GetLogs');
    $routes->post('submitamendment', '\Modules\Busservice\Controllers\Rest::SubmitAmendment');
});
$routes->post('busservice/rest/generate-ticket-invoice', '\Modules\Busservice\Controllers\Rest::generateTicketInvoice');
$routes->get('busservice/rest/update-city-list', '\Modules\Busservice\Controllers\Rest::GetBusCityList');
$routes->post('busservice/rest/generate-credit-note', '\Modules\Busservice\Controllers\Rest::generateCreditNote');