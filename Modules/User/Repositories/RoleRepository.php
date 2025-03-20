<?php

namespace Modules\User\Repositories;

use Modules\User\Entities\Role;

class RoleRepository
{

    public function __construct(private Role $model)
    {
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Role
    {
        $model = $this->findById($id);
        $model->updateOrFail($data);

        return $model;
    }

    public function delete($id)
    {
        return $this->findById($id)->deleteOrFail();
    }

    public function syncPermissions(Role $role, array $permissions)
    {
        return $role->syncPermissions($permissions);
    }
}
