<?php

namespace App\Models;

use App\Services\AIs\Instances\ModelInstance;
use App\Services\AIs\RequestHelper;
use Illuminate\Database\Eloquent\Model;

class UserAiModel extends Model
{
    const STATUS_TRAINING = 'training';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_TRAINED = 'trained';
    const STATUS_CREATED = 'created';

    protected $table = 'user_models';
    protected $fillable = [
        'cover_image_url',
        'description',
        'user_fine_tune',
        'type',
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
        return $this->hasOne(UserAiModelVersion::class, 'model_id', 'id')->latest();
    }

    public function getParent() : ModelInstance | null
    {
        $parentDataArray = explode('/', $this->trained_on);
        return RequestHelper::getPredictModel($parentDataArray[1], $parentDataArray[0]);
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'target_id', 'id')->where('target_type', 'user_model');
    }
}
