<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilterController;


Route::middleware(['auth', 'abilities'])->group(function () {
    Route::get('/get-country', [FilterController::class, 'getCountry']);
    Route::post('/get-city', [FilterController::class, 'getCity']);
    Route::post('/get-district', [FilterController::class, 'getDistrict']);
    Route::post('/get-commune', [FilterController::class, 'getCommune']);
});