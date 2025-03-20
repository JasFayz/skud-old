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

use Modules\Visitor\Http\Controllers\Admin\GuestController;
use Modules\Visitor\Http\Controllers\Admin\InviteController;

Route::prefix('admin')
    ->middleware(['auth'])
    ->as('admin.')->group(function () {
        Route::prefix('visitor')
            ->as('visitor.')
            ->group(function () {
                Route::patch('/guests/{guest}/remove-photo', [GuestController::class, 'removePhoto'])->name('guests.remove-photo');
                Route::resource('guests', GuestController::class);
                Route::get('/invite/{invite}/download-qr-code', [InviteController::class, 'downloadInviteQRCode']);
                Route::resource('invites', InviteController::class);
            });
    });


