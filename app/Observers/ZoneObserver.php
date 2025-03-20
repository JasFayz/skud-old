<?php

namespace App\Observers;

use App\Jobs\SendTerminalToRemoteServer;
use Modules\Skud\Entities\Zone;

class ZoneObserver
{

    public function created(Zone $zone)
    {
//        dispatch(new SendTerminalToRemoteServer($zone));
    }

    public function updated(Zone $zone)
    {
//        dispatch(new SendTerminalToRemoteServer($zone));
    }
}
