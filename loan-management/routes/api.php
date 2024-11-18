<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RepaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
])->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loan/{id}', [LoanController::class, 'show']);

     // Routes that require authentication
     Route::middleware('auth:sanctum')->group(function () {
        Route::post('/loans', [LoanController::class, 'store']);
        Route::put('/loan/edit/{id}', [LoanController::class, 'update']);
        Route::delete('/loan/{id}', [LoanController::class, 'destroy']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/repayment', [RepaymentController::class, 'store']);
    });

});
