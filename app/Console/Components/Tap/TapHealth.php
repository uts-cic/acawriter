<?php

namespace App\Console\Components\Tap;

use App\Events\Tap\Health;
use Illuminate\Console\Command;
use App\Services\Tap;
use Illuminate\Support\Facades\Log;

class TapHealth extends Command
{

    protected $signature = 'dashboard:tap-health';

    protected $description = 'Check if tap is operating normally';

    protected $service = 'health';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tap = new Tap();
        $tapHealth = $tap->getRequest($this->service);
        Log::info('health', ['dataTap' => $tapHealth]);
        event(new Health($tapHealth));
    }
}
