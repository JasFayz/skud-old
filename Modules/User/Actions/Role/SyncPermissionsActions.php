<?php

namespace Modules\User\Actions\Role;

use App\Http\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Modules\User\Repositories\RoleRepository;

class SyncPermissionsActions
{
    public function __construct(private RoleRepository $repository)
    {
    }

    public function __invoke($id, $permissions, $payload = null)
    {
        DB::beginTransaction();
        try {

            $model = $this->repository->findById($id);
            $model->syncPermissions($permissions);
            if ($payload) {
                $model->update($payload);
            }
            DB::commit();

            return ResponseHelper::success($model->fresh(), 'Successfully synced permissions');
        } catch (\Throwable $exception) {
            DB::rollBack();
            return ResponseHelper::error([], $exception->getMessage() );
        }
    }
}
