<?php

namespace App\Services\Image;

use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
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
                'title' =>  $i + 1 . 'quadrant',
                'extension' => 'jpg',
                'image' => $newImage->encode(new JpegEncoder(90)),
            ];
        }

        return $array;
    }
}
