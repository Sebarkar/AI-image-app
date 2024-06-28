<?php

namespace App\Models;

use App\Interfaces\FormInterface;
use App\Services\Files\FileStorage;
use App\Services\Image\ImageProcessor;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class AiModels extends Model
{
    use HasStatus;

    protected $table = 'models';
    protected $fillable = [
        'cover_image_url',
        'description',
        'github_url',
        'license_url',
        'user_fine_tune',
        'user_id',
        'provider',
        'name',
        'owner',
        'paper_url',
        'run_count',
        'url',
        'visibility',
    ];

    public function version()
    {
        return $this->hasOne(RegularAiModelVersion::class, 'model_id', 'id')
            ->where('target', AiModelVersion::TARGET_REGULAR)
            ->latest();
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'target_id', 'id')->where('target', 'models');
    }

    public function removeCoverImage()
    {
        return $this->image?->remove();
    }

    public function saveCoverImageLocal($url)
    {
        $file = FileStorage::downloadFromLink($url);


        if ($file->extension() === 'gif') {
            return $this::update([
                'cover_image_url' => $url
            ]);
        }
        $file = ImageProcessor::init('models_cover_images')
            ->setImage($file)
            ->resize(200, 200)
            ->getFile();

        return $this::update([
            'cover_image_url' => FileStorage::storeFile($file, $this, 'models', 'models_cover_images')->getSrc()
        ]);
    }
}
