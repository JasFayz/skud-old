<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//use Modules\Attendance\Http\Controllers\AttendanceController;
use Modules\HR\Http\Controllers\API\DayOffController;
use Modules\HR\Http\Controllers\API\DepartmentController;
use Modules\HR\Http\Controllers\API\DeviceController;
use Modules\HR\Http\Controllers\API\PositionController;
use Modules\HR\Http\Controllers\API\PositionGradeController;
use Modules\HR\Http\Controllers\API\StaffController;
use Modules\Skud\Http\Controllers\API\AccessController;

//use Modules\HR\Http\Controllers\DivisionController;
//use Modules\Management\Entities\Division;
//use Modules\Management\Entities\Position;

Route::prefix('management')
    ->middleware(['auth'])
    ->group(function () {

//        $positionModel = Position::with('division')->get();
//        $divisionModel = Division::all();

//        $positionID = $positionModel
//            ->where('name', '=', 'Техник-программист')
//            ->where('division_id', '=',  $divisionModel->where('name', '=', 'Подразделение по Ферганской области')->first()->id)
//            ->first();
//        dd($positionID);
//        Route::get('/', 'ManagementController@index');
        Route::get('/employee', [StaffController::class, 'indexWeb']);
        Route::get('/access', [AccessController::class, 'indexWeb']);
        Route::get('/department', [DepartmentController::class, 'indexWeb']);
        Route::get('/device', [DeviceController::class, 'indexWeb']);
//        Route::get('/division', [DivisionController::class, 'indexWeb']);
        Route::get('/position', [PositionController::class, 'indexWeb']);
        Route::get('/grade', [PositionGradeController::class, 'indexWeb']);
        Route::get('/day-offs', [DayOffController::class, 'indexWeb']);
    });
