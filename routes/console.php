<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('model:prune', ['--model' => [\App\Models\OneTimePassword::class],])->everyMinute();
Schedule::call(function () {
    \App\Services\AIs\AIClient::provider('replicate')->saveAvailableModels();
})->daily()->at('00:00');
