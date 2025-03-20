<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\API\PermissionController;
use Modules\User\Http\Controllers\API\RoleController;
use Modules\User\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth')->group(function () {

    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    Route::get('permissions', [PermissionController::class, 'getPermissions'])->name('permissions');
    Route::post('permission/create', [PermissionController::class, 'createPermission'])->name('permission.create');
    Route::put('permission/{permission}/update', [PermissionController::class, 'updatePermission'])->name('permission.update');
    Route::delete('permission/{permission}/delete', [PermissionController::class, 'deletePermission'])->name('permission.delete');

    Route::get('permission-groups', [PermissionController::class, 'getGroups'])->name('permission-groups');
    Route::post('permission-group/create', [PermissionController::class, 'createPermissionGroup'])->name('permission-group.create');
    Route::put('permission-group/{permission_group}/update', [PermissionController::class, 'updatePermissionGroup'])->name('permission-group.update');
    Route::delete('permission-group/{permission_group}/delete', [PermissionController::class, 'deletePermissionGroup'])->name('permission-group.delete');

    Route::get('roles', [RoleController::class, 'getRoles'])->name('roles');
    Route::get('/role/get-all', [RoleController::class, 'getAll'])->name('role.get-all');
    Route::post('role/create', [RoleController::class, 'createRole'])->name('role.create');
    Route::put('role/{role}/update', [RoleController::class, 'updateRole'])->name('role.update');
    Route::delete('role/{role}/delete', [RoleController::class, 'deleteRole'])->name('role.delete');
    Route::patch('role/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('role.sync-permissions');

    Route::get('/user', [UserController::class, 'getUsers']);
    Route::get('/user/get-all', [UserController::class, 'getAll']);
//    Route::post('/user/telegram', [UserController::class, 'loginTerminal']);
//    Route::get('/user/division-ids', [UserController::class, 'getByDivisionIds']);
    Route::get('/user/department-ids', [UserController::class, 'getByDepartmentIds']);
    Route::post('/user/{user}/login-as', [UserController::class, 'loginAs'])->middleware('role:super_admin');
    Route::get('/user/export/images', [UserController::class, 'exportUsersImages']);
    Route::get('/user/export', [UserController::class, 'export']);
    Route::post('/user/import', [UserController::class, 'import']);
    Route::post('/user/refresh-password', [UserController::class, 'refreshPassword']);
    Route::apiResource('user', UserController::class)->except('index');
    Route::post('/users/telegrams/remove', [UserController::class, 'removeTelegram']);
});
