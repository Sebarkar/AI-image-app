<?php

namespace App\Services\AIs\Interfaces;

interface SourceInterface
{
    public function init();
    public function setPrompt($text, $tags, $options = []);
    public function sendRequest();

    public function getVariants();
    public function isResultReady();
}
