<?php
/**
 * Define Query Routes
 */

$routes->group("query", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'],'/', '\Modules\Query\Controllers\Query::index'); 
    $routes->post('remove-query', '\Modules\Query\Controllers\Query::remove_query'); 

    $routes->post('export-query', '\Modules\Query\Controllers\Query::export_query');
    $routes->get('wedding-query-list', '\Modules\Query\Controllers\Query::wedding_query_list');
    $routes->match(['POST'], 'weddingquery-list-details/(:any)', '\Modules\Query\Controllers\Query::weddingquery_list_details');
    $routes->post('remove-wedding-query-list', '\Modules\Query\Controllers\Query::remove_wedding_query_list');
}); 