<?php

namespace App\Services\AIs\Providers\Replicate;

use App\Models\AiModels;
use App\Models\AiModelVersion;
use App\Models\Task;
use App\Services\AIs\AIClient;
use App\Services\AIs\Instances\DataAiRequest;
use App\Services\AIs\Instances\ModelInstance;
use App\Services\AIs\Instances\Request\TrainInstance;
use App\Services\AIs\Instances\Request\UserVersionRequest;
use App\Services\AIs\Instances\Responses\PredictionResponseInstance;
use App\Services\AIs\Instances\Responses\TrainResponseInstance;
use App\Services\AIs\Interfaces\CanTrainInterface;
use App\Services\AIs\Interfaces\SourceInterface;
use App\Services\AIs\Providers\Replicate\Errors\JsonEncodedException;
use App\Services\AIs\Providers\Replicate\Request\ReplicateTrainRequest;
use App\Services\AIs\Providers\Replicate\Response\PredictionResponse;
use App\Services\AIs\Providers\Replicate\Response\TrainResponse;
use App\Services\AIs\RequestHelper;
use App\Services\Files\FileStorage;
use App\Services\Image\ImageProcessor;
use GuzzleHttp\Psr7\Request;
use HalilCosdu\Replicate\Facades\Replicate as ReplicateProvider;
use HalilCosdu\Replicate\Services\ModelService;
use Illuminate\Support\Facades\Http;

class Replicate implements SourceInterface, CanTrainInterface
{
    private $provider;
    private $request;
    private $model;

    public static function init()
    {
        $object = new self();
        $object->provider = new ReplicateProvider();
        return $object;
    }

    public function setDataForPrediction(DataAiRequest $data): self
    {
        $this->request = [
            'version' => $data->version_id,
            'webhook' => $data->webhook,
            'input' => $data->input,
        ];
        return $this;
    }

    public function setDataForTrain(TrainInstance $data): self
    {
        $this->request = [
            'input' => $data->input,
            'destination' => "sebarkar/fdsjhklkjs323",
            'webhook' => $data->webhook,
        ];
        return $this;
    }

    public function createPrediction(array $data): PredictionResponseInstance
    {
        $data = DataAiRequest::parse($data);
        $this->setDataForPrediction($data);
        $result = ReplicateProvider::createPrediction($this->request);
        if (!$result->successful()) {
            throw new JsonEncodedException($result->object()->detail);
        }
        return PredictionResponse::handle($result->object());
    }

    public function getUserVersion(UserVersionRequest $data)
    {
        return ReplicateProvider::getModelVersion($data->model_owner, $data->model_name, $data->version_id);
    }

    public function createTraining(array $data): TrainResponseInstance
    {
        $data = TrainInstance::parse($data);
        $this->setDataForTrain($data);
        $result = ReplicateProvider::createTraining($data->model_owner, $data->model_name, $data->version_id, $this->request);

        if (!$result->successful()) {
            throw new \JsonException($result->object()->detail);
        }

        return TrainResponse::handle($result->object());
    }

    public function getAvailableModels()
    {
        return ReplicateProvider::listModels();
    }

    public function saveAvailableModels($url = null)
    {
        if ($url) {
            $body = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('replicate.api_token'),
                'Content-Type' => 'application/json',
            ])->get($url)->getBody();
            $response = (object)json_decode((string)$body, true);
            $json = json_encode($response);
            $response = json_decode($json);
            //https://api.replicate.com/v1/models?cursor=cD0yMDI0LTA2LTA4KzA5JTNBNDYlM0EyOC45NzU0NTQlMkIwMCUzQTAw
        } else {
            $response = $this->getAvailableModels()->object();
        }
        $models = $response->results;

        foreach ($models as $model) {
            $version = $model->latest_version;

            if (!isset($version->openapi_schema->openapi)) {
                continue;
            }

            $modelad = AiModels::firstOrCreate(
                ['name' => $model->name, 'owner' => $model->owner, 'provider' => 'replicate'],
                [
                    'description' => $model->description,
                    'github_url' => $model->github_url,
                    'paper_url' => $model->paper_url,
                    'run_count' => $model->run_count,
                    'url' => $model->url,
                    'cover_image_url' => $model->cover_image_url,
                    'visibility' => $model->visibility,
                    'license_url' => $model->license_url
                ],
            );

            if ($model->cover_image_url) {
                if (!$modelad->image) {
                    $modelad->saveCoverImageLocal($model->cover_image_url);
                }
            }

            $this->saveModelVersion($modelad->id, $version);
        }

        if (isset($response->next) && $response->next) {
            $this->saveAvailableModels($response->next);
        }

        return ReplicateProvider::listModels();
    }

    public function getUserVersionRequestFromString($title) : UserVersionRequest
    {
        $data = explode(':', $title);
        $names = explode('/', $data[0]);

        $versionRequest = new UserVersionRequest();
        $versionRequest->model_name = $names[1];
        $versionRequest->model_owner = $names[0];
        $versionRequest->version_id = $data[1];
        return $versionRequest;
    }

    public function saveUserModelVersion(string $title, int $target_id)
    {
        $versionRequest = $this->getUserVersionRequestFromString($title);

        $version = $this->getUserVersion($versionRequest)->object();

        return $this->saveModelVersion($target_id, $version, AiModelVersion::TARGET_USER);
    }

    public function saveModelVersion(string $model_id, $version, string $target = AiModelVersion::TARGET_REGULAR)
    {
        return AiModelVersion::firstOrCreate(
            ['model_id' => $model_id, 'version_id' => $version->id, 'target' => $target],
            [
                'cog_version' => $version->cog_version,
                'created_at' => $version->created_at,
                'schema_version' => $version->openapi_schema->openapi,
                'schemas' => $version->openapi_schema->components,
            ],
        );
    }

    public function getName()
    {
        return 'replicate';
    }

    public function createModel()
    {
        return 'replicate';
    }

    public function getModel($owner = '', $name = '', $version = '')
    {
        return ReplicateProvider::getModelVersion($owner, $name, $version);
    }

    public function removeModel()
    {
        return 'replicate';
    }
}
