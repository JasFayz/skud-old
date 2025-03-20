<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\RemoteClient\Actions\Server\SendUsersAction;
use Modules\RemoteClient\Entities\Server;
use Modules\RemoteClient\Transformers\UserServerResource;
use Modules\User\Entities\User;

class SendUserToRemoteServers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(private User $user)
    {

    }

    public function handle(SendUsersAction $sendUsersAction)
    {
        $servers = Server::query()
            ->where('company_id', $this->user->company_id)
            ->where('type', Server::TYPE_SECONDARY)->get();

        $response = [];

        if ($servers) {
            foreach ($servers as $server) {
                $response[$server->id] = $sendUsersAction->execute($server, $this->user->id);
            }
        }
        Log::channel('webhook')->info(json_encode($response));
    }
}
