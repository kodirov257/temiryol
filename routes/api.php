<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['as' => 'api.', 'namespace' => 'Api'], function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', 'Auth\RegistrationController@register')->name('registration.register');

        Route::post('/verify-email', 'Auth\VerificationController@verifyByEmail')
            ->middleware(['throttle:api:6,1'])->name('verification.email.verify');
        Route::post('/verify-email/resend', 'Auth\VerificationController@sendEmailVerificationNotification')
            ->middleware(['throttle:api:6,1'])->name('verification.email.send');

        Route::post('/forgot-password-email', 'Auth\PasswordResetController@sendResetByEmail')->name('password.email.send');
        Route::post('/reset-password-email', 'Auth\PasswordResetController@resetByEmail')->name('password.email.reset');

        Route::post('/login', 'Auth\LoginController@login')->name('signin');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/auth/me', 'UserController@info');

        Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    });

    Route::get('/search-regions', 'SearchController@searchRegions');
    Route::get('/search-users', 'SearchController@searchUsers');
    Route::get('/search-departments', 'SearchController@searchDepartments');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
