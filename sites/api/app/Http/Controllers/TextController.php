<?php

namespace App\Http\Controllers;

/**
 * Class TextController
 * @package App\Http\Controllers
 */
class TextController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Text!',
            'data' => ''
        ], 200);

    }
}