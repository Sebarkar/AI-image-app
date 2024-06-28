<?php

namespace App\Services\AIs\Providers\Replicate\Models\StabilityAISdxl;

use App\Services\AIs\Instances\ModelInstance;

class StabilityAiSdxlTrain extends ModelInstance
{
    /**
     * @var string Input mask for inpaint mode. Black areas will be preserved, white areas will be inpainted.
     */
    public $input_images;
    public $seed = '';
    public $resolution = 512;
    public $select_images = [
        'None' => [
            'src' => 'https://t3.ftcdn.net/jpg/02/15/15/46/360_F_215154625_hJg9QkfWH9Cu6LCTUc8TiuV6jQSI0C5X.jpg',
            'presets' => [
                'is_lora' => true,
                'resolution' => 512,
                'train_batch_size' => 4,
                'num_train_epochs' => 4000,
                'max_train_steps' => 1000,
                'unet_learning_rate' => '1e-6',
                'ti_lr' => '3e-4',
                'lora_lr' => '1e-4',
                'lr_scheduler' => 'constant',
                'lr_warmup_steps' => 100,
                'crop_based_on_salience' => true,
                'use_face_detection_instead' => true,
                'clipseg_temperature' => 1.0,
                'checkpointing_steps' => 200,
            ],
        ],
        'Face' => [
            'src' => 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?cs=srgb&dl=pexels-pixabay-415829.jpg&fm=jpg',
            'presets' => [
                'is_lora' => true,
                'resolution' => 512,
                'train_batch_size' => 4,
                'num_train_epochs' => 4000,
                'max_train_steps' => 1000,
                'unet_learning_rate' => '1e-6',
                'ti_lr' => '3e-4',
                'lora_lr' => '1e-4',
                'lr_scheduler' => 'constant',
                'lr_warmup_steps' => 100,
                'crop_based_on_salience' => true,
                'use_face_detection_instead' => true,
                'clipseg_temperature' => 1.0,
                'checkpointing_steps' => 200,
            ],
        ],
        'Full Body' => [
            'src' => 'https://target.scene7.com/is/image/Target/GUEST_b0e54839-ed6c-447d-a37f-e67caed5cd39?wid=488&hei=488&fmt=pjpeg',
            'presets' => [
                'is_lora' => true,
                'resolution' => 512,
                'train_batch_size' => 4,
                'num_train_epochs' => 2000,
                'max_train_steps' => 1000,
                'unet_learning_rate' => '1e-6',
                'ti_lr' => '3e-4',
                'lora_lr' => '1e-4',
                'lr_scheduler' => 'constant',
                'lr_warmup_steps' => 100,
                'crop_based_on_salience' => true,
                'use_face_detection_instead' => false,
                'clipseg_temperature' => 1.0,
                'checkpointing_steps' => 200,
            ],
        ],
        'Item' => [
            'src' => 'https://cdnimg.webstaurantstore.com/images/products/large/197994/2401540.jpg',
            'presets' => [
                'is_lora' => false,
                'resolution' => 512,
                'train_batch_size' => 4,
                'num_train_epochs' => 4000,
                'max_train_steps' => 1000,
                'unet_learning_rate' => '1e-6',
                'ti_lr' => '3e-4',
                'lora_lr' => '1e-4',
                'lr_scheduler' => 'constant',
                'lr_warmup_steps' => 100,
                'crop_based_on_salience' => true,
                'use_face_detection_instead' => false,
                'clipseg_temperature' => 1.0,
                'checkpointing_steps' => 200,
            ],
        ],
    ];
    public $train_batch_size = 4;
    public $num_train_epochs = 4000;
    public $max_train_steps = 1000;
    public $is_lora = true;
    public $unet_learning_rate = '1e-6';
    public $ti_lr = '3e-4';
    public $lora_lr = '1e-4';
    public $lr_scheduler = 'constant';
    public $lr_warmup_steps = 100;
    public $token_string = 'TOK';
    public $caption_prefix = 'A photo of TOK';
    public $mask_target_prompts = '';
    public $crop_based_on_salience = true;
    public $use_face_detection_instead = false;
    public $clipseg_temperature = 1.0;
    public $verbose = true;
    public $checkpointing_steps = 200;

