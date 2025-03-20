<?php

namespace Modules\User\Actions\User;

use Modules\User\Entities\User;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        $user->deleteOrFail();
    }
}
