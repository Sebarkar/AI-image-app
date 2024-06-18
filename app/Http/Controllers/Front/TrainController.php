<?php

namespace App\Http\Controllers\Front;

use App\Events\Task\TaskStatusChanged;
use App\Models\Datasets;
use App\Services\AIs\AIClient;
use App\Services\AIs\Instances\Request\PredictionInstance;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use Illuminate\Http\Request;
use App\Models\Task;

class TrainController
{
    public function train(Request $request)
    {
        $dataset = Datasets::where('user_id', $request->user()->id)
            ->with('archive')->where('id', $request->json('dataset_id'))->first();

        $task = Task::create([
            'user_id' => $request->user()->id,
            'provider' => 'replicate',
            'type' => 'train',
            'target_id' => $request->json('dataset_id'),
            'status' => ResponseInstance::STATUS_AWAITING
        ]);

        $task->update([
            'data' => [
                'input' => [
                    'input_images' => $dataset->archive->getSrc(),
                    'use_face_detection_instead' => false,
                    'token_string' => $dataset->getNameForTokenAi(),
                    'caption_prefix' => 'A photo of ' . $dataset->getNameForTokenAi(),
                    ...$request->json()->all()
                ],
                'model_name' => 'sdxl',
                'model_owner' => 'stability-ai',
                'version_id' => '7762fd07cf82c948538e41f63f77d685e02b063e37e496e96eefd46c929f9bdc',
                'webhook' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'destination' => "sebarkar/fdsjhklkjs323"
            ]
        ]);

        AIClient::provider($task->provider)->createTraining($task->data);

        return response()->json($task);
    }
}
