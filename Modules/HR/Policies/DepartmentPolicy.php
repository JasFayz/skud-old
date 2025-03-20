<?php

namespace Modules\HR\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\HR\Entities\Department;
use Modules\User\Entities\User;

class DepartmentPolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        //
    }

    public function update(User $user, Department $department)
    {
        return $user->can('department.update') && $department->company_id === $user->company_id;
    }

    public function destroy(User $user, Department $department)
    {
        return $user->can('department.destroy') && $department->company_id === $user->company_id;
    }
}
