<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Interfaces\CRUDController;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

class UserController extends Controller implements CRUDController
{
    public function index(Request $request) : JsonResponse
    {
        $rowsPerPage = $request->json('options.itemsPerPage');
        $page = ($request->json('options.page'));
        $start = ($request->json('options.search.start'));
        $end = ($request->json('options.search.end'));

        $models = \DB::table('users');

        //Sorting data...
        if ($sortDesc = $request->json('options.sortDesc') && $request->json('options.sortBy')) {
            $sortTable = $request->json('options.sortBy')[0];
            $sortType = $sortDesc[0] == false ? 'asc' : 'desc';
            $models = $models->orderBy($sortTable, $sortType);
        }

        if ($start) {
            $models = $models->where('updated_at', '>=', $start);
        }

        if ($end) {
            $models = $models->where('updated_at', '<=', $end);
        }

        $models->orderBy('created_at', 'desc');

        $count = $models->count();

        if ($page) {
            $models = $models->take($rowsPerPage)->skip($rowsPerPage * ($page - 1));
        } else {
            $models = $models->take($rowsPerPage);
        }

        $result = $models->get();

        return response()->json([
            'data' => $result,
            'count' => $count
        ]);
    }

    public function read(Request $request) : JsonResponse
    {
        return response()->json();
    }

    public function update(Request $request) : JsonResponse
    {
        return response()->json();
    }

    public function create(Request $request) : JsonResponse
    {
        return response()->json();
    }

    public function delete(Request $request) : JsonResponse
    {
        return response()->json();
    }

}
