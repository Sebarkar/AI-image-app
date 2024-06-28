<?php

namespace App\Services\Forms;

use App\Models\Files;
use App\Rules\FileOrString;
use App\Services\Files\FileStorage;

trait ValidatorConstructor
{
    protected $excludeTypes = ['setting_presets', 'separator'];

    public static function getValidators(): array
    {
        $form = static::getForm();
        return self::makeValidatorsFromForm($form);
    }

    public static function makeValidatorsFromForm($form)
    {
        $validators = [];
        $validator = new self();
        foreach ($form as $key => $value) {
            if (in_array($value['type'], $validator->excludeTypes)) {
                continue;
            }
            $validatorArray = [];
            if (array_key_exists('required', $value) && $value['required']) {
                $validatorArray[] = 'required';
            } else {
                $validatorArray[] = 'nullable';
            }
            if ($value['type'] === 'string' || $value['type'] === 'select') {
                $validatorArray[] = 'string';
                if (!array_key_exists('required', $value) || !$value['required']) {
                    $validatorArray[] = 'nullable';
                }
            }
            if ($value['type'] === 'number' || $value['type'] === 'integer') {
                $validatorArray[] = 'numeric';
            }
            if ($value['type'] === 'file' || $value['type'] === 'files') {
                if (array_key_exists('allowed_types', $value)) {
                    $validatorArray[] = new FileOrString($value['allowed_types']);
                } else {
                    $validatorArray[] = new FileOrString();
                }
                if (!array_key_exists('required', $value) || !$value['required']) {
                    $validatorArray[] = 'nullable';
                }
            }
            if (array_key_exists('custom_validators', $value)) {
                foreach ($value['custom_validators'] as $customValidator) {
                    $validatorArray[] = $customValidator;
                }
            }
            if ($value['type'] === 'files') {
                if (array_key_exists('allowed_types', $value)) {
                    $validatorArray[] = 'extensions:' . implode(',', $value['allowed_types']);
                }
                $validatorArray[] = 'max:22200';

                //Pass required for all files group
                if (array_key_exists('required', $value) && $value['required']) {
                    $validators[$key] = 'required|array|min:1';
                }

                $key = $key . '.*';
            }
            $validators[$key] = $validatorArray;
        }
        return $validators;
    }

    public static function getTypeBasedInput($formWithTypes, $input, $removeEmptyValues = true)
    {
        $inputArray = [];
        foreach ($input as $key => $value) {
            if ($removeEmptyValues && ($value === null || $value === '' || $value === 'null')) {
                continue;
            }
            if (array_key_exists($key, $formWithTypes)) {
                $inputArray[$key] = self::changeValueByType($value, $formWithTypes[$key]['type']);
            }
        }
        return $inputArray;
    }

    private static function changeValueByType($value, $type)
    {
        switch ($type) {
            case 'number':
                return (float) $value;
            case 'integer':
                return (int) $value;
            case 'boolean':
                if (is_string($value)) {
                    return $value === 'true' || $value === '1' || $value === 'on';
                }
                return !!$value;
            default:
                return $value;
        }
    }

    /**
     * Usually input from front contains file, in case form should contain links - need save and set link to values
     * @param $formWithTypes
     * @param $input
     * @param $target
     * @param $targetType
     * @return array
     */
    public static function getFilesLinksFromInput($formWithTypes, $input, $target, $targetType) : array
    {
        foreach ($input as $key => $value) {
            if ($formWithTypes[$key]['type'] === 'file' && is_file($value)) {
                //Save file and set link to input if not exist
                $input[$key] = FileStorage::storeImages([$value], $target, $targetType)[0]->getSrc();
            }
        }
        return $input;
    }
}
