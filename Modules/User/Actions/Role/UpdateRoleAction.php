<?php

namespace Modules\User\Actions\Role;

use App\Http\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Modules\User\DTO\RoleDTO;
use Modules\User\Repositories\RoleRepository;

class UpdateRoleAction
{
    public function __construct(private RoleRepository $repository)
    {
    }

    public function __invoke($id, RoleDTO $roleDTO)
    {
        try {
            DB::beginTransaction();
            $role = $this->repository->update($id, [...$roleDTO->toArray(), 'guard_name' => 'web']);
            $this->repository->syncPermissions($role, $roleDTO->permissions);
            DB::commit();
            return ResponseHelper::success($role, 'Successfully created');
        } catch (\Throwable $exception) {
            DB::rollBack();
            return ResponseHelper::error([], $exception->getMessage());
        }
    }
}
