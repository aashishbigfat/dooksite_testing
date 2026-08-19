<?php
/**
 * Define Career Routes
 */
$routes->group("career", ["filter" => "auth"] , function($routes) {
    $routes->match(['GET'],'/', '\Modules\Career\Controllers\Career::index');
    $routes->post('add-career-template', '\Modules\Career\Controllers\Career::add_career_view');
    $routes->post('add-career', '\Modules\Career\Controllers\Career::add_career');
    $routes->post('remove-career', '\Modules\Career\Controllers\Career::remove_career');
    $routes->post('edit-career-template/(:any)', '\Modules\Career\Controllers\Career::edit_career_view');
    $routes->post('edit-career/(:any)', '\Modules\Career\Controllers\Career::edit_career');
    $routes->post('career-details/(:any)', '\Modules\Career\Controllers\Career::career_details');

    $routes->match(['GET'],'job-applications-list', '\Modules\Career\Controllers\Career::job_application_list');

    $routes->post('remove-job-application', '\Modules\Career\Controllers\Career::remove_job_application');

    $routes->post('career-status-change', '\Modules\Career\Controllers\Career::career_status_change');
    
    $routes->match(['GET'],'career-categories-list', '\Modules\Career\Controllers\Career::career_categories_list');
    $routes->post('add-career-categories-template', '\Modules\Career\Controllers\Career::add_career_categories_view');
    $routes->post('add-career-categories', '\Modules\Career\Controllers\Career::add_career_categories');
    $routes->post('career-categories-status-change', '\Modules\Career\Controllers\Career::career_categories_status_change');
    $routes->post('remove-career-categories', '\Modules\Career\Controllers\Career::remove_career_categories');
    $routes->post('edit-career-categories-template/(:any)', '\Modules\Career\Controllers\Career::edit_career_categories_view');
    $routes->post('edit-career-categories/(:any)', '\Modules\Career\Controllers\Career::edit_categories');
    
});
