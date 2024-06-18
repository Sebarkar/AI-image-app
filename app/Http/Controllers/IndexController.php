<?php

namespace App\Http\Controllers;

use App\Models\User;
use HalilCosdu\Replicate\Facades\Replicate;
use Illuminate\Http\Request;
use App\Models\Task;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'version' => '7762fd07cf82c948538e41f63f77d685e02b063e37e496e96eefd46c929f9bdc',
            'input' => [
                'prompt' => 'red pony is running in the field.'
            ],
        ];
//        $response = Replicate::createPrediction($data);
        $response = Replicate::getPrediction('r5rynyrm1nrgg0cfp2xt69cx7c');
        dd($response->object());
        return response()->json($response);

        $user = User::where('id', 10)
            ->first();

        $task = Task::create([
            'owner_id' => $user->id,
        ]);

        $task->runQueue(Task::NO_SUBSCRIPTION_QUEUE);

        return response()->json($task);
    }
}
