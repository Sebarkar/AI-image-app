<?php

namespace App\Jobs;

use App\Events\Train\PredictFinished;
use App\Models\Bots\Bot;
use App\Services\Image\ImageProcessor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class GenerateVariants implements ShouldQueue
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

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->task->owner_id))->releaseAfter(30),
        ];
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
        $bot = Bot::where('provider', 'midjourney')->where('connection_left', '>', 0)->first();

        if (!$bot) {
            $this->release(10);
        }

        $bot->increment('connections');
//        $ai = new MidJourneyBot($bot);
//        $ai->setPrompt(
//            'make girl in western style with hat and red dress with flower in hand',
//            '8k octane render, photorealistic --ar 9:20 --v 5',
//            ['img' => 'https://barkar.apartments/images/alinabarkar.jpg']
//        );

        $imageProcessor = new ImageProcessor('variants');

        $path = '/' . mb_substr($this->task->owner_id * 1000, 0, 4) . '/' . $this->task->owner_id . '/';
        $suffix = $this->task->id;

        $this->task->result = $imageProcessor->setPath($path)
            ->setSuffix($suffix)
            ->setImage('https://barkar.apartments/images/alinabarkar.jpg')
            ->make(ImageProcessor::CUT_QUADRANT)
            ->getLinks();

        $this->task->finished_at = now();
        $this->task->save();

        $bot->decrement('connections');
        PredictFinished::dispatch($this->task, 'finish');
    }
}
