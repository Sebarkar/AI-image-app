<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = User::where('id', 10)
            ->first();

        $task = Task::create([
            'owner_id' => $user->id,
        ]);

        $task->runQueue(Task::NO_SUBSCRIPTION_QUEUE);

        return response()->json($task);
    }
}
