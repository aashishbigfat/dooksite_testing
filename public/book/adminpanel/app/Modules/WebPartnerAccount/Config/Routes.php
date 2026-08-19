<?php
/**
 * Define WebPartnerAccount Routes
 */
/* $routes->group("webpartneraccounts", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::index');
    $routes->match(['post'], 'web-account-info/(:any)', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::webAccountinfo');
    $routes->match(['get'], 'get-web-partner', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::getWebpartner');
    $routes->match(['get'], 'get-web-partner-account-info', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::web_partner_account_logs');

    $routes->match(['get'], 'get-web-partner-debit-info', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::web_partner_debit_logs');

    $routes->match(['get'], 'credit-notes', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes');


    $routes->match(['get'], 'credit-notes-hotel', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_hotel');

    $routes->match(['get'], 'credit-notes-holiday', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_holiday');

    $routes->match(['get'], 'credit-notes-visa', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_visa');
    $routes->match(['get'], 'credit-notes-car', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_car');
    $routes->match(['get'], 'credit-notes-bus', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_bus');

    $routes->match(['get'], 'credit-notes-cruise', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_cruise');
});
 */



$routes->group("webpartneraccounts", ["filter" => "auth"], function ($routes) {

    $routes->match(['GET'], '/', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::index');
    $routes->match(['POST'], 'web-account-info/(:any)', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::webAccountinfo');
    $routes->match(['GET'], 'get-web-partner', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::getWebpartner');
    $routes->match(['GET'], 'get-web-partner-account-info', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::web_partner_account_logs');

    $routes->match(['GET'], 'get-web-partner-debit-info', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::web_partner_debit_logs');

    $routes->match(['GET'], 'credit-notes', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes');

    $routes->match(['GET'], 'credit-notes-hotel', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_hotel');

    $routes->match(['GET'], 'credit-notes-holiday', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_holiday');

    $routes->match(['GET'], 'credit-notes-visa', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_visa');
    $routes->match(['GET'], 'credit-notes-car', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_car');
    $routes->match(['GET'], 'credit-notes-bus', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_bus');

    $routes->match(['GET'], 'credit-notes-cruise', '\Modules\WebPartnerAccount\Controllers\WebPartnerAccount::credit_notes_cruise');
});