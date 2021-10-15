<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'SONG!',
            'data' => ''
        ], 200);

    }
}
