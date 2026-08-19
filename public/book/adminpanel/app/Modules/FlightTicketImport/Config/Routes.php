<?php
/**
 * Define Flight Ticket Import Routes
 */

$routes->group("flight-ticket-import", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::index');
    $routes->match(['GET'], 'segment-passenger-detail', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::segmentPassengerDetail');
    $routes->match(['POST'], 'check-pnr', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::checkPNR'); 
    $routes->match(['POST'], 'save-passenger', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::savePassenger'); 
    $routes->match(['POST'], 'store-segement-info', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::storeSegementInfo'); 
    $routes->match(['GET'], 'review-detail', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::reviewDetail'); 
    $routes->match(['GET'], 'generate-ticket', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::generateTicket'); 
    $routes->match(['GET','POST'], 'import-pnr-details', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::ImportPNRDetails'); 
});



