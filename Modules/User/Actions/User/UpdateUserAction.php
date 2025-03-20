<?php

namespace Modules\User\Actions\User;

use Modules\User\DTO\UserDTO;
use Modules\User\Entities\User;

class UpdateUserAction
{
    public function execute(User $user, UserDTO $data): User
    {
        $user->update($data->toArray());

        return $user->fresh();
    }
}
