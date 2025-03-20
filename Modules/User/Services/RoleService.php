<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Role;

class RoleService
{
    public function createRole($data)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $data['name'],
                'label' => $data['label'],
                'grade' => $data['grade']
            ]);
            $role->givePermissionTo($data['permissions']);
            DB::commit();
            return ['success' => true, 'data' => $role];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function updateRole(Role $role, array $data): array
    {
        return ['success' => $role->update([
            'name' => $data['name'],
            'label' => $data['label']
        ])];
    }

    public function syncPermissions(Role $role, array $permissions): array
    {
        return ['success' => $role->syncPermissions($permissions)];
    }
}
