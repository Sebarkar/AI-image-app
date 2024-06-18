<?php

namespace App\Services\AIs\Interfaces\Responses;

interface TrainResponseInterface
{
    public static function handle($data) : TrainResponseInterface;
}
