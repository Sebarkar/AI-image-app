<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class FileOrString implements ValidationRule
{
    public $mimes;
    public function __construct($mimes = null)
    {
        $this->mimes = $mimes;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is an instance of UploadedFile (a file)
        if (!($value instanceof UploadedFile) && !is_string($value)) {
            $fail('The :attribute must be a file or a string.')->translate();
        }

        if ($value instanceof UploadedFile) {
            // Check if the file is an image
            if ($this->mimes) {
                $validator = Validator::make(
                    [$attribute => $value],
                    [$attribute => 'file|mimes:' . implode(',', $this->mimes)]);
                if ($validator->fails()) {
                    $fail($validator->errors()->all()[0]);
                }
            }
        }
    }
}
