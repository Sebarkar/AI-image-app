<?php

namespace App\Services\AIs\Instances;

class ModelInstance
{
    public $user_id;
    public $user_fine_tune;
    public $public;
    public $type;
    public $name;
    public $title;
    public $description;
    public $license_url;
    public $owner;
    public $image;
    //For Train FrontEnd Form
    public $train = [];
    //For Predict FrontEnd Form
    public $predict = [];

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            if ($value) {
                $array[$key] = $value;
            }
        }
        return $array;
    }
}
