<?php

namespace App\Services\AIs\Instances\Responses;

class ResponseInstance
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_RUNNING = 'running';
    const STATUS_FAILED = 'failed';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_UNKNOWN = 'unknown';

    protected $allowed_statuses = [
        ResponseInstance::STATUS_COMPLETED,
        ResponseInstance::STATUS_RUNNING,
        ResponseInstance::STATUS_FAILED,
        ResponseInstance::STATUS_CANCELED,
        ResponseInstance::STATUS_UNKNOWN,
    ];
}
