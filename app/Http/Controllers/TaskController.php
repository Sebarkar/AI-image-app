<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function getOwner(Request $request)
    {
        return response()->json('123');
    }

    public function isRegistered()
    {

    }
}
