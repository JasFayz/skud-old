<?php

namespace Modules\User\Actions\Role;

use App\Http\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Modules\User\DTO\RoleDTO;
use Modules\User\Entities\Permission;
use Modules\User\Repositories\RoleRepository;

class CreateRoleAction
{
    public function __construct(private RoleRepository $repository)
    {
    }

    public function __invoke(RoleDTO $roleDTO)
    {
        try {
            DB::beginTransaction();

            $role = $this->repository->create([...$roleDTO->toArray(), 'guard_name' => 'web']);

            $this->repository->syncPermissions($role, $roleDTO->permissions);
            DB::commit();
            return ResponseHelper::success($role, 'Successfully create Role');

        } catch (\Throwable $exception) {
            DB::rollBack();
            return ResponseHelper::error([], $exception->getMessage());
        }
    }
}
