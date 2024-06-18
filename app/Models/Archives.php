<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Archives extends Files
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->whereIn('extension', ['zip']);
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
