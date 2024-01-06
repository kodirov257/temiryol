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
    Route::get('register', 'Auth\RegistrationController@create')->name('register.show');
    Route::post('register', 'Auth\RegistrationController@register')->name('register');

    Route::get('/verify', 'Auth\VerificationController@verifyForm')->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', 'Auth\VerificationController@verifyEmail')
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', 'Auth\VerificationController@sendEmailVerificationNotification')
        ->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/forgot-password', 'Auth\PasswordResetController@showEmail')->name('password.email.request');
    Route::post('/forgot-password-email', 'Auth\PasswordResetController@sendResetByEmail')->name('password.email.send');
    Route::get('/reset-password/{token}', 'Auth\PasswordResetController@resetPassword')->name('password.reset');
    Route::get('/reset-password-email/{token}', 'Auth\PasswordResetController@showResetByEmail')->name('password.email.reset.show');
    Route::post('/reset-password-email', 'Auth\PasswordResetController@resetByEmail')->name('password.email.reset');

    Route::get('/login', 'Auth\LoginController@loginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('signin');
});

Route::middleware('auth')->group(function () {
    Route::get('/confirm-password', 'Auth\ConfirmPasswordController@show')->name('password.confirm.show');
    Route::post('/confirm-password', 'Auth\ConfirmPasswordController@confirm')->name('password.confirm');
    Route::put('/password', 'Auth\UserController@updatePassword')->name('password.update');

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
