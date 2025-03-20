<?php

namespace Modules\User\Actions\Role;

use App\Http\ResponseHelper;
use Modules\User\Repositories\RoleRepository;

class DeleteRoleAction
{

    public function __construct(private RoleRepository $repository)
    {
    }


    public function __invoke($id)
    {
        try {
            $this->repository->delete($id);

            return ResponseHelper::success([], 'Successfully deleted');
        } catch (\Exception $exception) {
            return ResponseHelper::error([], $exception->getMessage());
        }
    }
}
