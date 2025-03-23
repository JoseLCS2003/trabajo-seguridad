<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

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
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'loginView')->name('login');
        Route::post('/login', 'login');

        Route::get('/register', 'registerView')->name('register');
        Route::post('/register', 'createUser');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/home', 'homeView')->name('home');
        Route::post('/logout', 'logout')->name('logout');
    });
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/verify/code/{id}', 'sendAuthCode')
        ->whereNumber('id')
        ->middleware('signed');

    Route::post('/verify/code/{id}', 'verifyCode')
        ->name('verify.code')
        ->whereNumber('id');
});
