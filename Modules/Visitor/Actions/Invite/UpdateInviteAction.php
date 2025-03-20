<?php

namespace Modules\Visitor\Actions\Invite;

use Modules\Visitor\DTOs\InviteDTO;
use Modules\Visitor\Entities\Invite;

class UpdateInviteAction
{
    public function handle(Invite $invite, InviteDTO $inviteDTO): bool
    {
        return $invite->update($inviteDTO->toArray());
    }
}
