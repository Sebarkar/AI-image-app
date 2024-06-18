<?php

namespace App\Services\Forms;

trait ValidatorConstructor
{
    public static function getValidators(): array
    {
        $form = self::getForm();
        $validators = [];
        foreach ($form as $key => $value) {
            $validatorArray = [];
            if ($value['required']) {
                $validatorArray[] = 'required';
            }
            if ($value['type'] === 'string' || $value['type'] === 'select') {
                $validatorArray[] = 'string';
                if (!$value['required']) {
                    $validatorArray[] = 'nullable';
                }
            }
            if ($value['type'] === 'number' || $value['type'] === 'integer') {
                $validatorArray[] = 'numeric';
            }
            if ($value['type'] === 'file') {
                $validatorArray[] = 'file';
                if (!$value['required']) {
                    $validatorArray[] = 'nullable';
                }
            }
            if (array_key_exists('allowed_types', $value)) {
                $validatorArray[] = 'extensions:' . implode(',', $value['allowed_types']);
            }
            if (array_key_exists('custom_validators', $value)) {
                foreach ($value['custom_validators'] as $customValidator) {
                    $validatorArray[] = $customValidator;
                }
            }
            $validators[$key] = implode('|', $validatorArray);
        }
        return $validators;
    }
}
