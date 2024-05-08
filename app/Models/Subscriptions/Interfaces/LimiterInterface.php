<?php

namespace App\Models\Subscriptions\Interfaces;

interface LimiterInterface
{
    public function getLimiter($id) : array;
}
