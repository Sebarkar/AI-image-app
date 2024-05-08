<?php

namespace App\Models;

use App\Jobs\GenerateVariants;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const NO_SUBSCRIPTION_QUEUE = 'guest_queue';
    const BASIC_QUEUE = 'basic_queue';
    const FAST_QUEUE = 'fast_queue';

    protected $fillable = ['owner_id'];

    public function runQueue($queue = self::NO_SUBSCRIPTION_QUEUE) :void
    {
        GenerateVariants::dispatch($this);
    }
}
