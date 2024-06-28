<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

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
            $builder->whereIn('extension', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff']);
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function remove()
    {
        if (Storage::disk($this->storage)->delete($this->storage_path)) {
            parent::remove();
        };
    }
}