    public static function getDataProperties()
    {
        $model = new self();
        return [
            'presets' => [
                'visibility' => true,
                'value' => array_key_first($model->select_images),
                'options' => $model->select_images,
                'type' => 'setting_presets',
                'x-order' => 0,
                'description' => 'Select preset. Presets are pre-settings for optimal training. If you select a preset, all other settings will be overwritten.',
            ],
            'input_images' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->input_images,
                'type' => 'files',
                'allowed_types' => ['jpg', 'jpeg', 'png'],
                'max' => 100,
                'description' => 'List of images to train on. Images will be resized to resolution x resolution pixels.',
            ],
            'token_string' => [
                'required' => true,
                'visibility' => true,
                'value' => $model->token_string,
                'type' => 'string',
                'description' => 'A unique string that will be trained to refer to the concept in the input images. Can be anything, but TOK works well. Defaults to TOK.',
            ],
            'caption_prefix' => [
                'required' => true,
                'visibility' => true,
                'value' => $model->caption_prefix,
                'type' => 'string',
                'description' => 'Text which will be used as prefix during automatic captioning. Must contain the token_string. For example, if caption text is ‘a photo of TOK’, automatic captioning will expand to ‘a photo of TOK under a bridge’, ‘a photo of TOK holding a cup’, etc.”, Defaults to a photo of TOK.',
            ],
            'is_lora' => [
                'visibility' => true,
                'value' => $model->is_lora,
                'type' => 'boolean',
                'description' => 'Boolean indicating whether to use LoRA training. If set to False, will use Full fine tuning. Defaults to True.',
            ],
            'seed' => [
                'visibility' => true,
                'required' => false,
                'value' => $model->seed,
                'type' => 'number',
                'description' => 'Random seed integer for reproducible training. Leave empty to use a random seed.',
            ],
            'resolution' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->resolution,
                'type' => 'number',
                'min' => 512,
                'max' => 1028,
                'step' => 1,
                'description' => 'Square pixel resolution which your images will be resized to for training. Defaults to 512.',
            ],
            'train_batch_size' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->train_batch_size,
                'type' => 'number',
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'description' => 'Batch size (per device) for training. Defaults to 4',
            ],
            'num_train_epochs' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->num_train_epochs,
                'type' => 'number',
                'min' => 1000,
                'max' => 6000,
                'step' => 500,
                'description' => 'Number of epochs to loop through your training dataset. Defaults to 4000.',
            ],
            'max_train_steps' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->max_train_steps,
                'type' => 'number',
                'min' => 500,
                'max' => 3000,
                'step' => 500,
                'description' => 'Number of individual training steps. Takes precedence over num_train_epochs. Defaults to 1000.',
            ],
            'unet_learning_rate' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->unet_learning_rate,
                'type' => 'select',
                'options' => [
                    '1e-1', '1e-2', '1e-3', '1e-4', '1e-5', '1e-6'
                ],
                'description' => 'Learning rate for the U-Net as a float. We recommend this value to be somewhere between 1e-6: to 1e-5. Defaults to 1e-6.',
            ],
            'ti_lr' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->ti_lr,
                'type' => 'select',
                'options' => [
                    '1e-4', '2e-4', '3e-4', '4e-4'
                ],
                'description' => 'Scaling of learning rate for training textual inversion embeddings. Don’t alter unless you know what you’re doing. Defaults to 3e-4.',
            ],
            'lora_lr' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->lora_lr,
                'type' => 'select',
                'options' => [
                    '1e-4', '2e-4', '3e-4', '4e-4'
                ],
                'description' => 'Scaling of learning rate for training LoRA embeddings. Don’t alter unless you know what you’re doing. Defaults to 1e-4.',
            ],
            'lr_scheduler' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->lr_scheduler,
                'type' => 'select',
                'options' => [
                    'constant', 'linear'
                ],
                'description' => 'Learning rate scheduler to use for training. Allowable values are constant or linear. Defaults to constant.',
            ],
            'lr_warmup_steps' => [
                'visibility' => true,
                'required' => true,
                'value' => $model->lr_warmup_steps,
                'type' => 'number',
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'description' => 'Number of warmup steps for lr schedulers with warmups. Defaults to 100.',
            ],
            'mask_target_prompts' => [
                'visibility' => true,
                'value' => $model->mask_target_prompts,
                'type' => 'string',
                'description' => 'Prompt that describes part of the image that you will find important. For example, if you are fine-tuning your pet, photo of a dog will be a good prompt. Prompt-based masking is used to focus the fine-tuning process on the important/salient parts of the image. Defaults to Empty.',
            ],
            'crop_based_on_salience' => [
                'visibility' => true,
                'value' => $model->crop_based_on_salience,
                'type' => 'boolean',
                'description' => 'If you want to crop the image to target_size: based on the important parts of the image, set this to True. If you want to crop the image based on face detection, set this to False. Defaults to True.',
            ],
            'use_face_detection_instead' => [
                'visibility' => true,
                'value' => $model->use_face_detection_instead,
                'type' => 'boolean',
                'description' => 'If you want to use face detection instead of CLIPSeg for masking. For face training, we recommend using this option. Defaults to False.',
            ],
            'clipseg_temperature' => [
                'visibility' => true,
                'value' => $model->clipseg_temperature,
                'type' => 'number',
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
                'description' => 'How blurry you want the CLIPSeg mask to be. We recommend this value be something between 0.5: to 1.0. If you want to have more sharp mask (but thus more errorful), you can decrease this value. Defaults to 1.0.',
            ],
            'verbose' => [
                'visibility' => true,
                'value' => $model->verbose,
                'type' => 'boolean',
                'description' => 'Verbose output. Defaults to True.',
            ],
            'checkpointing_steps' => [
                'visibility' => true,
                'value' => $model->checkpointing_steps,
                'type' => 'number',
                'min' => 100,
                'max' => 9999,
                'step' => 1,
                'description' => 'Number of steps between saving checkpoints. Set to very very high number to disable checkpointing, because you don’t need one. Defaults to 200.',
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
