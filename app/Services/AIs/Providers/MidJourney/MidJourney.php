<?php

namespace App\Services\AIs\Providers\MidJourney;

use App\Services\AIs\Interfaces\SourceInterface;
use eDiasoft\Midjourney\MidjourneyApiClient;

class MidJourney implements SourceInterface
{
    protected $provider;
    protected $text = '';
    protected $tags;
    private $chanel_id;
    private $user_token;

    public function init()
    {
        $this->provider = new MidjourneyApiClient(
            $this->chanel_id,
            $this->user_token,
        );
        return $this;
    }

    public function setChanelId($chanel_id): void
    {
        $this->chanel_id = $chanel_id;
    }

    public function setUserToken($user_token): void
    {
        $this->user_token = $user_token;
    }

    public function setPrompt($text, $tags, $options = [])
    {
        $this->text = $text;
        $this->tags = $tags;

        if (array_key_exists('img', $options)) {
            $this->text .= ', ' . $options['img'];
        }

        return $this;
    }

    public function sendRequest()
    {
        return $this->provider->imagine($this->text, $this->tags)->send();
    }

    public function getVariants()
    {
        $message = $this->sendRequest();
        if (array_key_exists('attachments', $message)) {
            if (array_key_exists(0, $message['attachments']) && is_array($message['attachments'])) {
                return $message['attachments'][0]['url'];
            }
        }
        return '';
    }

    public function isResultReady()
    {
        // TODO: Implement isResultReady() method.
    }

    public function getName()
    {
        return 'midjourney';
    }

    public function createPrediction($data)
    {
        // TODO: Implement makePrediction() method.
    }
}
