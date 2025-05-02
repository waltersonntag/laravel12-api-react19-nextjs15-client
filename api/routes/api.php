<?php

use App\Http\Controllers\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::withoutMiddleware('auth:sanctum')->group(function () {
    // Retrieve the limiter configuration for login attempts
    $limiter = config('fortify.limiters.login');

    // Route for user login
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),  // Only guests (non-authenticated users) are allowed
            $limiter ? 'throttle:'.$limiter : null,  // Throttle login attempts if limiter is configured
        ]));

    // Route for user registration
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest:'.config('fortify.guard'));  // Only guests (non-authenticated users) are allowed

    // Route for initiating password reset
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest:'.config('fortify.guard'))  // Only guests (non-authenticated users) are allowed
        ->name('password.email');  // Name for the route
});

Route::group(['middleware' => 'auth:sanctum'], function() {

    // Authentication routes
    Route::prefix('auth')->group(function () {

        // Retrieve the verification limiter configuration for verification attempts
        $verificationLimiter = config('fortify.limiters.verification', '6,1');

        // Route to resend email verification notification
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware([
                'throttle:'.$verificationLimiter // Throttle resend email attempts
            ]);
    });

    Route::post('/logout', [LogoutController::class, 'destroy']);

    // User routes
    Route::prefix('user')->middleware('verified')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });

//        Route::put('/update-password', [PasswordController::class, 'update']);
    });

});
