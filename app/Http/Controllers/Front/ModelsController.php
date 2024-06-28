<?php

namespace App\Http\Controllers\Front;

use App\Events\Models\ModelsCreated;
use App\Models\UserAiModel;
use App\Services\AIs\AIClient;
use App\Services\AIs\RequestHelper;
use App\Services\Files\FileStorage;
use App\Services\Forms\Model\CreateModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelsController
{
    public function index(Request $request)
    {
        if ($request->json('search')) {
            $models = RequestHelper::searchPredictModels($request->json('search'));
        } else {
            $models = RequestHelper::getPredictModels(false);
        }

        return response()->json($models);
    }

    public function read(Request $request)
    {
        $model = RequestHelper::getPredictModel($request->json('name'), $request->json('owner'));

        if (!$model) {
            return response()->json(['error' => 'Models data not found'], 451);
        }
        return response()->json($model);
    }

    public function getUserModel(Request $request)
    {
        $model = RequestHelper::getUserModel($request->json('model_id'));

        if (!$model) {
            return response()->json(['error' => 'Models data not found'], 451);
        }
        return response()->json($model);
    }


    public function getModels(Request $request)
    {
        dd(AIClient::provider('replicate')->getModel('sebarkar', 'fdsjhklkjs323', '3cb397a1cef75a85da67893858a73b0e1111252a40ccbded2f815db4451883bc')->object());
        $client = AIClient::provider('replicate')->saveAvailableModels();
        return response()->json($client->results);
    }

    public function createModel(Request $request)
    {
        $validator = Validator::make($request->all(), CreateModelForm::getValidators());

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $model = UserAiModel::create(
            [
                ...$request->all(),
                'provider' => 'replicate',
                'owner' => auth()->user()->email,
                'user_fine_tune' => true,
                'type' => 'image',
                'trained_on' => 'stability-ai/sdxl',
                'user_id' => auth()->user()->id,
            ]
        );

        if ($request->hasFile('image')) {
            $images = FileStorage::storeImages([$request->file('image')], $model,'models');
            $model->cover_image_url = $images[0]?->getSrc();
            $model->save();
        }

        ModelsCreated::dispatch($model);

        return response()->json($model);
    }

    public function removeModel()
    {
        $client = AIClient::provider('replicate')->removeModel();
        return response()->json($client->results);
    }

    public function getCreateForm()
    {
        return response()->json(CreateModelForm::getForm());
    }
}
