<?php

namespace App\Models\Bots;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $fillable = ['key', 'target', 'provider'];

}
