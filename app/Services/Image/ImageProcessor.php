<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;

class ImageProcessor
{
    const CUT_QUADRANT = 'cut_quadrant';

    protected $image;
    protected $storage;
    protected $images = [];
    protected $providerRules = null;
    private $path = null;
    private $suffix = null;
    private $preparedImages = [];
    private $result = [];

    public function __construct($storage)
    {
        $this->storage = Storage::disk($storage);
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

    private function downloadFromLink($url)
    {
        return file_get_contents($url);
    }

    public function setImage($image)
    {
        if (is_string($image)) {
            $image = $this->downloadFromLink($image);
        }
        $this->image = $image;
        return $this;
    }

    public function prepareImage()
    {
        return $this->image;
    }

    public function saveImage()
    {
        return $this->storage->put('image.jpg', $this->image);
    }
}
