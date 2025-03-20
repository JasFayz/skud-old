<?php

namespace Modules\Skud\Services;

use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalActionLog;

class TerminalActionLogService
{

    public function handle(?TerminalCreatable $user, string $type, Terminal $terminal, $response)
    {

        TerminalActionLog::create([
            'date' => now(),
            'terminal_id' => $terminal->id,
            'action_type' => $type,
            'identifier_number' => $user?->getIdentifier(),
            'status' => $response?->success,
            'response' => json_encode($response),
        ]);
    }

}
