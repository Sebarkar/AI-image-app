<?php

namespace App\Services\Forms\Model;

use App\Services\Forms\FormInstance;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class CreateModelForm extends FormInstance
{

    public $title = '';
    public $description = '';
    public $image;

    public static function getForm(): array
    {
        $model = new self();
        return [
            'name' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->title,
                'type' => 'string',
                'x-order' => 0,
                'description' => 'Enter Model title.',
                'custom_validators' => [
                    Rule::unique('user_models')->where(fn (Builder $query) => $query->whereNotIn('user_id', auth()->user()->id)),
                ],
            ],
            'description' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->description,
                'type' => 'string',
                'x-order' => 1,
                'description' => 'Enter Model description.',
            ],
            'image' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->image,
                'type' => 'file',
                'x-order' => 2,
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'description' => 'Add hover image for Model. If not provided, default or first generated image will be used.',
            ],
        ];
    }
}
