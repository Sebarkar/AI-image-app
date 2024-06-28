<?php

namespace App\Http\Controllers\Front;

use App\Events\Predict\PredictStarted;
use App\Jobs\Predict;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use App\Services\AIs\RequestHelper;
use App\Services\Forms\FormInstance;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

class PredictionController
{
    public function predict(Request $request)
    {
        $model = RequestHelper::getPredictModel($request->get('model_name'), $request->get('model_owner'));

        $typeBasedInput = FormInstance::getTypeBasedInput(formWithTypes: $model->predict, input: $request->all());


        $validator = Validator::make($typeBasedInput, FormInstance::makeValidatorsFromForm($model->predict));

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = Task::create([
            'user_id' => $request->user()->id,
            'provider' => 'replicate',
            'type' => 'predict',
            'status' => ResponseInstance::STATUS_AWAITING
        ]);

        //Save and add files link to input
        $typeBasedInputWithFilesLinks = FormInstance::getFilesLinksFromInput(
            formWithTypes: $model->predict,
            input: $typeBasedInput,
            target: $request->user(),
            targetType: 'request'
        );

        $task->update([
            'data' => [
                'model_title' => $model->title,
                'version_id' => $model->version_id,
                'webhook' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'stream' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'input' => $typeBasedInputWithFilesLinks
            ],
        ]);

        PredictStarted::dispatch($task);

        Bus::chain([
            new Predict($task),
        ])->dispatch();

        return response()->json($task);
    }
}
