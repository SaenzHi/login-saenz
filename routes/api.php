<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController;

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login');
    Route::post('/login', 'postLogin');
    Route::get('/login/register', 'register');
    Route::post('/login/register', 'save');
    Route::get('/login/recover', 'recover');
    Route::post('/login/recover', 'sendCode');
    Route::get('/login/validate', 'validate');
    Route::post('/login/validate', 'validateCode');
    Route::get('/login/passwordd', 'passwordd');
    Route::post('/login/passwordd', 'updatePasswordd');
});