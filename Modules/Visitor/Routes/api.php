<?php

use Illuminate\Http\Request;
use Modules\Visitor\Http\Controllers\GuestController;
use Modules\Visitor\Http\Controllers\InviteController;

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

Route::middleware('auth:api')->get('/visitor', function (Request $request) {
    return $request->user();
});


Route::prefix('/visitor')->group(function () {
    Route::get('/guest/get-guests', [GuestController::class, 'getGuests']);
    Route::get('/invite/get-invites', [InviteController::class, 'getInvites']);
    Route::post('/invite/{invite}/attach-image', [InviteController::class, 'attachImage']);
    Route::post('/invite/{invite}/set-status', [InviteController::class, 'setStatus']);
    Route::get('/invite/get-expired-invites-quantity', [InviteController::class, 'getExpiredInvitesQuantity']);
    Route::post('/guest-deleted-log/{guest_deleted_log}/retry-delete-guest', [InviteController::class, 'retryDeleteGuest']);

    Route::apiResource('/guest', GuestController::class);
    Route::apiResource('/invite', InviteController::class);
});
