<?php

namespace App\Jobs;

use App\Events\Train\TrainFinished;
use App\Models\Bots\Bot;
use App\Services\AIs\AIClient;
use App\Services\Image\ImageProcessor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class TrainModelFinish implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */

    public $task;
    public $tries = 10;
    public $maxExceptions = 10;

    /**
     * Create a new job instance.
     * @return void
     * @var $image
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): Carbon
    {
        return now()->addSeconds(20);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        AIClient::provider($this->task->provider)
            ->saveUserModelVersion($this->task->last_response['output']['version'], $this->task->user_model_id);

        TrainFinished::dispatch($this->task);
    }
}
