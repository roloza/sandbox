<?php

/**
 * Routes SERVICE PING PONG
 */

// SERVICE PING PONG ROUTES

/**
 * TESTING ROUTE
 */

use App\Http\Controllers\PingController;

Route::prefix('song')
    ->name('song.')
    ->group(function () {
        Route::get(('/'), [\App\Http\Controllers\SongController::class, 'index']);
    });