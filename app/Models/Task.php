<?php

namespace App\Models;

use App\Jobs\GenerateVariants;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const NO_SUBSCRIPTION_QUEUE = 'guest_queue';
    const BASIC_QUEUE = 'basic_queue';
    const FAST_QUEUE = 'fast_queue';

    protected $fillable = ['user_id', 'provider', 'status', 'provider_id', 'last_response', 'result', 'type', 'data', 'target_id'];

    protected $casts = [
        'last_response' => 'array',
        'data' => 'array',
    ];

    public function runQueue($queue = self::NO_SUBSCRIPTION_QUEUE) :void
    {
        GenerateVariants::dispatch($this);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'target_id', 'id')->where('target', 'tasks');
    }
}
