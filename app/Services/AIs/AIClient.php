<?php

namespace App\Services\AIs;

use App\Services\AIs\Interfaces\SourceInterface;

class AIClient
{
    protected $provider;

    public function __construct(SourceInterface $source)
    {
        $this->provider = $source->init();
    }

    public function setPrompt($text, $tags, $details = [])
    {
        return $this->provider->setPrompt($text, $tags, $details);
    }

    public function getVariants()
    {
        return $this->provider->getVariants();
    }
}
