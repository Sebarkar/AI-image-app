<?php

namespace App\Services\AIs;

use App\Services\AIs\Instances\DataAiRequest;
use App\Services\AIs\Instances\Request\UserVersionRequest;
use App\Services\AIs\Instances\Responses\PredictionResponseInstance;
use App\Services\AIs\Instances\Responses\TrainResponseInstance;

class AIClient
{
    private $provider;
    public static function provider(string $providerName): AIClient
    {
        $object = new self();
        if (config('ais.providers.' . $providerName)) {
            $providerClass = config('ais.providers.' . $providerName . '.model');
            $object->provider = $providerClass::init();
        } else {
            throw new \Exception("Unsupported provider: $providerName");
        }
        return $object;
    }

    public function getUserVersion(UserVersionRequest $data)
    {
        return $this->provider->getUserVersion($data);
    }

    public function saveUserModelVersion($data, $target_id)
    {
        return $this->provider->saveUserModelVersion($data, $target_id);
    }

    public function createPrediction(array $data) : PredictionResponseInstance
    {
        return $this->provider->createPrediction($data);
    }

    public function getAvailableModels()
    {
        return $this->provider->getAvailableModels();
    }

    public function createModel()
    {
        return $this->provider->createModel();
    }

    public function getModel($owner = '', $name = '', $version = '')
    {
        return $this->provider->getModel($owner, $name, $version);
    }

    public function removeModel()
    {
        return $this->provider->removeModel();
    }

    public function saveAvailableModels($url = null)
    {
        return $this->provider->saveAvailableModels($url);
    }

    public function createTraining(array $data) : TrainResponseInstance
    {
        return $this->provider->createTraining($data);
    }
}
