<?php

namespace App\Jobs;

use App\Services\Files\FileStorage;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveImages implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $this->task->refresh();
        if (!$this->task->last_response || array_key_exists('output', $this->task->last_response) === false) {
            return;
        }

        $images = $this->task->last_response['output'];
        if ($images) {
            foreach ($this->task->last_response['output'] as $image) {
                Log::info('SaveImages', [$image]);
                FileStorage::storeImageFromAiResponse($image, $this->task);
            }
        }
    }
}
