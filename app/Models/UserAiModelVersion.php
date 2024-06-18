<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserAiModelVersion extends AiModelVersion
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('target', 'user');
        });
    }
}
