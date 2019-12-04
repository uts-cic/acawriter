<?php

/**
 * Created by IntelliJ IDEA.
 * User: Developer CIC: Radhika Mogarkar
 * Date: 2/11/17
 * Time: 11:09 AM
 */

namespace App\Events\Tap;

use App\Events\DashboardEvent;
use Illuminate\Support\Facades\Log;

class Health extends DashboardEvent
{
    public $health;

    public function __construct($status)
    {
        Log::info('into Event health', ['eventH' => $status]);
        $this->health = $status;
    }
}
