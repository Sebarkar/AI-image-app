<?php

namespace App\Http\Controllers\Front;

use App\Events\Train\TrainStarted;
use App\Helpers\Statuses;
use App\Models\User;
use App\Models\UserAiModel;
use App\Services\AIs\AIClient;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use App\Services\AIs\RequestHelper;
use App\Services\Files\FileStorage;
use App\Services\Forms\FormInstance;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TrainController
{
    public function train(Request $request)
    {

        $model = RequestHelper::getPredictModel($request->get('model_name'), $request->get('model_owner'));

        $validator = Validator::make($request->all(), FormInstance::makeValidatorsFromForm($model->train));

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $typeBasedInput = FormInstance::getTypeBasedInput(formWithTypes: $model->train, input: $validator->validated());

        $task = Task::create([
            'user_id' => $request->user()->id,
            'provider' => 'replicate',
            'model' => $request->get('model_owner') . '/' . $request->get('model_name'),
            'user_model_id' => $model->user_model_id,
            'type' => 'train',
            'status' => Statuses::STATUS_AWAITING
        ]);

        $provider = AIClient::provider($task->provider);

        if ($model->shouldAddFieldToModelWhenTrain()) {
            UserAiModel::where('id', $task->user_model_id)
                ->update([
                    'data' => $model->getFieldToModelWhenTrain($typeBasedInput),
                    'status' => Statuses::STATUS_RUNNING
                ]);
        }

        $data = [
            ...$typeBasedInput,
            ...['input_images' => FileStorage::storeZip($validator->getValue('input_images'), $task, 'train')]
        ];

        $useModel = $model->parent_model ?? $model;

        $task->update([
            'data' => [
                'input' => $data,
                'model_name' => $useModel->name,
                'model_owner' => $useModel->owner,
                'version_id' => $useModel->version_id,
                'webhook' => env('WEBHOOK_URL') . '/api/v1/task-webhook/' . $task->id,
                'destination' => "sebarkar/fdsjhklkjs323"
            ]
        ]);

//        Bus::chain([
//            new Predict($task),
//        ])->dispatch();
        $provider->createTraining($task->data);

        TrainStarted::dispatch($task);

        return response()->json($task);
    }
}
