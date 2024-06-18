<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Datasets extends Model
{
    public $table = 'datasets';

    protected $fillable = [
        'user_id',
        'is_active',
        'title',
        'status',
        'model',
        'version',
        'model_owner',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'target_id', 'id', )->where('target', 'datasets');
    }

    public function archive()
    {
        return $this->hasOne(Archives::class, 'target_id', 'id', )->where('target', 'datasets');
    }

    public function getNameForTokenAi()
    {
        return $this->id . mb_strtoupper(str_replace(' ', '', $this->title));
    }

    public function removeAllContent()
    {
        $this->images()->delete();
        $this->archive()->delete();
        $this->delete();
    }
}
