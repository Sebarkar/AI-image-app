<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Image extends Files
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->whereIn('extension', ['jpg', 'jpeg', 'png', 'gif']);
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
