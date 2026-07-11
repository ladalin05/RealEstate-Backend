<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\CMSController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\FilterController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\InteractionController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\DbMockup\EndpointController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/register-telegram', [UserAuthController::class, 'telegramRegister']);
    Route::post('/google-login', [UserAuthController::class, 'googleLogin']);
    Route::post('/verify-otp', [UserAuthController::class, 'verifyOtp']);
    Route::get('/logout', [UserAuthController::class, 'logout']);
});

Route::prefix('cms')->group(function () {
    Route::get('/home', [CMSController::class, 'getHomeData']);
    Route::get('/featured-properties', [CMSController::class, 'getFeaturedProperties']);
    Route::get('/user-dashboard', [CMSController::class, 'getUserDashboard']);
    Route::get('/menu', [CMSController::class, 'getMenu']);
    Route::get('/setting', [CMSController::class, 'getSetting']);
    Route::get('/contact', [CMSController::class, 'getContact']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('user-management')->group(function () {
    Route::get('/get-user', [UserAuthController::class, 'getInfo']);
    Route::get('/agents', [AgentController::class, 'getAllAgents']);
    Route::get('/agent-detail', [AgentController::class, 'getAgentDetail']);
    Route::get('/get-info', [UserAuthController::class, 'getInfo']);
    Route::put('update-info', [UserAuthController::class, 'updateInfo']);
});

Route::prefix('interaction')->group(function () {
    Route::post('schedule-tour', [InteractionController::class, 'scheduleTour']);
    Route::post('request-info',  [InteractionController::class, 'requestInfo']);
});

Route::prefix('property')->group(function () {
    Route::get('/', [PropertyController::class, 'getProperty']);
    Route::get('/detail', [PropertyController::class, 'getPropertyDetails']);
    Route::get('/categories', [CategoryController::class, 'getPropertyCategories']);
    Route::get('/favourite', [PropertyController::class, 'getFavouriteProperties']);
    Route::post('/toggle-favourite', [PropertyController::class, 'toggleFavourite']);
    Route::get('/fillter-properties', [PropertyController::class, 'filterProperties']);
    Route::get('/get-data-fillter', [PropertyController::class, 'getDataFillter']);
});

Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'getAllBlogs']);
    Route::get('/detail', [BlogController::class, 'getBlogDetail']);
});


