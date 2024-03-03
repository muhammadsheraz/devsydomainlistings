<?php

use App\Http\Controllers\CreateAccount;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\LoginController;
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

Route::post('/register', CreateAccount::class);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/login/2fa', [LoginController::class, 'twoFactorAuthentication']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('domains', [DomainController::class, 'add']);
});

Route::post('/bids', [BidController::class, 'create']);
