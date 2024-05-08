<?php

namespace App\Models\Subscriptions\RateLimiters;

use App\Models\Subscriptions\Interfaces\LimiterInterface;

class BasicLimiter implements LimiterInterface
{
    public function getLimiter($id = null) : array
    {
        return [];
    }
}
