<?php

namespace App\Services\AIs\Instances;

use App\Models\AiModels;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ModelInstanceFactory
 * @package App\Services\AIs\Instances
 * Created models instances for AIClient
 */
class ModelInstanceFactory
{
    /**
     * @param AiModels $data
     * @return ModelInstance
     */
    public static function build(Collection $data, $withForm = true): array
    {
        $result = [];
        $data->each(function ($item) use (&$result, $withForm) {
            $result[] = ModelInstanceBuilder::build($item, $withForm);
        });
        return $result;
    }

}
