<?php

namespace App\Models\Subscriptions\RateLimiters;

use App\Models\Subscriptions\Interfaces\LimiterInterface;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class GuestLimiter implements LimiterInterface
{

    public function getLimiter($id) : array
    {
       return [
           (new WithoutOverlapping($id))->releaseAfter(60),
           new RateLimited('backups')
       ];
    }
}
