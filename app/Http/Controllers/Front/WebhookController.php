<?php

namespace App\Http\Controllers\Front;

use App\Events\Datasets\DatasetFinished;
use App\Events\Task\TaskFinished;
use App\Events\Task\TaskStatusChanged;
use App\Jobs\SaveImages;
use App\Models\Datasets;
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
            'result' => $response->output,
            'last_response' => $response,
        ]);

        if ($task->type === 'predict') {
            if ($response->completed()) {
                Bus::chain([
                    new SaveImages($task),
                    function () use ($task) {
                        $task->refresh();
                        $task->loadMissing('images');
                        TaskFinished::dispatch($task);
                    },
                ])->dispatch();
            } else {
                TaskStatusChanged::dispatch($task);
            }
        }

        if ($task->type === 'train') {
            $dataset = Datasets::where('id', $task->target_id)->first();
            $dataset->update([
                'status' => ResponseInstance::STATUS_COMPLETED,
                'model' => $response->model,
                'version' => $response->version,
                'model_owner' => 'stability-ai/sdxl',
            ]);
            DatasetFinished::dispatch($dataset);
        }

        return response()->json([
            'message' => 'Webhook received',
        ]);
    }
}
