<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */ 


 $routes->setDefaultNamespace('Modules\Home\Controllers');
 $routes->setDefaultController('Home');
 $routes->setDefaultMethod('index');
 $routes->setTranslateURIDashes(false);
 $routes->set404Override();
 $routes->setAutoRoute(true);
 
 $routes->get('/', 'Home::index'); 
 
 if (file_exists(APPPATH . 'Modules')) {
     $modulesPath = APPPATH . 'Modules/';
     $modules = scandir($modulesPath);
     foreach ($modules as $module) {
         if ($module === '.' || $module === '..') {
             continue;
         }
         $modulePath = $modulesPath . $module;
         if (is_dir($modulePath)) {
             $routesPath = $modulePath . '/Config/Routes.php';
             if ($module !== 'Pages' && file_exists($routesPath)) {
                 require $routesPath;
             }
         }
     }
     $routesPath = $modulesPath . 'Pages' . '/Config/Routes.php';
     if (file_exists($routesPath)) {
         require $routesPath;
     }
 }