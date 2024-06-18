<?php

namespace App\Http\Controllers\Front;

use App\Http\Resources\DatasetResource;
use App\Http\Resources\TaskResource;
use App\Models\Datasets;
use App\Models\Task;
use App\Services\Files\FileStorage;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)
            ->with('images')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json(TaskResource::collection($tasks));
    }
}
