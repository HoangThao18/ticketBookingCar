<?php

use App\Http\Controllers\API\checkoutController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\LogoutController;
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
Route::get("/reset-password", [ResetPasswordController::class, "reset"]);
Route::post("/register", [RegisterController::class, "register"]);
Route::get("/login/{provider}", [LoginController::class, "redirectToProvider"]);
Route::get("/login/{provider}/callback", [LoginController::class, "handleProviderCallback"]);

Route::get("/trip/search", [TripController::class, 'search']);
Route::get("/popular-trips", [TripController::class, 'getPopularTrips']);
Route::get("/trip/{trip}", [TripController::class, 'show'])->name('trip.show');

Route::get("/ticket/search", [TicketController::class, 'searchByCode']);
Route::get("/vnpay-return", [checkoutController::class, 'vnpayReturn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post("/vnpay-payment", [checkoutController::class, 'vnpayPayment']);
    Route::get("/logout", [LogoutController::class, "logout"]);
    Route::get('/user/profile', [LoginController::class, 'getUser']);
    Route::put('/user/cancel-booking', [checkoutController::class, 'cancelBooking']);
    Route::put("/user", [UserController::class, 'update']);
    Route::put("/user/change-password", [UserController::class, 'changePassword']);
});
