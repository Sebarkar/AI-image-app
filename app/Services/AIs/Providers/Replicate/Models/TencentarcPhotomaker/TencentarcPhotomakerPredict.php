<?php

namespace App\Services\AIs\Providers\Replicate\Models\TencentarcPhotomaker;

use App\Services\AIs\Instances\ModelInstance;
use App\Services\AIs\Providers\Replicate\Models\StabilityAISdxl\StabilityAiSdxlTrain;

class TencentarcPhotomakerPredict extends ModelInstance
{
    /**
     * @var string Input mask for inpaint mode. Black areas will be preserved, white areas will be inpainted.
     */
    public $input_image;
    public $input_image2;
    public $input_image3;
    public $input_image4;
    public $prompt = 'A photo of a scientist img receiving the Nobel Prize';
    public $style_name = 'Photographic (Default)';
    public $negative_prompt = 'nsfw, lowres, bad anatomy, bad hands, text, error, missing fingers, extra digit, fewer digits, cropped, worst quality, low quality, normal quality, jpeg artifacts, signature, watermark, username, blurry';
    public $num_steps = 50;
    public $style_strength_ratio = 20;
    public $num_outputs = 1;
    public $guidance_scale = 5;
    public $seed;


    public static function getDataProperties($withForm = true)
    {
        $instancePredict = new self();
        $model = new ModelInstance();
        $model->user_fine_tune = false;
        $model->public = true;
        $model->title = 'tencentarc/photomaker';
        $model->name = 'photomaker';
        $model->owner = 'tencentarc';
        $model->type = 'image';
        $model->image = 'images/ais/tencentarc-photomaker.jpg';

        if ($withForm) {
            $model->form = $instancePredict->getPredictForm();
        }

        return $model;
    }

    public function getPredictForm()
    {
        $model = $this;
        return [
            'input_image' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->input_image,
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'type' => 'file',
                'description' => 'The input image, for example a photo of your face.',
            ],
            'input_image2' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->input_image2,
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'type' => 'file',
                'description' => 'Additional input image (optional)',
            ],
            'input_image3' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->input_image3,
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'type' => 'file',
                'description' => 'Additional input image (optional)',
            ],
            'input_image4' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->input_image4,
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'type' => 'file',
                'description' => 'Additional input image (optional)',
            ],
            'prompt' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->prompt,
                'type' => 'textarea',
                'description' => "Prompt. Example: a photo of a man/woman img'. The phrase 'img' is the trigger word.",
            ],
            'style_name' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->style_name,
                'type' => 'select',
                'description' => 'Style template. The style template will add a style-specific prompt and negative prompt to the user\'s prompt.',
                'options' => [
                    '(No style)', 'Cinematic', 'Disney Charactor', 'Digital Art', 'Photographic (Default)', 'Fantasy art', 'Neonpunk',
                    'Enhance', 'Comic book', 'Lowpoly', 'Line art'
                ],
            ],
            'negative_prompt' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->negative_prompt,
                'type' => 'textarea',
                'description' => 'Negative Prompt. The negative prompt should NOT contain the trigger word.',
            ],
            'num_outputs' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->num_outputs,
                'type' => 'number',
                'min' => 1,
                'max' => 4,
                'step' => 1,
                'description' => 'Number of output images',
            ],
            'separator' => [
                'visibility' => true,
                'type' => 'separator',
                'description' => 'Advanced Options',
            ],
            'num_steps' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->num_steps,
                'pro_field' => true,
                'type' => 'number',
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'description' => 'Number of sample steps',
            ],
            'style_strength_ratio' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->style_strength_ratio,
                'pro_field' => true,
                'type' => 'number',
                'min' => 15,
                'max' => 50,
                'step' => 1,
                'description' => 'Style strength (%)',
            ],
            'guidance_scale' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->guidance_scale,
                'pro_field' => true,
                'type' => 'number',
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'description' => 'Guidance scale. A guidance scale of 1 corresponds to doing no classifier free guidance.',
            ],
        ];
    }

    public static function parse(array $data)
    {
        $instance = new self();
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }


}
