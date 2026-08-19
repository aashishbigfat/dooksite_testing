<?php
/**
 * Define Feedback Routes
 */

$routes->group("feedback", ["filter" => "auth"], function ($routes) {
    $routes->match(['GET'], '/', '\Modules\Feedback\Controllers\Feedback::index');
    $routes->post('add-feedback-template', '\Modules\Feedback\Controllers\Feedback::add_feedback_view');
    $routes->post('add-feedback', '\Modules\Feedback\Controllers\Feedback::add_feedback');
    $routes->post('remove-feedback', '\Modules\Feedback\Controllers\Feedback::remove_feedback');
    $routes->post('edit-feedback-template/(:any)', '\Modules\Feedback\Controllers\Feedback::edit_feedback_view');
    $routes->post('edit-feedback/(:any)', '\Modules\Feedback\Controllers\Feedback::edit_feedback');

    $routes->post('feedback-details/(:any)', '\Modules\Feedback\Controllers\Feedback::feedback_details');

    $routes->post('feedback-status-change', '\Modules\Feedback\Controllers\Feedback::feedback_status_change');
});
