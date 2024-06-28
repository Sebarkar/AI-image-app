<?php

namespace App\Services\AIs\Instances\Responses;

class PredictionResponseInstance extends ResponseInstance
{
    public $id;
    public $model;
    public $version;
    public $input;
    public $logs;
    public $output;
    public $error;
    public $status;
    public $token;
    public $created_at;
    public $data_removed;
    public $started_at;
    public $finished_at;
    public $metrics;
    public $urls;

    public function checkStatus()
    {
        if (!in_array($this->status, $this->allowed_statuses)) {
            throw new \Exception("Invalid status: $this->status");
        }
        return true;
    }

    public static function make(string $provider)
    {
        switch ($provider) {
            case 'replicate':
                return new \App\Services\AIs\Providers\Replicate\Response\PredictionResponse();
            default:
                throw new \Exception("Unsupported provider: $provider");
        }
    }

    public function completed()
    {
        return $this->status === ResponseInstance::STATUS_COMPLETED || $this->status === ResponseInstance::STATUS_SUCCEEDED;
    }
}
