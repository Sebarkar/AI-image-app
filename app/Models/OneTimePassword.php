<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class OneTimePassword extends Model
{
    use Prunable;

    protected $fillable = ['user_id', 'code', 'expired_at', 'column'];

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('expired_at', '<=', now());
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
