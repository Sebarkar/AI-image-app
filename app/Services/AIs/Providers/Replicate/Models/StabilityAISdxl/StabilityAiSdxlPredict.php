<?php

namespace App\Services\AIs\Providers\Replicate\Models\StabilityAISdxl;

use App\Services\AIs\Instances\ModelInstance;

class StabilityAiSdxlPredict extends ModelInstance
{
    /**
     * @var string Input mask for inpaint mode. Black areas will be preserved, white areas will be inpainted.
     */
    public $mask = '';
    public $seed;
    public $image = '';
    public $width = 1024;
    public $height = 1024;
    public $prompt = 'Photo of man on boat';
    public $refine = 'expert_ensemble_refiner';
    public $scheduler = 'DDIM';
    public $lora_scale = 1;
    public $num_outputs = 1;
    public $refine_steps = 20;
    private array $fieldsToModelWhenTrain = ['token_string'];
    public $guidance_scale = 7.5;
    public $apply_watermark = false;
    public $high_noise_frac = 0.8;
    public $negative_prompt = '(worst quality, low quality, normal quality, lowres, low details, oversaturated, undersaturated, overexposed, underexposed, grayscale, bw, bad photo, bad photography, bad art:1.4), (watermark, signature, text font, username, error, logo, words, letters, digits, autograph, trademark, name:1.2), (blur, blurry, grainy), morbid, ugly, asymmetrical, mutated malformed, mutilated, poorly lit, bad shadow, draft, cropped, out of frame, cut off, censored, jpeg artifacts, out of focus, glitch, duplicate, (airbrushed, cartoon, anime, semi-realistic, cgi, render, blender, digital art, manga, amateur:1.3), (3D ,3D Game, 3D Game Scene, 3D Character:1.1), (bad hands, bad anatomy, bad body, bad face, bad teeth, bad arms, bad legs, deformities:1.3)';
    public $prompt_strength = 0.8;
    public $num_inference_steps = 50;
    public $disable_safety_checker = true;

    public static function getDataProperties($withForm = true)
    {
        $instancePredict = new self();
        $model = new ModelInstance();
        $model->user_fine_tune = true;
        $model->title = 'stability-ai/sdxl';
        $model->public = true;
        $model->version_id = '7762fd07cf82c948538e41f63f77d685e02b063e37e496e96eefd46c929f9bdc';
        $model->type = 'image';
        $model->name = 'sdxl';
        $model->owner = 'stability-ai';
        $model->image = 'images/ais/sdxl-1.0.jpg';
        $model->setFieldsToModelWhenTrain($instancePredict->fieldsToModelWhenTrain);

        if ($withForm) {
            $model->train = StabilityAiSdxlTrain::getDataProperties();
            $model->predict = $instancePredict->getPredictForm();
        }

        return $model;
    }

    public function getPredictForm()
    {
        $model = $this;
        return [
            'prompt' => [
                'visibility' => true,
                'required' => true,
                'show_token' => true,
                'value' => $model->prompt,
                'type' => 'textarea',
                'description' => 'Input prompt.',
            ],
            'negative_prompt' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->negative_prompt,
                'type' => 'textarea',
                'description' => 'Input Negative Prompt',
            ],
            'image' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->image,
                'type' => 'file',
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'description' => 'Input image for img2img or inpaint mode',
            ],
            'mask' => [
                'visibility' => false,
                'required' => false,
                'value' => $model->mask,
                'type' => 'file',
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'description' => 'Input mask for inpaint mode.',
            ],

            'width' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->width,
                'type' => 'number',
                'min' => 512,
                'max' => 2056,
                'step' => 1,
                'description' => 'Width of the output image.',
            ],
            'height' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->height,
                'type' => 'number',
                'min' => 512,
                'max' => 2056,
                'step' => 1,
                'description' => 'Height of the output image.',
            ],
            'num_outputs' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->num_outputs,
                'type' => 'number',
                'min' => 1,
                'max' => 4,
                'step' => 1,
                'description' => 'Number of images to output.',
            ],
            'separator' => [
                'visibility' => true,
                'type' => 'separator',
                'description' => 'Advanced Options',
            ],
            'seed' => [
                'visibility' => false,
                'required' => false,
                'pro_field' => true,
                'value' => $model->seed,
                'type' => 'number',
                'description' => 'Seed for random number generator.',
            ],
            'refine' => [
                'visibility' => true,
                'required' => true,
                'pro_field' => true,
                'value' => $model->refine,
                'type' => 'select',
                'description' => 'Refine the output image.',
                'options' => [
                    'no_refiner', 'expert_ensemble_refiner', 'base_image_refiner'
                ],
            ],
            'scheduler' => [
                'visibility' => true,
                'value' => $model->scheduler,
                'type' => 'select',
                'pro_field' => true,
                'description' => 'Scheduler for text-to-image.',
                'options' => [
                    'DDIM', 'DPMSolverMultistep', 'HeunDiscrete', 'KarrasDPM', 'K_EULER_ANCESTRAL', 'K_EULER', 'PNDM'
                ],
            ],
            'lora_scale' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->lora_scale,
                'type' => 'number',
                'pro_field' => true,
                'min' => 0.1,
                'max' => 1,
                'step' => 0.1,
                'description' => 'LoRA additive scale. Only applicable on trained models.',
            ],
            'refine_steps' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->refine_steps,
                'pro_field' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'description' => 'For base_image_refiner, the number of steps to refine, defaults to num_inference_steps',
            ],
            'guidance_scale' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->guidance_scale,
                'type' => 'number',
                'pro_field' => true,
                'min' => 1,
                'max' => 50,
                'step' => 0.11,
                'description' => 'Scale for classifier-free guidance',
            ],
            'high_noise_frac' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->high_noise_frac,
                'type' => 'number',
                'pro_field' => true,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'description' => 'For expert_ensemble_refiner, the fraction of noise to use',
            ],
            'prompt_strength' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->prompt_strength,
                'type' => 'number',
                'pro_field' => true,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'description' => 'Prompt strength when using img2img/inpaint. 1.0 corresponds to full destruction of information in image',
            ],
            'num_inference_steps' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->num_inference_steps,
                'type' => 'number',
                'pro_field' => true,
                'min' => 1,
                'max' => 500,
                'step' => 1,
                'description' => 'Number of denoising steps',
            ],
            'disable_safety_checker' => [
                'visibility' => false,
                'required' => true,
                'pro_field' => true,
                'value' => $model->disable_safety_checker,
                'type' => 'boolean',
                'description' => 'Disable safety checker',
            ],
        ];
    }

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
