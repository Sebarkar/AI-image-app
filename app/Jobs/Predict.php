<?php

namespace App\Jobs;

use App\Events\Predict\PredictStatusChanged;
use App\Models\Bots\Bot;
use App\Services\AIs\AIClient;
use App\Services\AIs\Instances\Responses\ResponseInstance;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Predict implements ShouldQueue
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
        $bot = Bot::where('provider', $this->task->provider)->where('connection_left', '>', 0)->first();
        if (!$bot) {
            $this->release(10);
        }
        $bot->increment('connections');

        $data = $this->task->data;

        $result = AIClient::provider($this->task->provider)->createPrediction($data);

        $this->task->update([
            'status' => ResponseInstance::STATUS_RUNNING,
            'provider_id' => $result->id,
            'last_response' => $result,
        ]);

        PredictStatusChanged::dispatch($this->task);

//        $this->task->finished_at = now();
//        $this->task->save();

        $bot->decrement('connections');
    }
}
