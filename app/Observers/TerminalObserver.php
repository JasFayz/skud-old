<?php

namespace App\Observers;

use App\Jobs\SendTerminalToRemoteServer;
use Modules\Skud\Entities\Terminal;

class TerminalObserver
{
    public function created(Terminal $terminal)
    {
        dispatch(new SendTerminalToRemoteServer($terminal));

    }

    public function updated(Terminal $terminal)
    {
        dispatch(new SendTerminalToRemoteServer($terminal));

    }

}
