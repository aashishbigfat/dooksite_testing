<?php

use App\Http\Controllers\Frontend\HomePageController;
use Illuminate\Support\Facades\Route;

Route::controller(HomePageController::class)->group(function() {
    Route::get('/','index')->name('index');
});
