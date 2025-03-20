<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\User\Entities\User;
use Illuminate\Auth\AuthenticationException;
use Modules\User\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*///Route::prefix('user')->group(function() {
//    Route::get('/', 'UserController@index');
//});

Route::get('/login/digital', function (Request $request) {

    $token = $request->query('token');

    try {
        if (!$token) {
            throw new AuthenticationException('Token missing');
        }

        $res = Http::post('https://digital.egov.uz/apiUser/auth/authorization', [
            'token' => $token
        ]);

        $payload = json_decode($res->body());

        $pinfl = $payload->result?->pnfl;

        if (!$pinfl && !isset($pinfl)) {
            throw new AuthenticationException('No pinfl');
        }

        $user = User::where('pinfl', '=', $pinfl)->first();
        if (!$user) {
            throw new AuthenticationException('No such user');
        }
        Auth::login(user: $user);
        return redirect('/profile');


    } catch (Throwable $exception) {
        return redirect()->to('/login')->with([
            'error_message' => $exception->getMessage()
        ]);
    }
});

//Route::prefix('consumer')->group(function () {
//    Route::resource('users', UserController::class);
//});
Route::get('/telegram/callback', [UserController::class, 'telegramCallback']);
