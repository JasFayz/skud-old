<?php

namespace App\Observers;

use App\Jobs\SendTerminalRequestToRemoteServer;
use Modules\Skud\Entities\TerminalRequestLog;

class TerminalRequestLogObserver
{

    public function created(TerminalRequestLog $log)
    {
        $requestLog = TerminalRequestLog::query()->find($log->id);

        if ($requestLog->type === 'local') {
            dispatch(new SendTerminalRequestToRemoteServer($requestLog->id))
                ->onQueue('webhook');
        }
    }
}
