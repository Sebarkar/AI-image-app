<?php

namespace App\Services\AIs\Interfaces;

use App\Services\AIs\Instances\ModelInstance;
use App\Services\AIs\Instances\Responses\PredictionResponseInstance;
use App\Services\AIs\Providers\Replicate\Request\ReplicatePredictionRequest;

interface SourceInterface
{
    public function getAvailableModels();
}
