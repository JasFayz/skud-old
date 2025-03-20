<?php

namespace Modules\User\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\User\Entities\Permission;
use Modules\User\Entities\PermissionGroup;
use Modules\User\Entities\Role;
use Modules\User\Services\PermissionService;

class PermissionController extends Controller
{

    public function __construct(public PermissionService $permissionService)
    {
        $this->middleware('permission:permission.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('role:super_admin', ['only' => ['createPermissionGroup',
            'createPermission', 'updatePermission', 'updatePermissionGroup', 'deletePermissionGroup', 'deletePermission']]);

    }

    public function getPermissions()
    {
        return Permission::query()->with('permissionGroup')->paginate();
    }

    public function getAll()
    {
        return Permission::query()->with('permissionGroup')->get();
    }

    public function getGroups()
    {
        $permissionGroups = PermissionGroup::query()->with('permissions')
            ->where('slug', '!=', 'permission')->get();

        return [
            'data' => $permissionGroups,
            'teams' => Role::list()
        ];
    }

    public function createPermissionGroup(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|numeric', Rule::in(array_keys(Role::list()))
        ]);

        $response = $this->permissionService->createPermissionGroup($validate);

        return response()->json($response);
    }


    public function createPermission(Request $request)
    {

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'permission_group_id' => 'required|numeric',
        ]);

        $response = $this->permissionService->createPermission($validate);

        return response()->json($response);
    }

    public function updatePermission(Permission $permission, Request $request)
    {

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'permission_group_id' => 'required|numeric'
        ]);

        return response()->json($this->permissionService->updatePermission($permission, $validate));

    }

    public function updatePermissionGroup(PermissionGroup $permissionGroup, Request $request)
    {

        $validate = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return response()->json($this->permissionService->updatePermissionGroup($permissionGroup, $validate));

    }


//    public function updatePermissionGroup(Permission $permission, Request $request)
//    {
//
//        $validate = $request->validate([
//            'name' => 'required|string|max:255',
//            'label' => 'required|string|max:255',
//            'permission_group_id' => 'required|numeric'
//        ]);
//
//        return response()->json($this->permissionService->updatePermission($permission, $validate));
//
//    }

    public function deletePermissionGroup(PermissionGroup $permissionGroup)
    {
        return response()->json([
            'success' => $permissionGroup->delete()
        ]);
    }

    public function deletePermission(Permission $permission)
    {
        return response()->json([
            'success' => $permission->delete()
        ]);
    }

}
