<?php

namespace App\Services\AIs;

use App\Models\AiModels;
use App\Models\UserAiModel;
use App\Services\AIs\Instances\ModelInstanceBuilder;
use App\Services\AIs\Instances\ModelInstanceFactory;

class RequestHelper
{
    public static function getPredictModels($withForm = true)
    {
        $models = [];
        foreach (config('ais.providers') as $provider => $providerConfig) {
            foreach (config('ais.providers.' . $provider . '.available_models') as $modelConfig) {
                $models[] = $modelConfig['predict']::getDataProperties($withForm);
            }
        }

        $modelsDB = AiModels::with('version')
            ->whereNotIn('name', collect($models)->pluck('name'))
            ->whereNotIn('owner', collect($models)->pluck('owner'))
            ->limit(20)->orderByDesc('run_count')->get();
        $modelsDB = ModelInstanceFactory::build($modelsDB, $withForm);

        $userModels = UserAiModel::where('user_id', auth()->user()->id)->get();
        $userModels = ModelInstanceFactory::build($userModels, $withForm);

        return [...$userModels, ...$models, ...$modelsDB];
    }

    public static function getPredictModel($name, $owner, $withForm = true)
    {
        //Search in local files
        $models = [];
        foreach (config('ais.providers') as $provider => $providerConfig) {
            foreach (config('ais.providers.' . $provider . '.available_models') as $modelConfig) {
                $models[] = $modelConfig['predict']::getDataProperties($withForm);
            }
        }

        $model = collect($models)->where('owner', $owner)->where('name', $name)->first();

        if ($model !== null) {
            return $model;
        }
        //Search in local DB
        $model = AiModels::with('version')->where('owner', $owner)->where('name', $name)->first();

        //Search in local DB user's models
        if ($model === null) {
            $model = UserAiModel::with('version')->where('owner', $owner)->where('name', $name)->first();
        }
        if ($model !== null) {
            return ModelInstanceBuilder::build($model, $withForm);
        }

        return null;
    }

    public static function getUserModel($id, $withForm = true)
    {
        $model = UserAiModel::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($model !== null) {
            return ModelInstanceBuilder::build($model, $withForm);
        }

        return null;
    }
}
