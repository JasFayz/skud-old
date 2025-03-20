<?php

use App\Http\Controllers\ProfileController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\TerminalUserIdentifier;

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

Route::get('/', function () {
    return redirect()->route('login');
});
//require __DIR__ . '/import-image.php';
require __DIR__ . '/auth.php';
