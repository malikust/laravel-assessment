<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
    return view('register');
});

Route::get('/signupInvitation', [UserController::class, 'signupInvitation']);
Route::get('/create/account', [UserController::class, 'createAccount']);
Route::post('/update/account', [UserController::class, 'userUpdateAccount']);
Route::post('/login', [UserController::class, 'userLogin']);
Route::post('/userVerify', [UserController::class, 'userVerify']);
Route::get('/logout', [UserController::class, 'logout']);

