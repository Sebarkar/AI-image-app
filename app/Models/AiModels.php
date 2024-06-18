<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModels extends Model
{

    protected $table = 'models';
    protected $fillable = [
        'cover_image_url',
        'description',
        'github_url',
        'license_url',
        'user_fine_tune',
        'user_id',
        'provider',
        'name',
        'owner',
        'paper_url',
        'run_count',
        'url',
        'visibility',
    ];

    public function version()
    {
        return $this->hasOne(RegularAiModelVersion::class, 'model_id', 'id')->latest();
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'target_id', 'id')->where('target_type', 'model');
    }
}
