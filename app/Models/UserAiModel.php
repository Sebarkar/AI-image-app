<?php

namespace App\Models;

use App\Services\AIs\Instances\ModelInstance;
use App\Services\AIs\RequestHelper;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class UserAiModel extends Model
{
    use HasStatus;

    const STATUS_TRAINING = 'training';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_TRAINED = 'trained';
    const STATUS_CREATED = 'created';

    protected $table = 'user_models';

    protected $casts = [
        'data' => 'array',
    ];
    protected $fillable = [
        'cover_image_url',
        'description',
        'user_fine_tune',
        'type',
        'data',
        'status',
        'user_id',
        'provider',
        'name',
        'name',
        'owner',
        'trained_on',
        'run_count',
    ];

    public function version()
    {
        return $this->hasOne(UserAiModelVersion::class, 'model_id', 'id')
            ->where('target', AiModelVersion::TARGET_USER)
            ->latest();
    }

    public function getParent() : ModelInstance | null
    {
        return RequestHelper::getModelByTitle($this->trained_on);
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'target_id', 'id')->where('target', 'user_model');
    }
}
