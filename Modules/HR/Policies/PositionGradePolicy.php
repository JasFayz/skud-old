<?php

namespace Modules\HR\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\HR\Entities\PositionGrade;
use Modules\User\Entities\User;

class PositionGradePolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        //
    }

    public function update(User $user, PositionGrade $positionGrade)
    {
        return $user->can('position-grade.update') && $user->company_id === $positionGrade->company_id;
    }

    public function destroy(User $user, PositionGrade $positionGrade)
    {
        return $user->can('position-grade.destroy') && $user->company_id === $positionGrade->company_id;
    }
}
