<?php

namespace App\Services\AIs\Instances;

use App\Models\AiModels;
use App\Models\UserAiModel;
use App\Services\AIs\Instances\Schemas\OpenApiSchemas;
use App\Services\AIs\Interfaces\Schemas\SchemaInterface;
use function PHPUnit\Framework\isInstanceOf;

class ModelInstanceBuilder
{
    public static function build(UserAiModel | AiModels $data, bool $withForm = true): ModelInstance
    {
        $model = new ModelInstance();
        $model->user_id = $data->user_id;
        $model->name = $data->name;
        $model->type = $data->type;
        $model->license_url = $data->license_url;
        $model->description = $data->description;
        $model->title = $data->owner . '/' . $data->name;
        $model->public = true;
        $model->user_fine_tune = $data->user_fine_tune;
        $model->owner = $data->owner;
        $model->image = $data->cover_image_url ?? '/images/model-default.jpg';
        if ($withForm) {
            //In case New or Empty user's model = use parent forms || Wrong model
            if (!$data->version) {
                if ($data instanceof UserAiModel) {
                    $parentModel = self::getParentForUserModel($data);
                    if ($parentModel) {
                        $model->train = $parentModel->train;
                        $model->predict = $parentModel->predict;
                    }
                }
            } else {
                $model->train = self::buildTrainForm($data);
                $model->predict = self::buildPredictForm($data);
            }
        }
        return $model;
    }

    private static function getParentForUserModel(UserAiModel $model) : ModelInstance | null
    {
        $parentModel = $model->getParent();

        return $parentModel ?? null;
    }

    public static function buildPredictForm(AiModels | UserAiModel $model): array
    {
        $schemaBuilder = self::getSchema($model->version->schema);
        $schemaBuilder->data = $model->version->schemas;
        return $schemaBuilder->buildPredictForm();
    }

    public static function buildTrainForm(AiModels | UserAiModel $model): array
    {
        $schemaBuilder = self::getSchema($model->version->schema);
        $schemaBuilder->data = $model->version->schemas;
        return $schemaBuilder->buildTrainForm();
    }

    public static function getSchema(string $schemaTitle): SchemaInterface
    {
        switch ($schemaTitle) {
            case 'openapi':
                return new OpenApiSchemas();
            default:
                return throw new \Exception('Schema not found');
        }
    }
}
