<?php

namespace App\Console\Commands;

use App\Services\AIs\AIClient;
use Illuminate\Console\Command;

class UpdateModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AIClient::provider('replicate')->saveAvailableModels();
    }
}
