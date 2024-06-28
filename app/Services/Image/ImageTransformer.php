<?php

namespace App\Services\Image;

use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTransformer
{
    public static function cutQuadrant($image)
    {
        for ($i = 0; $i < 4; $i++) {
            $newImage = Image::read($image);
            $width = $newImage->width();
            $height = $newImage->height();

            $quadrantWidth = $width / 2;
            $quadrantHeight = $height / 2;
            $newImage = $newImage->crop($quadrantWidth, $quadrantHeight, $quadrantWidth * ($i % 2), $quadrantHeight * floor($i / 2));
            $array[] = [
                'title' => $i + 1 . 'quadrant',
                'extension' => 'jpg',
                'image' => $newImage->encode(new JpegEncoder(90)),
            ];
        }

        return $array;
    }

    public static function resize(UploadedFile $image, $height, $width)
    {
        $newImage = Image::read($image);

        $newImage = $newImage->resize($width, $height);

        $extension = $image->getExtension() ? $image->getExtension() : 'jpg';

        return self::formatImage($newImage, $extension);
    }

    public static function formatImage($image, $extension)
    {
        if ($extension === 'png') {
            return $image->encode(new PngEncoder(9));
        } elseif ($extension === 'gif') {
            return $image->encode(new GifEncoder());
        } elseif ($extension === 'bmp') {
            return $image->encode(new BmpEncoder());
        } elseif ($extension === 'webp') {
            return $image->encode(new WebpEncoder());
        } elseif ($extension === 'tiff') {
            return $image->encode(new TiffEncoder());
        } elseif ($extension === 'jpeg') {
            return $image->encode(new JpegEncoder(90));
        }

        return $image->encode(new JpegEncoder(90));
    }
}
