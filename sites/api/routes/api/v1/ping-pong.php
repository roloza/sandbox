<?php

/**
 * Routes SERVICE PING PONG
 */

// SERVICE PING PONG ROUTES
/**
 * TESTING ROUTE
 */

use App\Http\Controllers\PingController;

Route::get(('ping'), [PingController::class, 'index']);