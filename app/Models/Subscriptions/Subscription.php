<?php

namespace App\Models\Subscriptions;

use App\Models\Subscriptions\RateLimiters\GuestLimiter;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function getLimiter($id)
    {
        return $this->getRateLimiters()->getLimiter($id);
    }

    public function getRateLimiters()
    {
        return new GuestLimiter();
    }
}
