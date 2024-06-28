<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModelVersion extends Model
{

    /**
     * Version has different target types for correct relations with parent model tables
     * -- use relation: AiModel, UserAiModel
     */
    const TARGET_REGULAR = 'regular';
    const TARGET_USER = 'user';

    protected $table = 'model_versions';
    public $timestamps = false;

    protected $casts = [
        'schemas' => 'array',
    ];
    protected $fillable = [
        'cog_version',
        'created_at',
        'model_id',
        'schema',
        'target',
        'schema_version',
        'user_id',
        'version_id',
        'schemas',
    ];


}
