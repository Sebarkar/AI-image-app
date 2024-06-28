<?php

namespace App\Services\Image;

use App\Services\Files\FileStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ImageProcessor
{
    const CUT_QUADRANT = 'cut_quadrant';

    protected $image;
    protected $storage;
    protected $extension;
    protected $images = [];
    protected $providerRules = null;
    private $path = null;
    private $target = null;
    private $targetType = null;
    private $tmpPath;
    private $suffix = null;
    private $preparedImages = [];
    private $result;

    public function __construct($storage)
    {
        $this->storage = Storage::disk($storage);
    }

    public static function init($storage)
    {
        return new self($storage);
    }

    public function getLinks()
    {
        foreach ($this->preparedImages as $variant) {
            $fullPath = ($this->path ?? '') . $variant['title'] . ($this->suffix ?? '') . '.' . $variant['extension'];

            $this->storage->put($fullPath, $variant['image']);
            $this->images[] = $this->storage->url($fullPath);
        }

        return $this->images;
    }

    public function getFile()
    {
        return $this->result;
    }

    public function make($job)
    {
        if ($job === self::CUT_QUADRANT) {
            $this->preparedImages = $this->cut('quadrant');
        }
        return $this;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function cut($type)
    {
        if ($type === 'quadrant') {
            return ImageTransformer::cutQuadrant($this->image);
        }
        return [];
    }

    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    public function setImage($image)
    {
        if (is_string($image)) {
            $image = FileStorage::downloadFromLink($image);
        }
        $this->image = $image;
        $this->extension = $this->image->getExtension();
        return $this;
    }

    public function getFileForJob()
    {
        if ($this->result) {
            return $this->result;
        } else {
            return $this->image;
        }
    }

    public function resize($width, $height)
    {
        $this->result = ImageTransformer::resize($this->getFileForJob(), $height, $width);
        $this->saveFile();


        return $this;
    }

    public function saveFile()
    {
        $this->result = FileStorage::getTempFileFromContent($this->result, $this->extension);
    }
}
