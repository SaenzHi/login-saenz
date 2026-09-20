<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController;

Route::controller(LoginController::class)->group( function() {

    Route::get('/login', 'login');
    Route::post('/login', 'post_login');

    Route::get('/login/register', 'register');
    Route::post('/login/register', 'save');

    Route::get('/login/recover', 'recover');
    Route::post('/login/recover', 'send_code');

    Route::get('/login/validate', 'validate');
    Route::post('/login/validate', 'validate_code');

    Route::get('/login/passwordd', 'passwordd');
    Route::post('/login/passwordd', 'update_passwordd');

});