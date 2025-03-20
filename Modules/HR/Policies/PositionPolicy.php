<?php

namespace Modules\HR\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\HR\Entities\Position;
use Modules\User\Entities\User;

class PositionPolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        //
    }

    public function update(User $user, Position $position)
    {
        return $user->can('position.update') && $user->company_id === $position->company_id;
    }

    public function destroy(User $user, Position $position)
    {
        return $user->can('position.destroy') && $user->company_id === $position->company_id;
    }
}
