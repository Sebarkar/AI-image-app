<?php

namespace App\Services\AIs\Instances\Schemas;

use App\Services\AIs\Interfaces\Schemas\SchemaInterface;

class OpenApiSchemas implements SchemaInterface
{
    public $data;
    public $validationError;
    public $required;

    protected $titlesForImage = ['image_url', 'image', 'img'];
    protected $titlesForMultipleImages = ['input_images'];
    protected $titlesForMask = ['mask'];
    protected $titlesForTextarea = ['prompt', 'negative_prompt', 'a_prompt', 'n_prompt'];
    public $result;

    public function convertDataToObject(): object
    {
        $json = json_encode($this->data);
        $this->data = json_decode($json);
        $this->validationError = $this->data->schemas->ValidationError;
        return $this->data;
    }

    public function buildPredictForm(): array
    {
        $this->convertDataToObject();
        $props = $this->data->schemas->Input->properties;
        $xOrderVariable = 'x-order';
        $form = [];
        foreach ($props as $title => $prop) {
            $form[$title] = [
                'required' => $this->checkIfRequired($title),
                'visibility' => true,
                'title' => $prop->title ?? $title,
                'type' => $this->getType($prop, $title),
                'value' => $prop->default ?? null,
                'description' => $prop->description ?? null,
                'x-order' => $prop->$xOrderVariable,
                ...$this->getTypeBasedFields($prop, $title, $prop->type ?? 'select')
            ];
        }
        uasort($form, function ($a, $b) {
            return $a['x-order'] <=> $b['x-order'];
        });
        return $form;
    }

    public function buildTrainForm(): array
    {
        $this->convertDataToObject();

        if (!isset($this->data->schemas->TrainingInput)) {
            return [];
        }

        $props = $this->data->schemas->TrainingInput->properties;
        $this->required = $this->data->schemas->TrainingInput->required;
        $xOrderVariable = 'x-order';
        $form = [];
        foreach ($props as $title => $prop) {
            $form[$title] = [
                'required' => $this->checkIfRequired($title),
                'visibility' => true,
                'title' => $prop->title ?? $title,
                'type' => $this->getType($prop, $title),
                'value' => $prop->default ?? null,
                'description' => $prop->description ?? null,
                'x-order' => $prop->$xOrderVariable,
                ...$this->getTypeBasedFields($prop, $title, $prop->type ?? 'select')
            ];
        }
        uasort($form, function ($a, $b) {
            return $a['x-order'] <=> $b['x-order'];
        });
        return $form;
    }

    private function getType(object $prop, string $title): string
    {
        if (!isset($prop->type)) {
            return 'select';
        }
        switch ($prop->type) {
            case 'number':
                return 'number';
            case 'integer':
                return 'integer';
            case 'string':
                return $this->getTypeString($title);
            case 'boolean':
                return 'boolean';
            case 'array':
                return 'select';
            default:
                return 'string';
        }
    }

    private function getTypeString($title): string
    {
        if (in_array($title, $this->titlesForImage) || in_array($title, $this->titlesForMask)) {
            return 'file';
        } elseif (in_array($title, $this->titlesForTextarea)) {
            return 'textarea';
        } elseif (in_array($title, $this->titlesForMultipleImages)) {
            return 'files';
        }
        return 'string';
    }

    public function checkIfRequired(string $title): bool
    {
        return $this->required && in_array($title, $this->required);
    }

    public function getTypeBasedFields(object $input, string $title, $type): array
    {
        switch ($type) {
            case 'number':
                return [
                    'min' => $input->minimum ?? 0,
                    'max' => $input->maximum ?? null,
                    'step' => isset($input->maximum) ? ($input->maximum > 4 ? 1 : 0.1) : null,
                ];
            case 'integer':
                return [
                    'min' => $input->minimum ?? 0,
                    'max' => $input->maximum ?? null,
                    'step' => 1,
                ];
            case 'select':
                return [
                    'options' => $this->data->schemas->$title->enum
                ];
            default:
                return [];
        }
    }

}
