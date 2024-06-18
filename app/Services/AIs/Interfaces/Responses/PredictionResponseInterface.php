<?php

namespace App\Services\AIs\Interfaces\Responses;

interface PredictionResponseInterface
{
    public static function handle($data) : PredictionResponseInterface;
}
