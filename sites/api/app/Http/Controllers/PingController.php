<?php

namespace App\Http\Controllers;

/**
 * Class PingController
 * @package App\Http\Controllers
 */
class PingController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'PONG!',
            'data' => ''
        ], 200);

    }
}