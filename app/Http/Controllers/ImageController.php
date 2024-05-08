<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function save(Request $request)
    {
        return response()->json('123');
    }
}
