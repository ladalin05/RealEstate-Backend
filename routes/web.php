<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'abilities'])->group(function () {
    
    Route::group([
        'prefix' => 'users-management',
        'as' => 'users-management.'
    ], function () {
        Route::group([
            'prefix' => 'users',
            'as' => 'users.'
        ], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/add', [UserController::class, 'add'])->name('add');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('/save/{id?}', [UserController::class, 'save'])->name('save');
            Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');

        });
        
        Route::group([
            'prefix' => 'roles',
            'as' => 'roles.'
        ], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/add', [RoleController::class, 'add'])->name('add');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::post('/save/{id?}', [RoleController::class, 'save'])->name('save');
            Route::delete('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'agents',
            'as' => 'agents.'
        ], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/add', [RoleController::class, 'add'])->name('add');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::post('/save/{id?}', [RoleController::class, 'save'])->name('save');
            Route::delete('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        });
    });

    // Properties
    Route::group([
        'prefix' => 'property',
        'as' => 'property.'
    ], function () {
        Route::group([
            'prefix' => 'properties',
            'as' => 'properties.'
        ], function () {
            Route::get('/', [PropertyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PropertyController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [PropertyController::class, 'destroy'])->name('deleted');
            Route::get('/search', [PropertyController::class, 'property_filter'])->name('filter');
        });

        Route::group([
            'prefix' => 'types',
            'as' => 'types.'
        ], function () {
            Route::get('/', [TypeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'add', [TypeController::class, 'create'])->name('add');
            Route::match(['get', 'post'], 'edit/{id}', [TypeController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [TypeController::class, 'delete'])->name('deleted');
        });

        Route::group([
            'prefix' => 'amenities',
            'as' => 'amenities.'
        ], function () {
            Route::get('/', [TypeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'add', [TypeController::class, 'create'])->name('add');
            Route::match(['get', 'post'], 'edit/{id}', [TypeController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [TypeController::class, 'delete'])->name('deleted');
        });

        Route::group([
            'prefix' => 'features',
            'as' => 'features.'
        ], function () {
            Route::get('/', [TypeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'add', [TypeController::class, 'create'])->name('add');
            Route::match(['get', 'post'], 'edit/{id}', [TypeController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [TypeController::class, 'delete'])->name('deleted');
        });
    });

    // Locations
    Route::group([
        'prefix' => 'location',
        'as' => 'location.'
    ], function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::match(['get', 'post'],'/add', [LocationController::class, 'store'])->name('add');
        Route::match(['get', 'post'],'/edit/{id}', [LocationController::class, 'update'])->name('edit');
        Route::get('/delete/{id}', [LocationController::class, 'destroy'])->name('deleted');
    });

    // Customer Interaction
    Route::group([
        'prefix' => 'interaction',
        'as' => 'interaction.'
    ], function () {
        Route::group([
            'prefix' => 'inquiries',
            'as' => 'inquiries.'
        ], function () {
            Route::get('/', [PropertyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PropertyController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [PropertyController::class, 'destroy'])->name('deleted');
        });

        Route::group([
            'prefix' => 'reviews',
            'as' => 'reviews.'
        ], function () {
            Route::get('/', [PropertyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PropertyController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [PropertyController::class, 'destroy'])->name('deleted');
        });
    });

    Route::group([
        'prefix' => 'reports',
        'as' => 'reports.'
    ], function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/filter', [ReportsController::class, 'report_filter'])->name('filter');
    });

    // Blog
    Route::group([
        'prefix' => 'posts',
        'as' => 'posts.'
    ], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/filter', [TransactionController::class, 'transaction_filter'])->name('filter');
        Route::post('/export', [TransactionController::class, 'transaction_export'])->name('export');
        Route::group([
            'prefix' => 'categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [SettingsController::class, 'general_settings'])->name('index');
            Route::post('/create', [SettingsController::class, 'general_setting_create'])->name('create');
            Route::post('/update', [SettingsController::class, 'general_setting_update'])->name('update');
    });
    });
    
    // Web Settings
    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', [SettingsController::class, 'content_page'])->name('index');
        Route::group([
            'prefix' => 'banners',
            'as' => 'banners.',
        ], function () {
            Route::get('/', [SettingsController::class, 'content_page'])->name('index');
            Route::match(['get', 'post'], '/create', [SettingsController::class, 'content_page_create'])->name('create');
            Route::match(['get', 'post'], '/update', [SettingsController::class, 'content_page_update'])->name('update');
        });

    });
});

require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
