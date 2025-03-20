<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\RemoteClient\Actions\Server\SendTerminalsAction;
use Modules\RemoteClient\Entities\Server;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;

class SendTerminalToRemoteServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(private Terminal $terminal)
    {
        //
    }

    public function handle(SendTerminalsAction $sendTerminalsAction)
    {

        $servers = Server::query()
            ->where('type', 1)
            ->where('company_id', $this->terminal->company_id)->get();


        if ($servers) {
            foreach ($servers as $server) {
                $sendTerminalsAction->execute($server, $this->terminal->id)->json();
            }
        }
    }

}
