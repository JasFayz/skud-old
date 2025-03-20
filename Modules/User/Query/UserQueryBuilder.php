<?php

namespace Modules\User\Query;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Entities\User;

class UserQueryBuilder extends Builder
{
    public function forCompany(?int $company_id): UserQueryBuilder
    {
        return $this->when($company_id, function ($builder) use ($company_id) {
            return $builder->where('users.company_id', '=', $company_id);
        });
    }

    public function forRole(?int $role_id)
    {
        return $this->when($role_id, function ($builder) use ($role_id) {
            return $builder->whereHas('roles', function ($query) use ($role_id) {
                $query->where('id', $role_id);
            });
        });
    }

    public function whereFio(?string $fio)
    {
        return $this->when($fio, function ($query) use ($fio) {
            return $query->whereHas('profile', function ($q) use ($fio) {
                return $q->where('first_name', 'ilike', '%' . $fio . '%')
                    ->orWhere('last_name', 'ilike', '%' . $fio . '%')
                    ->orWhere('full_name', 'ilike', '%' . $fio . '%');
            });
        });
    }

    public function forUser(?Authenticatable $user)
    {
        return $this
            ->whereIsFired(false)
            ->whereHas('roles', function ($q) {
                return $q->whereNot('name', 'super_admin');
            })
            ->when($user?->company_id, function ($builder) use ($user) {
                return $builder->where('company_id', $user->company_id);
            });
    }

    public function wherePinfl(?string $value)
    {
        return $this->when($value, function (Builder $builder) use ($value) {
            $builder->where('pinfl', 'ilike', "%{$value}%");
        });
    }

}
