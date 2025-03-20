<?php

namespace Modules\User\Services;

use Illuminate\Support\Str;
use Modules\User\Entities\Permission;
use Modules\User\Entities\PermissionGroup;

class PermissionService
{
    public function createPermissionGroup(array $data): array
    {
        $permissionGroup = PermissionGroup::create(array_merge($data, ['slug' => Str::slug($data['name'])]));

        return ['success' => (bool)$permissionGroup];
    }

    public function updatePermissionGroup(PermissionGroup $permissionGroup, array $data)
    {

        $permissionGroup->update([...$data, 'slug' => Str::slug($data['name'])]);

        return ['success' => (bool)$permissionGroup];
    }

    public function createPermission(array $data): array
    {
        $permission = Permission::create([...$data, 'guard_name' => 'web']);

        return ['success' => (bool)$permission];
    }

    public function updatePermission(Permission $permission, $data): array
    {
        return ['success' => $permission->update([...$data, 'guard_name' => 'web'])];
    }
}
