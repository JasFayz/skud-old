<?php

namespace Modules\Visitor\Actions\Invite;

use Modules\Visitor\Entities\Invite;

class SyncZoneAction
{
    public function handle(Invite $invite, array $zones)
    {
        return $invite->sync($zones);
    }
}
