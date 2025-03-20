<?php

namespace Modules\User\ViewModels;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Skud\Entities\Company;
use Modules\User\Entities\User;
use Modules\User\FilterData\UserFilterData;

class UserViewModel
{
    public function __construct(private Authenticatable $user, private UserFilterData $filter)
    {
    }

    protected function users()
    {

        return User::query()
            ->with(['company', 'roles', 'profile', 'zones', 'zones.terminals', 'terminals', 'terminals.zone', 'telegrams'])
            ->forUser($this->user)
            ->forRole($this->filter->role)
            ->forCompany($this->filter->company_id)
            ->whereFio($this->filter->fio)
            ->hasPhoto((bool)filter_var($this->filter->has_photo, FILTER_VALIDATE_BOOLEAN));

    }

    public function getPaginate()
    {
        return $this->users()->orderBy('name')->paginate();
    }

    public function getAll()
    {
        return $this->users()->orderBy('name')->get();
    }

    public function getRoles()
    {
        $roleView = new RoleViewModel($this->user);
        return $roleView->getAll();
    }

    public function getCompanies()
    {
        return Company::query()->get();
    }

    public function getIndex()
    {
        return [
            'users' => $this->getPaginate(),
            'roles' => $this->getRoles(),
            'companies' => $this->getCompanies()
        ];
    }

}
