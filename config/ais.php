<?php

return [
    'providers' => [
        'replicate' => [
            'model' => \App\Services\AIs\Providers\Replicate\Replicate::class,
            'available_models' => [
                'stability-ai/sdxl' => [
                    'predict' => \App\Services\AIs\Providers\Replicate\Models\StabilityAISdxl\StabilityAiSdxlPredict::class,
                    'train' => \App\Services\AIs\Providers\Replicate\Models\StabilityAISdxl\StabilityAiSdxlTrain::class,
                ],
                'tencentarc/photomaker' => [
                    'predict' => \App\Services\AIs\Providers\Replicate\Models\TencentarcPhotomaker\TencentarcPhotomakerPredict::class,
                ],
            ],
        ]
    ]
];
