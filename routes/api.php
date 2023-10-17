<?php

use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\password;

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

Route::post("/login", [LoginController::class, "login"]);
Route::post("/forgot-password", [ResetPasswordController::class, "forgotPassword"])->name('password.reset');
Route::post("/reset-password", [ResetPasswordController::class, "reset"]);
Route::post("/register", [RegisterController::class, "register"]);
Route::get("/login/{provider}", [LoginController::class, "redirectToProvider"]);
Route::get("/login/{provider}/callback", [LoginController::class, "handleProviderCallback"]);

Route::get("/trip/{trip}", [TripController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'getUser']);
});
