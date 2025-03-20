<?php

use Illuminate\Http\Request;
use Modules\HR\Http\Controllers\API\DayOffController;
use Modules\HR\Http\Controllers\API\DepartmentController;
use Modules\HR\Http\Controllers\API\DeviceController;
use Modules\HR\Http\Controllers\API\MeetingController;
use Modules\HR\Http\Controllers\API\PositionController;
use Modules\HR\Http\Controllers\API\PositionGradeController;
use Modules\HR\Http\Controllers\API\StaffController;

//use Modules\HR\Http\Controllers\DivisionController;

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

Route::middleware('auth:api')->get('/management', function (Request $request) {
    return $request->user();
});

Route::middleware('auth')->group(function () {
    Route::get('/employees', [StaffController::class, 'getEmployees']);
    Route::get('/department', [DepartmentController::class, 'getDepartments']);
//Route::get('/division', [DivisionController::class, 'getDivisions']);
    Route::get('/position', [PositionController::class, 'getPositions']);
    Route::get('/grade', [PositionGradeController::class, 'getGrades']);
    Route::get('/grade/all', [PositionController::class, 'getAll']);
    Route::get('/device', [DeviceController::class, 'getDevices']);
    Route::get('/free-devices', [DeviceController::class, 'getFreeDevices']);
    Route::get('/day-offs', [DayOffController::class, 'getDayOffs']);
    Route::get('/day-off-types', [DayOffController::class, 'getDayOffTypes']);

    Route::get('/employees/get-all', [StaffController::class, 'getAllEmployees']);
    Route::post('/employee/attach-device', [StaffController::class, 'attachDevice']);
    Route::post('/employee/detach-device', [StaffController::class, 'detachDevice']);
    Route::get('/department/get-all', [DepartmentController::class, 'getAll']);
    Route::get('/department/get-tree', [DepartmentController::class, 'getDepartmentTree']);
    Route::post('/department/change-node', [DepartmentController::class, 'changeNode']);
    Route::post('/department/{department}/attach-position', [DepartmentController::class, 'attachPosition']);
//Route::get('/division/get-all', [DivisionController::class, 'getAll']);
    Route::get('/position/get-all', [PositionController::class, 'getAll']);
//Route::get('/division/department/department-ids', [DivisionController::class, 'getByDepartmentIDs']);

//Route::get('/division/department/{department}', [DivisionController::class, 'getByDepartmentId']);
    Route::get('/department/division/{division}', [DepartmentController::class, 'getByDivisionId']);
    Route::get('/department/company/auth-user', [DepartmentController::class, 'getByCompanyId']);
    Route::get('/position/division/{division}', [PositionController::class, 'getByPositionId']);
    Route::get('/position/department/{department}', [PositionController::class, 'getByDepartmentId']);

    Route::get('/division/company/auth-user', [DepartmentController::class, 'getByCompanyId']);


    Route::post('/staff', [StaffController::class, 'store']);
    Route::post('/staff/{user}/firing', [StaffController::class, 'firingUser']);
    Route::put('/employee/update/{user}', [StaffController::class, 'update']);
    Route::post('/employee/{user}/set-schedule', [StaffController::class, 'setSchedule']);
    Route::post('/staff/add-to-watch-list', [StaffController::class, 'addToWatchList']);
    Route::post('/staff/{user}/remove-from-watch-list', [StaffController::class, 'removeFromWatchList']);
    Route::apiResources([
        'department' => DepartmentController::class,
        'position' => PositionController::class,
//    'division' => DivisionController::class,
        'day-offs' => DayOffController::class,
        'grade' => PositionGradeController::class,
        'device' => DeviceController::class
    ], ['except' => 'index']);

    Route::apiResource('meeting', MeetingController::class);
});



