<?php

namespace App\Services\AIs\Instances\Request;

use App\Services\AIs\Instances\ModelInstance;

class TrainInstance
{
    public $model_name;
    public $model_owner;
    public $version_id;
    public $input;

    /**
     * @var string $webhook
     * Request a webhook to receive the prediction via an HTTP POST request.
     * If the requested model version supports webhooks, the returned prediction will have a webhook entry in its urls
     * property with an HTTPS URL that you can use to receive the prediction.
     */
    public string $webhook;

    /*
     * https://replicate.com/docs/reference/http#predictions.create--webhook_events_filter
     */
    public string $webhook_events_filter;

    public static function parse(array $data) {
        $instance = new self();
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }

    public function setPrompt(ModelInstance $data) {
        $this->input = $data;
    }
}
