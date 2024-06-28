<?php

namespace App\Services\AIs\Instances;

class DataAiRequest
{
    public $model;
    public $input;
    public $stream;
    public $model_owner;
    public $webhook;
    public $webhook_events_filter;
    public $version_id;
    public $prompt;

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
