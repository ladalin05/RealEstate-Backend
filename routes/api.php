<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\FilterController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\DbMockup\EndpointController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/register-telegram', [UserAuthController::class, 'telegramRegister']);
    Route::post('/verify-otp', [UserAuthController::class, 'verifyOtp']);
    Route::get('/logout', [UserAuthController::class, 'logout']);
});

Route::prefix('cms')->group(function () {
    Route::get('/menu', [MainController::class, 'getMenu']);
    Route::get('/setting', [MainController::class, 'getSetting']);
    Route::get('/contact', [MainController::class, 'getContact']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('property')->group(function () {
    Route::get('/', [PropertyController::class, 'getProperty']);
    Route::get('/detail/{id}', [PropertyController::class, 'getPropertyDetails']);
    Route::get('/categories', [CategoryController::class, 'getPropertyCategories']);
    Route::post('/toggle-favourite', [PropertyController::class, 'toggleFavourite']);
    Route::get('/fillter-properties', [PropertyController::class, 'filterProperties']);
});


