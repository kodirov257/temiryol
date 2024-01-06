<?php

use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', 'Auth\LoginController@loginForm');

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Admin'/*, 'middleware' => ['auth', 'can:admin-panel']*/], function () {
    Route::get('/', 'DashboardController@index')->name('home');

    Route::post('/darkmode/toggle', [DarkModeController::class, 'toggle'])
        ->name('darkmode.toggle');
});

Route::get('/', function () {
    return view('welcome');
});
