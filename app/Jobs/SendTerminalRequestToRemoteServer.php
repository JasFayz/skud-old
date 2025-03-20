<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\RemoteClient\Actions\Server\SendTerminalRequestAction;
use Modules\RemoteClient\Entities\Server;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\User\Entities\User;

class SendTerminalRequestToRemoteServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private int $terminalRequestLogId)
    {
        //
    }

    public function handle(SendTerminalRequestAction $sendTerminalRequestAction)
    {
        $terminalRequestLog = TerminalRequestLog::query()->find($this->terminalRequestLogId);

        $user = $terminalRequestLog->identification->identifiable;

        $servers = Server::query()->where('type', Server::TYPE_MAIN)
            ->where('company_id', $user->company_id)
            ->get();

        $response = [];
        if ($user instanceof User) {
            foreach ($servers as $server) {
                $response[] = $sendTerminalRequestAction->execute($server, $this->terminalRequestLogId)->json();
            }
        }
        echo json_encode([
            'response' => $response,
            'terminal_request_log' => $this->terminalRequestLogId
        ]);
    }
}
