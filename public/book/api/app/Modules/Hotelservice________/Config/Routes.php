<?php
/**
 * Define Hotelservice Routes
 */

$routes->group("hotelservice/rest", ["filter" => "auth"], function ($routes) {

    $routes->post('search', '\Modules\Hotelservice\Controllers\Rest::Search');
    $routes->post('gethotelinfo', '\Modules\Hotelservice\Controllers\Rest::GetHotelInfo');
    $routes->post('getroominfo', '\Modules\Hotelservice\Controllers\Rest::GetHotelRoom');
    $routes->post('blockroom', '\Modules\Hotelservice\Controllers\Rest::BlockRoom');
    $routes->post('book', '\Modules\Hotelservice\Controllers\Rest::Book');
    $routes->post('generatevoucher', '\Modules\Hotelservice\Controllers\Rest::GenerateVoucher');
    $routes->post('getbookingdetail', '\Modules\Hotelservice\Controllers\Rest::GetBookingDetail');
    $routes->post('cancelrequest', '\Modules\Hotelservice\Controllers\Rest::CancelRequest');
    $routes->post('refundrequest', '\Modules\Hotelservice\Controllers\Rest::RefundRequest');
    $routes->post('updatebookingdetail', '\Modules\Hotelservice\Controllers\Rest::UpdateBookingDetail');
    $routes->post('submitamendment', '\Modules\Hotelservice\Controllers\Rest::SubmitAmendment');

});
$routes->post('hotelservice/rest/generate-voucher-invoice', '\Modules\Hotelservice\Controllers\Rest::generateVoucherInvoice');
$routes->post('hotelservice/rest/generate-credit-note', '\Modules\Hotelservice\Controllers\Rest::generateCreditNote');
$routes->get('hotelservice/rest/get-logs/(:any)', '\Modules\Hotelservice\Controllers\Rest::GetLogs');
