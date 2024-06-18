<?php

namespace App\Services\AIs\Providers\Replicate\Response;

use App\Services\AIs\Instances\Responses\TrainResponseInstance;
use App\Services\AIs\Interfaces\Responses\PredictionResponseInterface;
use App\Services\AIs\Interfaces\Responses\TrainResponseInterface;

class TrainResponse extends TrainResponseInstance implements TrainResponseInterface
{
    public static function handle($data) : TrainResponseInterface
    {
        $result = new self();
        $result->id = $data->id ?? null;
        $result->model = $data->model ?? null;
        $result->version = $data->version ?? null;
        $result->input = $data->input;
        $result->logs = $data->logs;
        $result->output = $data->output ?? null;
        $result->error = $data->error;
        $result->created_at = $data->created_at;
        $result->data_removed = $data->data_removed;
        $result->started_at = $data->started_at ?? null;
        $result->finished_at = $data->completed_at ?? null;
        $result->metrics = $data->metrics ?? null;
        $result->urls = $data->urls ?? [];

        $result->setStatus($data->status);

        return $result;
    }

    private function setStatus($status): void
    {
        if ($status === 'starting' || $status === 'processing') {
            $this->status = self::STATUS_RUNNING;
        } elseif ($status === 'succeeded') {
            $this->status = self::STATUS_COMPLETED;
        } elseif ($status === 'canceled') {
            $this->status = self::STATUS_CANCELED;
        } elseif ($status === 'failed') {
            $this->status = self::STATUS_FAILED;
        } else {
            $this->status = self::STATUS_UNKNOWN;
        }
        $this->checkStatus();
    }
}
