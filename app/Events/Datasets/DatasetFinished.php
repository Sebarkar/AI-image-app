<?php

namespace App\Events\Datasets;

use App\Http\Resources\DatasetResource;
use App\Http\Resources\TaskResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DatasetFinished implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $dataset;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('dataset.' . $this->dataset->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'dataset.finished';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'task' => new DatasetResource($this->dataset),
        ];
    }
}
