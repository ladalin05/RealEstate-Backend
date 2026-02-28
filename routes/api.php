<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\FilterController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\DbMockup\EndpointController;

// --------------------
// Public routes (no auth)
// --------------------

// --------------------
// Protected routes (require Sanctum token)
// --------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Add other protected routes here
});

// Auth routes (public)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/filter_data', [FilterController::class, 'filter_data']);

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'list']);
});

Route::prefix('menus')->group(function () {
    Route::get('/', [MainController::class, 'getMenu']);
});
Route::get('/get-contact', [MainController::class, 'getContact']);

Route::prefix('property')->group(function () {
    Route::get('/', [PropertyController::class, 'getProperty']);
    Route::get('/detail/{id}', [PropertyController::class, 'getPropertyDetails']);
    Route::post('/favourite', [PropertyController::class, 'is_favourit']);
});


