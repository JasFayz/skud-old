<?php

namespace Modules\User\ViewModels;


use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Entities\PermissionGroup;
use Modules\User\Entities\Role;

class RoleViewModel
{
    public function __construct(protected Authenticatable $user)
    {
    }

    protected function roles()
    {
        return Role::query()
            ->forUser($this->user)
            ->with(['permissions']);
    }

    public function getPaginate()
    {
        return $this->roles()->paginate();
    }

    public function getAll()
    {
        return $this->roles()->get();
    }

    public function getPermissionGroup()
    {
        $permission_groups = PermissionGroup::query()->with('permissions')
            ->where('slug', '!=', 'permission')->get();

        return [
            'permission_groups' => $permission_groups,
            'teams' => Role::list()
        ];
    }

    public function getIndex($page = 1)
    {
        return [
            'roles' => $this->getPaginate(),
            'groups' => $this->getPermissionGroup()
        ];
    }
}
