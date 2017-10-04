<?php
/**
 * Created by IntelliJ IDEA.
 * User: Developer CIC: Radhika Mogarkar
 * Date: 3/10/17
 * Time: 2:28 PM
 */



namespace App\Console\Components\InternetConnection;

use Illuminate\Console\Command;
use App\Events\InternetConnection\Heartbeat;


class SendHeartbeat extends Command
{

    protected $signature = 'dashboard:send-heartbeat';

    protected $description = 'Send a heartbeat to the the Internet Connection box';

    public function handle() {
        event(new HeartBeat());
    }

}