<?php

use Modules\Skud\Http\Controllers\API\AccessController;
use Modules\Skud\Http\Controllers\API\CompanyController;
use Modules\Skud\Http\Controllers\API\FloorController;
use Modules\Skud\Http\Controllers\API\TerminalController;
use Modules\Skud\Http\Controllers\API\ZoneController;


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

Route::prefix('access')->middleware('auth')->group(function () {
    Route::get('/users', [AccessController::class, 'getUsers']);
    Route::get('/zones', [AccessController::class, 'getZones']);
//    Route::post('/user/{user}/add-zone-user', [AccessController::class, 'addZoneUser']);
//    Route::post('/user/{user}/remove-zone-user', [AccessController::class, 'removeZoneUser']);
    Route::post('/user/{user}/processingZones', [AccessController::class, 'processingUserZones']);
    Route::post('/guest/{guest}/processingZones', [AccessController::class, 'processingGuestZones']);
//    Route::post('/door-key/{doorKey}/add-zone-door-key', [AccessController::class, 'addZoneDoorKey']);
//    Route::post('/door-key/{doorKey}/remove-zone-door-key', [AccessController::class, 'removeZoneDoorKey']);
//    Route::post('/batch-add-zone', [AccessController::class, 'setBatchZoneUser']);
    Route::post('/user/{user}/check-zone-terminal-status', [AccessController::class, 'checkZoneTerminalStatus']);

    Route::post('/terminal/check-status/', [AccessController::class, 'checkTerminalStatus']);
    Route::post('/user/{user}/set-access', [AccessController::class, 'setAccess']);
});


Route::group([
    'middleware' => 'auth'
], function () {
    Route::post('/terminal/{terminal}/sync-time', [TerminalController::class, 'syncTime']);
    Route::post('/terminal/{terminal}/export/users', [TerminalController::class, 'exportTerminalUsers']);
    Route::get('/terminal/logs/requests', [TerminalController::class, 'getRequestLogs']);
    Route::get('/terminal/logs/request/export', [TerminalController::class, 'exportRequestLog']);
    Route::get('/terminal/logs/actions', [TerminalController::class, 'getActionLogs']);
    Route::get('/floor', [FloorController::class, 'getFloors']);
    Route::get('/floor/get-all', [FloorController::class, 'getAll']);
    Route::get('/floor_by_company', [FloorController::class, 'getByCompany']);
    Route::get('/company', [CompanyController::class, 'getCompanies']);
    Route::get('/company/get-all', [CompanyController::class, 'getAll']);
    Route::get('/terminal', [TerminalController::class, 'getTerminals']);
    Route::get('/terminal/get-all', [TerminalController::class, 'getAll']);
    Route::get('/zone', [ZoneController::class, 'getZones']);
    Route::get('/zone/get-all', [ZoneController::class, 'getAll']);
    Route::get('/zone/get-by-type', [ZoneController::class, 'getAllByType']);

    Route::get('/terminal/{terminal}/get-user-list', [TerminalController::class, 'getUserListFromTerminal']);
    Route::post('/terminal/{terminal}/user/{terminalUserId}/', [TerminalController::class, 'deleteUserFromTerminal']);
    Route::post('/terminal/{terminal}/change-logo', [TerminalController::class, 'changeTerminalLogo']);
    Route::post('/terminal/{terminal}/change-background', [TerminalController::class, 'changeTerminalBackground']);
    Route::post('/terminal/change-background-for-all', [TerminalController::class, 'changeAllTerminalBackground']);
    Route::post('/terminal/change-logo-for-all', [TerminalController::class, 'changeAllTerminalLogo']);

    Route::apiResource('floor', FloorController::class)->except(['index', 'getByCompany']);
    Route::apiResource('company', CompanyController::class)->except('index');
    Route::apiResource('terminal', TerminalController::class)->except('index');
    Route::apiResource('zone', ZoneController::class)->except('index');

});

Route::post('/terminal/log', [TerminalController::class, 'storeLog']);


Route::prefix('v1')->group(function () {
    Route::post('/terminal/logs/requests', [TerminalController::class, 'getRequestLogByPinfl'])
        ->middleware(['throttle:terminal_request_log']);
});

