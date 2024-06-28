<?php

namespace App\Services\AIs\Instances\Request;

use App\Services\AIs\Instances\ModelInstance;

class UserVersionRequest
{
    public $model_name;
    public $model_owner;
    public $version_id;

    public static function parse(array $data) {
        $instance = new self();
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }
}
