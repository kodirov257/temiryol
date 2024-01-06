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

Route::middleware('guest')->group(function () {
    Route::get('/login', 'Auth\LoginController@loginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('signin');

});

Route::middleware('auth')->group(function () {
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Admin', 'middleware' => ['auth'/*, 'can:admin-panel'*/]], function () {
    Route::get('/', 'DashboardController@index')->name('home');

    Route::post('/darkmode/toggle', [DarkModeController::class, 'toggle'])
        ->name('darkmode.toggle');
});

Route::get('/', function () {
    return view('welcome');
});
