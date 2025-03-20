<?php

namespace Modules\Visitor\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Entities\User;
use Modules\Visitor\Entities\Invite;

class InvitePolicy
{

    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function destroy(User $user, Invite $invite)
    {
        return !($user->getRoleAttribute()->grade >= 6) || $user->id === $invite->created_by;
    }
}
