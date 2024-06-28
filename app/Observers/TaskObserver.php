<?php

namespace App\Observers;

use App\Helpers\Statuses;
use App\Models\Task;
use App\Models\UserAiModel;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->type === 'train') {
            if ($task->processing()) {
                UserAiModel::where('id', $task->user_model_id)->update(['status' => Statuses::STATUS_RUNNING]);
            } elseif ($task->completed()) {
                UserAiModel::where('id', $task->user_model_id)->update(['status' => Statuses::STATUS_COMPLETED]);
            }
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
