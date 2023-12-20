<?php

use App\Http\Controllers\API\User\StationController;
use App\Http\Controllers\API\User\CarController;
use App\Http\Controllers\API\User\checkoutController;
use App\Http\Controllers\API\User\CommentController;
use App\Http\Controllers\API\User\JobController;
use App\Http\Controllers\API\User\NewsController;
use App\Http\Controllers\API\User\RouteController;
use App\Http\Controllers\API\User\TicketController;
use App\Http\Controllers\API\User\TripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\MailController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Library\HttpResponse;
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
Route::get("/trip/popular", [TripController::class, 'getPopularTrips']);
Route::get("/trip/{trip}", [TripController::class, 'show'])->name('trip.show');

Route::get("/station", [StationController::class, 'index']);
Route::get("/station/province", [StationController::class, 'getProvince']);
Route::get("/news", [NewsController::class, 'index']);
Route::get("/news/popular", [NewsController::class, 'getPopularNews']);
Route::get("/news/lastest", [NewsController::class, 'getLatestNews']);
Route::get("/news/{id}", [NewsController::class, 'show']);
Route::get("/job", [JobController::class, 'index']);
Route::get("/job/{id}", [JobController::class, 'show']);

Route::get("car/{id}/comments", [CommentController::class, 'show']);
Route::get("/ticket/search", [TicketController::class, 'search']);
Route::get("/vnpay-return", [checkoutController::class, 'vnpayReturn']);
Route::post("/vnpay-payment", [checkoutController::class, 'vnpayPayment']);
Route::post("getbankqr", [checkoutController::class, 'getBankQR']);
Route::get("/bank-return", [checkoutController::class, 'bankReturn']);
Route::post("send-order-confirmation", [MailController::class, 'sendOrderConfirmation']);

// Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//     ->middleware(['signed', 'throttle:6,1'])
//     ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::get("ticket/history", [TicketController::class, 'getHistory']);
    Route::prefix('user')->group(function () {
        Route::get("/logout", [LogoutController::class, "logout"]);
        Route::get('profile', [LoginController::class, 'getUser']);
        Route::put('cancel-booking', [checkoutController::class, 'cancelBooking']);
        Route::put("", [UserController::class, 'update']);
        Route::put("change-password", [UserController::class, 'changePassword']);
        Route::post("update", [UserController::class, 'update']);
        Route::post("comment", [CommentController::class, 'store']);
    });
});
