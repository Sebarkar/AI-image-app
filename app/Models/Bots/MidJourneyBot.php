<?php

namespace App\Models\Bots;

use App\Services\AIs\AIClient;
use App\Services\AIs\Providers\MidJourney\MidJourney;

class MidJourneyBot
{
    public function __invoke (Bot $bot): AIClient
    {
        $connector = new MidJourney();

        $data = config('bots.guest')[0];

        $connector->setChanelId($data['chanel_id']);
        $connector->setUserToken($data['user_token']);

        return new AIClient($connector);
    }
}
