<?php

namespace App\Http\Controllers\Front;

use App\Events\Task\TaskFinished;
use App\Events\Task\TaskStarted;
use App\Events\Task\TaskStatusChanged;
use App\Jobs\GenerateVariants;
use App\Jobs\Predict;
use App\Jobs\SaveImages;
use App\Models\Datasets;
use App\Services\AIs\AIClient;
use App\Services\AIs\Instances\Responses\PredictionResponseInstance;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use App\Services\Files\FileStorage;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class PredictionController
{
    public function predict(Request $request)
    {
        $task = Task::create([
            'user_id' => $request->user()->id,
            'provider' => 'replicate',
            'type' => 'predict',
            'status' => ResponseInstance::STATUS_AWAITING
        ]);

        $dataset = Datasets::where('user_id', $request->user()->id)->where('id', $request->json('selectedDataset.id'))->first();

        $task->update([
            'data' => [
                'model' => $dataset->model,
                'model_owner' => $dataset->model_owner,
                'model_id' => $dataset->version,
                'webhook' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'stream' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'input' => $request->json()->all(),
            ]
        ]);

        AIClient::provider($task->provider)->createPrediction($task->data);

        TaskStarted::dispatch($task);

        Bus::chain([
            new Predict($task),
            new SaveImages($task),
        ])->dispatch();

        return response()->json($task);
    }
}
