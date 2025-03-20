<?php

namespace App\Observers;

use App\Jobs\SendUserToRemoteServers;
use Modules\RemoteClient\Entities\Server;
use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\User\Entities\User;

class UserObserver
{

    public function created(User $user)
    {
        $identifier = new TerminalUserIdentifier();
        $user->identifier()->save(new TerminalUserIdentifier());
        $user->update([
            'uuid' => $user->identifier->identifier_number
        ]);
        $user->profile()->create();

        $user = $user->fresh();

        $serverQuantity = Server::query()
            ->where('company_id', $user->company_id)
            ->where('type', Server::TYPE_SECONDARY)
            ->count();

        if ($user->isLocal() && $serverQuantity > 0) {
            dispatch(new SendUserToRemoteServers($user))->onQueue('webhook');
        }
    }
}
