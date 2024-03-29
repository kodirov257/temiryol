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

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', 'Auth\RegistrationController@create')->name('register.show');
        Route::post('register', 'Auth\RegistrationController@register')->name('register');
        Route::post('register/complete', 'Auth\RegistrationController@completeRegistration')->name('register.complete');

        Route::get('/verify-email', 'Auth\VerificationController@verifyEmailForm')->name('verification.email.notice');
        Route::get('/verify-email/{id}/{hash}', 'Auth\VerificationController@verifyEmail')
            ->middleware(['signed', 'throttle:6,1'])->name('verification.email.verify');
        Route::post('/verify-email/resend', 'Auth\VerificationController@sendEmailVerificationNotification')
            ->middleware(['throttle:6,1'])->name('verification.email.send');

        Route::get('/forgot-password', 'Auth\PasswordResetController@showEmail')->name('password.email.request');
        Route::post('/forgot-password-email', 'Auth\PasswordResetController@sendResetByEmail')->name('password.email.send');
        Route::get('/reset-password-email/{token}', 'Auth\PasswordResetController@showResetByEmail')->name('password.email.reset.show');
        Route::post('/reset-password-email', 'Auth\PasswordResetController@resetByEmail')->name('password.email.reset');

        Route::get('/two-factor-auth', 'Auth\TwoFactorAuthController@create')->name('two-factor-auth.create');
        Route::post('/two-factor-auth', 'Auth\TwoFactorAuthController@store')->name('two-factor-auth.store');
        Route::post('/two-factor-auth/complete', 'Auth\TwoFactorAuthController@complete')->name('two-factor-auth.complete');

        Route::get('/login', 'Auth\LoginController@loginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login')->name('signin');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/confirm-password', 'Auth\ConfirmPasswordController@show')->name('password.confirm.show');
        Route::post('/confirm-password', 'Auth\ConfirmPasswordController@confirm')->name('password.confirm');

        Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

        Route::middleware('2fa')->group(function () {
            Route::put('/password', 'Auth\UserController@updatePassword')->name('password.update');
            Route::post('/2fa', 'Auth\LoginController@google2fa')->name('2fa');
        });
    });

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Admin', 'middleware' => ['auth', 'can:admin-panel', '2fa']], function () {
        Route::get('/', 'DashboardController@index')->name('home');

        Route::resource('users', 'UserController');
        Route::post('users/{user}/remove-photo', 'UserController@removeAvatar')->name('remove-photo');

        Route::resource('regions', 'RegionController');
        Route::resource('organizations', 'OrganizationController');
        Route::resource('departments', 'Department\DepartmentController');
        Route::group(['prefix' => 'departments/{department}', 'namespace' => 'Department', 'as' => 'departments.'], function () {
            Route::get('employees/add', 'DepartmentController@addWorkerForm')->name('employees.add.form');
            Route::post('employees/add', 'DepartmentController@addWorker')->name('employees.add');
            Route::delete('employees/{employee}/delete', 'DepartmentController@removeWorker')->name('employees.remove');

            Route::get('instruments/create', 'InstrumentController@create')->name('instruments.create');
            Route::post('instruments/store', 'InstrumentController@store')->name('instruments.store');
        });
        Route::resource('instrument-types', 'Instrument\InstrumentTypeController');
        Route::group(['prefix' => 'instrument-types/{instrumentType}', 'namespace' => 'Instrument', 'as' => 'instrument-types.'], function () {
            Route::post('remove-photo', 'InstrumentTypeController@removePhoto')->name('remove-photo');
        });
        Route::group(['prefix' => 'department-instrument-types/{departmentInstrumentType}', 'namespace' => 'Instrument', 'as' => 'department-instrument-types.'], function () {
            Route::resource('instruments', 'InstrumentController');
            Route::group(['prefix' => 'instruments/{instrument}', 'as' => 'instruments.'], function () {
                Route::get('destroy', 'InstrumentController@destroyForm')->name('destroy.form');
            });
        });
        Route::get('instruments', 'Instrument\InstrumentController@indexAll')->name('instruments.index');

        Route::group(['prefix' => 'instruments/{instrument}', 'namespace' => 'Instrument', 'as' => 'instruments.'], function () {
            Route::get('operations/rent', 'OperationController@rentForm')->name('operations.rent.form');
            Route::post('operations/rent', 'OperationController@rent')->name('operations.rent');
            Route::resource('operations', 'OperationController')->except(['create', 'store', 'destroy']);
            Route::group(['prefix' => 'operations/{operation}', 'as' => 'operations.'], function () {
                Route::get('prolong', 'OperationController@prolongForm')->name('prolong.form');
                Route::post('prolong', 'OperationController@prolong')->name('prolong');
                Route::get('close', 'OperationController@closeForm')->name('close.form');
                Route::post('close', 'OperationController@close')->name('close');
            });
        });
        Route::get('operations', 'Instrument\OperationController@indexAll')->name('operations.index');

        Route::post('/darkmode/toggle', [DarkModeController::class, 'toggle'])
            ->name('darkmode.toggle');
    });

    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});
