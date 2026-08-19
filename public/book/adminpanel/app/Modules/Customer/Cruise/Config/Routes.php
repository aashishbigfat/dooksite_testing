<?php

/**
 * Define Cruise Routes
 */ 

$routes->group("cruise", ["filter" => "auth"], function ($routes) {

  $routes->get('/', '\Modules\Cruise\Controllers\Cruise::cruise_booking_list');

  /** cruise list routes*/
  $routes->match(['get'], 'cruise-list', '\Modules\Cruise\Controllers\Cruise::index');
  $routes->post('add-cruise-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_view');
  $routes->post('add-cruise', '\Modules\Cruise\Controllers\Cruise::add_cruise');
  $routes->post('edit-cruise-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_view');
  $routes->post('edit-cruise/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise');
  $routes->post('cruise-details/(:any)', '\Modules\Cruise\Controllers\Cruise::feedback_details');
  $routes->post('cruise-list-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_list_status_change');
  $routes->post('remove-cruise-list', '\Modules\Cruise\Controllers\Cruise::remove_cruise_list');
  /** cruise list routes end*/

  /** cruise line routes*/
  $routes->get('cruise-settings', '\Modules\Cruise\Controllers\Cruise::cruise_line_list');
  $routes->get('cruise-line-list', '\Modules\Cruise\Controllers\Cruise::cruise_line_list');
  $routes->post('add-cruise-line-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_line_template');
  $routes->post('add-cruise-line', '\Modules\Cruise\Controllers\Cruise::add_cruise_line');
  $routes->post('edit-cruise-line-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_line_view');
  $routes->post('edit-cruise-line/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_line');
  $routes->post('cruise-line-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_line_status_change');
  $routes->post('remove-cruise-line', '\Modules\Cruise\Controllers\Cruise::remove_cruise_line');
  /** cruise line routes end*/

  /** cruise ship routes*/
  $routes->get('cruise-ship-list', '\Modules\Cruise\Controllers\Cruise::cruise_ship_list');
  $routes->post('add-cruise-ship-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_ship_template');
  $routes->post('add-cruise-ship', '\Modules\Cruise\Controllers\Cruise::add_cruise_ship');
  $routes->post('edit-cruise-ship-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ship_view');
  $routes->post('edit-cruise-ship/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ship');
  $routes->post('cruise-ship-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_ship_status_change');
  $routes->post('remove-cruise-ship', '\Modules\Cruise\Controllers\Cruise::remove_cruise_ship');
  /** cruise ship routes end*/


  /** cruise ship gallery routes*/
  $routes->get('cruise-ship-gallery-list', '\Modules\Cruise\Controllers\Cruise::cruise_ship_gallery_list');
  $routes->post('add-cruise-ship-gallery-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_ship_gallery_template');
  $routes->post('add-cruise-ship-gallery', '\Modules\Cruise\Controllers\Cruise::add_cruise_ship_gallery');
  $routes->post('edit-cruise-ship-gallery-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ship_gallery_view');
  $routes->post('edit-cruise-ship-gallery/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ship_gallery');
  $routes->post('cruise-ship-gallery-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_ship_gallery_status_change');
  $routes->post('remove-cruise-ship-gallery', '\Modules\Cruise\Controllers\Cruise::remove_cruise_ship_gallery');

  $routes->post('get-cruise-ship-id-select', '\Modules\Cruise\Controllers\Cruise::get_cruise_ship_id_select');
  $routes->post('get-cruise-cabin-id-select', '\Modules\Cruise\Controllers\Cruise::get_cruise_cabin_id_select');
  /** cruise ship gallery routes end*/

   /** cruise markup routes start */
   $routes->get('cruise-markup-list', '\Modules\Cruise\Controllers\Cruise::cruise_markup_list');
   $routes->post('cruise-markup-view', '\Modules\Cruise\Controllers\Cruise::cruise_markup_view');
   $routes->post('add-cruise-markup', '\Modules\Cruise\Controllers\Cruise::add_cruise_markup');
   $routes->post('cruise-markup-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_markup_status_change');
   $routes->post('remove-cruise-markup', '\Modules\Cruise\Controllers\Cruise::remove_cruise_markup');
   $routes->post('edit-cruise-markup-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_markup_template');
   $routes->post('edit-cruise-markup/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_markup');
   /** end cruise markup routes*/

     /** cruise discount routes start */
     $routes->get('cruise-discount-list', '\Modules\Cruise\Controllers\Cruise::cruise_discount_list');
     $routes->post('cruise-discount-view', '\Modules\Cruise\Controllers\Cruise::cruise_discount_view');
     $routes->post('add-cruise-discount', '\Modules\Cruise\Controllers\Cruise::add_cruise_discount');
     $routes->post('edit-cruise-discount-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_discount_template');
     $routes->post('edit-cruise-discount/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_discount');
      $routes->post('cruise-discount-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_discount_status_change');
      $routes->post('remove-cruise-discount', '\Modules\Cruise\Controllers\Cruise::remove_cruise_discount');
     /** end cruise discount routes*/




  /** cruise cabin routes*/
  $routes->get('cruise-cabin-list', '\Modules\Cruise\Controllers\Cruise::cruise_cabin_list');
  $routes->post('add-cruise-cabin-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_cabin_template');
  $routes->post('add-cruise-cabin', '\Modules\Cruise\Controllers\Cruise::add_cruise_cabin');
  $routes->post('edit-cruise-cabin-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_cabin_view');
  $routes->post('edit-cruise-cabin/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_cabin');
  $routes->post('cruise-cabin-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_cabin_status_change');
  $routes->post('remove-cruise-cabin', '\Modules\Cruise\Controllers\Cruise::remove_cruise_cabin');
  /** cruise cabin routes end*/

  /** cruise price routes*/
  $routes->get('cruise-price-list/(:any)', '\Modules\Cruise\Controllers\Cruise::cruise_price_list');
  $routes->post('add-cruise-price-template/(:any)', '\Modules\Cruise\Controllers\Cruise::add_cruise_price_view');
  $routes->post('add-cruise-price/(:any)', '\Modules\Cruise\Controllers\Cruise::add_cruise_price');
  $routes->post('cruise-price-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_price_status_change');
  $routes->post('remove-cruise-price', '\Modules\Cruise\Controllers\Cruise::remove_cruise_price');
  $routes->post('edit-cruise-price-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_price_view');
  $routes->post('edit-cruise-price/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_price');
  /** cruise price routes end*/


  /** cruise cruise ocean routes*/
  $routes->get('cruise-ocean-list', '\Modules\Cruise\Controllers\Cruise::cruise_ocean_list');
  $routes->post('add-cruise-ocean-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_ocean_template');
  $routes->post('add-cruise-ocean', '\Modules\Cruise\Controllers\Cruise::add_cruise_ocean');
  $routes->post('edit-cruise-ocean-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ocean_view');
  $routes->post('edit-cruise-ocean/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_ocean');
  $routes->post('cruise-ocean-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_ocean_status_change');
  $routes->post('remove-cruise-ocean', '\Modules\Cruise\Controllers\Cruise::remove_cruise_ocean');
  /** cruise cruise ocean end*/


  /** cruise cruise port routes*/
  $routes->get('cruise-port-list', '\Modules\Cruise\Controllers\Cruise::cruise_port_list');
  $routes->post('add-cruise-port-template', '\Modules\Cruise\Controllers\Cruise::add_cruise_port_template');
  $routes->post('add-cruise-port', '\Modules\Cruise\Controllers\Cruise::add_cruise_port');
  $routes->post('edit-cruise-port-template/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_port_view');
  $routes->post('edit-cruise-port/(:any)', '\Modules\Cruise\Controllers\Cruise::edit_cruise_port');
  $routes->post('cruise-port-status-change', '\Modules\Cruise\Controllers\Cruise::cruise_port_status_change');
  $routes->post('remove-cruise-port', '\Modules\Cruise\Controllers\Cruise::remove_cruise_port');
  $routes->post('get-cruise-list-select', '\Modules\Cruise\Controllers\Cruise::get_cruise_list_select');
  /** cruise cruise port end*/

  $routes->get('confirmation/(:any)', '\Modules\Cruise\Controllers\Cruise::confirmation');

  $routes->get('cruise-booking-details/(:any)', '\Modules\Cruise\Controllers\Cruise::cruise_booking_detail');

  $routes->get('assign-update-cruise-ticket/(:any)', '\Modules\Cruise\Controllers\Cruise::AssignUpdateCruiseTicket');
  $routes->get('get-update-cruise-voucher-info/(:any)', '\Modules\Cruise\Controllers\Cruise::getUpdatecruiseVoucherInfo');

  $routes->post('cruise-update-voucher-info', '\Modules\Cruise\Controllers\Cruise::UpdateCruiseVoucherInfo');

  /**  Amendments Lists routes csi*/
  $routes->get('amendments', '\Modules\Cruise\Controllers\Cruise::amendment_lists');
  $routes->get('amendments-details/(:any)', '\Modules\Cruise\Controllers\Cruise::amendments_details');
  $routes->post('amendment-cancellation-charge', '\Modules\Cruise\Controllers\Cruise::amendment_cancellation_charge');
  $routes->post('amendment-status-change', '\Modules\Cruise\Controllers\Cruise::amendment_status_change');
  $routes->post('raise-amendment', '\Modules\Cruise\Controllers\Cruise::raiseAmendment');
  $routes->get('refunds', '\Modules\Cruise\Controllers\Cruise::refund_lists');
  $routes->post('refund-close', '\Modules\Cruise\Controllers\Cruise::refund_close');

  $routes->get('get-credit-note', '\Modules\Cruise\Controllers\Cruise::getCreditNote');

  $routes->match(['get', 'post'], 'get-invoice-ticket', '\Modules\Cruise\Controllers\Cruise::get_invoice_ticket');

  $routes->post('export-cruise-amendments', '\Modules\Cruise\Controllers\Cruise::export_amendments');
  /**  Refund Lists routes praveen*/
});
