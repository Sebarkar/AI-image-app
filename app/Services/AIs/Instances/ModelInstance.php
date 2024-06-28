<?php

namespace App\Services\AIs\Instances;

class ModelInstance
{
    public $user_id;
    public $user_model_id;
    public $user_fine_tune;
    public $public;
    public $type;
    public $name;
    public $status;
    public $data;
    public $completed;
    public $processing;
    public $title;
    public $description;
    public $parent_model;
    public $predict_settings = [];
    public $version_id;
    public $license_url;
    public $owner;
    public $image;
    //For Train FrontEnd Form
    public $train = [];
    //For Predict FrontEnd Form
    public $predict = [];
    private array $fieldsToModelWhenTrain = [];

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

    public function setFieldsToModelWhenTrain(array $fields)
    {
        $this->fieldsToModelWhenTrain = $fields;
    }

    public function getFieldsToModelWhenTrain()
    {
        return $this->fieldsToModelWhenTrain;
    }

    public function shouldAddFieldToModelWhenTrain()
    {
        return count($this->fieldsToModelWhenTrain);
    }

    public function getFieldToModelWhenTrain(array $input)
    {
        $fields = [];
        foreach ($this->fieldsToModelWhenTrain as $field) {
            if (array_key_exists($field, $input)) {
                $fields[$field] = $input[$field];
            }
        }
        return $fields;
    }
}
