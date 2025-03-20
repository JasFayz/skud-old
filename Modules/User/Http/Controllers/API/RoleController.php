<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Actions\Role\CreateRoleAction;
use Modules\User\Actions\Role\DeleteRoleAction;
use Modules\User\Actions\Role\SyncPermissionsActions;
use Modules\User\Actions\Role\UpdateRoleAction;
use Modules\User\DTO\RoleDTO;
use Modules\User\Entities\Role;
use Modules\User\Http\Requests\CreateRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\User\Services\RoleService;
use Modules\User\ViewModels\RoleViewModel;

class RoleController extends Controller
{

    public function __construct(public RoleService $roleService)
    {
        $this->middleware('permission:role.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:role.create', ['only' => 'createRole']);
        $this->middleware('permission:role.update', ['only' => 'updateRole']);
        $this->middleware('permission:role.destroy', ['only' => 'deleteRole']);
    }

    public function indexWeb()
    {
        return view('admin::role');
    }

    public function getAll()
    {
        return Role::initQuery()->with('permissions')->get();
    }

    public function getRoles(Request $request)
    {

        $viewModel = new RoleViewModel(auth()->user());

        return $viewModel->getIndex();
    }

    public function createRole(CreateRoleRequest $request, CreateRoleAction $createRoleAction)
    {

        $dto = RoleDTO::fromRequest($request);
        return $createRoleAction($dto);

    }

    public function updateRole(Role $role, UpdateRoleRequest $request, UpdateRoleAction $updateRoleAction)
    {
        $dto = RoleDTO::fromRequest($request);

        return $updateRoleAction($role->id, $dto);
    }

    public function syncPermissions(Role $role, Request $request, SyncPermissionsActions $syncPermissionsActions)
    {

        $payload = [
            'team_id' => $request->get('team_id')
        ];

        $syncPermissionsActions($role->id, $request->get('permissions'), $payload);
        return response()->json($this->roleService->syncPermissions($role, $request->get('permissions')));
    }

    public function deleteRole(Role $role, DeleteRoleAction $deleteRoleAction)
    {
        return $deleteRoleAction($role->id);

    }
}
