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

Route::middleware('auth')->group(function () {Route::get('/verify', 'Auth\VerificationController@verifyForm')->name('verification.show');
    Route::post('/verify-email/{id}/{hash}', 'Auth\VerificationController@verifyEmail')
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', 'Auth\VerificationController@sendEmailVerificationNotification')
        ->middleware(['signed', 'throttle:6,1'])->name('verification.send');

    Route::get('/confirm-password', 'Auth\ConfirmPasswordController@show')->name('password.confirm.show');
    Route::post('/confirm-password', 'Auth\ConfirmPasswordController@confirm')->name('password.confirm');

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
