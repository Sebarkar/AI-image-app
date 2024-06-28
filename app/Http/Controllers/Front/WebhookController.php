<?php

namespace App\Http\Controllers\Front;

use App\Events\Predict\PredictFinished;
use App\Events\Predict\PredictStatusChanged;
use App\Events\Train\TrainStatusChanged;
use App\Jobs\SaveImages;
use App\Jobs\TrainModelFinish;
use App\Models\Task;
use App\Services\AIs\Instances\Responses\PredictionResponseInstance;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class WebhookController
{
    public function handle(Request $request, string $task_id)
    {

        $task = Task::where('id', $task_id)->first();

        $response = PredictionResponseInstance::make($task->provider)->handle((object)$request->collect()->toArray());

        $task->update([
            'status' => $response->status,
            'finished_at' => $response->finished_at,
            'result' => is_string($response->output) ? [$response->output] : $response->output,
            'last_response' => $response,
        ]);

        if ($task->type === 'predict') {
            if ($response->completed()) {
                Bus::chain([
                    new SaveImages($task),
                    function () use ($task) {
                        $task->refresh();
                        $task->loadMissing('images');
                        PredictFinished::dispatch($task);
                    },
                ])->dispatch();
            } else {
                PredictStatusChanged::dispatch($task);
            }
        }

        if ($task->type === 'train') {
            if ($response->completed()) {
                $task->update([
                    'status' => ResponseInstance::STATUS_COMPLETED,
                    'model' => $response->model,
                    'version' => $response->version,
                    'model_owner' => 'stability-ai/sdxl',
                    'model_name' => 'stability-ai/sdxl',
                ]);

                Bus::chain([
                    new TrainModelFinish($task),
                ])->dispatch();
            } else {
                TrainStatusChanged::dispatch($task);
            }
        }

        return response()->json([
            'message' => 'Webhook received',
        ]);
    }
}
