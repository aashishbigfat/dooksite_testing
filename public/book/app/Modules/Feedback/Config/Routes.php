<?php

/**
 * Define Activity Routes
 */
$routes->group("feedback", function ($routes) {
    $routes->get('/', '\Modules\Feedback\Controllers\Feedback::index');
    $routes->get('read-more', '\Modules\Feedback\Controllers\Feedback::read_more');
    $routes->post('add-feedback-data','\Modules\Feedback\Controllers\Feedback::add_feedback_saved');
   
   
});
