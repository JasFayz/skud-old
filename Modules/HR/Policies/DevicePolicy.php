<?php

namespace Modules\HR\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\HR\Entities\Device;
use Modules\User\Entities\User;

class DevicePolicy
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

    public function update(User $user, Device $device)
    {
        return $user->can('device.update') && $device->company_id === $user->company_id;
    }

    public function destroy(User $user, Device $device)
    {
        return $user->can('device.destroy') && $device->company_id === $user->company_id;
    }
}
