<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Guest  extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'guest_token',
    ];

    public function getTaskUserId()
    {
        return 'guest' . $this->id;
    }
}
