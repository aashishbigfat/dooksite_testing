<?php

/**
 * Define Pages Routes
 */


/* *************************** Abhay ***************************  */
$routes->get('delete-all-sessions-and-logs', '\Modules\Pages\Controllers\Pages::deleteAllSessionsAndLogs');
/* *************************** Abhay ***************************  */

$routes->get('web-check-in', '\Modules\Pages\Controllers\Pages::web_check_in');
$routes->get('web-frame', '\Modules\Pages\Controllers\Pages::webFrame');
$routes->get('contact-us', '\Modules\Pages\Controllers\Pages::contact_us');
$routes->post('newsletter', '\Modules\Pages\Controllers\Pages::newsletter');
$routes->get('offers-list', '\Modules\Pages\Controllers\Pages::Offers_list');
$routes->post('contact-us-save', '\Modules\Pages\Controllers\Pages::savedata');
$routes->post('form', '\Modules\Pages\Controllers\Pages::all_Services_Enquiry_Form');


$routes->get('captchagenerate', '\Modules\Pages\Controllers\Pages::generateCaptcha');


$routes->get('booking-review/(:any)', '\Modules\Pages\Controllers\Pages::BookingReview');
$routes->post('reviewSave', '\Modules\Pages\Controllers\Pages::reviewSaveData');
$routes->get('/(:any)', '\Modules\Pages\Controllers\Pages::pages/$1');
