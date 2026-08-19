<?php
/**
 * Define Blog Routes
 */
 
 $routes->group("blog", function ($routes) {
    $routes->get('', '\Modules\Blog\Controllers\Blog::index');
    $routes->get('(:any)', '\Modules\Blog\Controllers\Blog::blogdetail');
});