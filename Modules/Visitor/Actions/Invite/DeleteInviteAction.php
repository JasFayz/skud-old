<?php

namespace Modules\Visitor\Actions\Invite;

use Modules\Visitor\Entities\Invite;

class DeleteInviteAction
{
    public function handle(Invite $invite): ?bool
    {
        return $invite->delete();
    }
}
