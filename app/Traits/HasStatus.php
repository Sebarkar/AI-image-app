<?php

namespace App\Traits;

use App\Helpers\Statuses;

trait HasStatus
{
    public function completed()
    {
        return $this->status === Statuses::STATUS_COMPLETED || $this->status === Statuses::STATUS_SUCCEEDED;
    }

    public function processing()
    {
        return $this->status === Statuses::STATUS_RUNNING;
    }
}
