<?php

use App\Http\Controllers\Blog\PostCategoryController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Property\AmenityController;
use App\Http\Controllers\Property\FeatureController;
use App\Http\Controllers\Property\PropertyController;
use App\Http\Controllers\Property\PropertyTypeController;
use App\Http\Controllers\Interaction\InquiryController;
use App\Http\Controllers\Interaction\ReviewController;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Location\CommuneController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\DistrictController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\AgentController;
use App\Http\Controllers\UserManagement\AgencyController;
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
    
    //User Management
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
            Route::get('/', [AgentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [AgentController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [AgentController::class, 'update'])->name('edit');
            Route::delete('/delete', [AgentController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'agencies',
            'as' => 'agencies.'
        ], function () {
            Route::get('/', [AgencyController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [AgencyController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [AgencyController::class, 'update'])->name('edit');
            Route::delete('/delete', [AgencyController::class, 'delete'])->name('delete');
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
            Route::match(['get', 'post'], '/edit', [PropertyController::class, 'update'])->name('edit');
            Route::get('/delete', [PropertyController::class, 'delete'])->name('deleted');
            Route::get('/search', [PropertyController::class, 'property_filter'])->name('filter');
        });

        Route::group([
            'prefix' => 'types',
            'as' => 'types.'
        ], function () {
            Route::get('/', [PropertyTypeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PropertyTypeController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [PropertyTypeController::class, 'update'])->name('edit');
            Route::get('/delete', [PropertyTypeController::class, 'delete'])->name('deleted');
        });

        Route::group([
            'prefix' => 'amenities',
            'as' => 'amenities.'
        ], function () {
            Route::get('/', [AmenityController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [AmenityController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [AmenityController::class, 'update'])->name('edit');
            Route::get('/delete', [AmenityController::class, 'delete'])->name('deleted');
        });

        Route::group([
            'prefix' => 'features',
            'as' => 'features.'
        ], function () {
            Route::get('/', [FeatureController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [FeatureController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [FeatureController::class, 'update'])->name('edit');
            Route::get('/delete', [FeatureController::class, 'delete'])->name('deleted');
        });
    });

    // Locations
    Route::group([
        'prefix' => 'location',
        'as' => 'location.'
    ], function () {
        Route::group([
            'prefix' => 'countries',
            'as' => 'countries.'
        ], function () {
            Route::get('/', [CountryController::class, 'index'])->name('index');
            Route::match(['get', 'post'],'/add', [CountryController::class, 'create'])->name('add');
            Route::match(['get', 'post'],'/edit', [CountryController::class, 'update'])->name('edit');
            Route::get('/delete', [CountryController::class, 'destroy'])->name('deleted');
        });
        Route::group([
            'prefix' => 'cities',
            'as' => 'cities.'
        ], function () {
            Route::get('/', [CityController::class, 'index'])->name('index');
            Route::match(['get', 'post'],'/add', [CityController::class, 'create'])->name('add');
            Route::match(['get', 'post'],'/edit', [CityController::class, 'update'])->name('edit');
            Route::get('/delete', [CityController::class, 'destroy'])->name('deleted');
        });
        Route::group([
            'prefix' => 'districts',
            'as' => 'districts.'
        ], function () {
            Route::get('/', [DistrictController::class, 'index'])->name('index');
            Route::match(['get', 'post'],'/add', [DistrictController::class, 'create'])->name('add');
            Route::match(['get', 'post'],'/edit', [DistrictController::class, 'update'])->name('edit');
            Route::get('/delete', [DistrictController::class, 'destroy'])->name('deleted');
        });
        Route::group([
            'prefix' => 'communes',
            'as' => 'communes.'
        ], function () {
            Route::get('/', [CommuneController::class, 'index'])->name('index');
            Route::match(['get', 'post'],'/add', [CommuneController::class, 'create'])->name('add');
            Route::match(['get', 'post'],'/edit', [CommuneController::class, 'update'])->name('edit');
            Route::get('/delete', [CommuneController::class, 'destroy'])->name('deleted');
        });
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
            Route::get('/', [InquiryController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [InquiryController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [InquiryController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [InquiryController::class, 'destroy'])->name('deleted');
        });

        Route::group([
            'prefix' => 'reviews',
            'as' => 'reviews.'
        ], function () {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [ReviewController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [ReviewController::class, 'edit'])->name('edit');
            Route::get('/delete/{id}', [ReviewController::class, 'destroy'])->name('deleted');
        });
    });

    //Report
    Route::group([
        'prefix' => 'reports',
        'as' => 'reports.'
    ], function () {
        Route::get('/property-report', [ReportsController::class, 'property_report'])->name('prop-report');
        Route::get('/sales-report', [ReportsController::class, 'sale_report'])->name('sale-report');
        Route::get('/agent-report', [ReportsController::class, 'agent_report'])->name('agent-report');
        Route::get('/investment-report', [ReportsController::class, 'invest_report'])->name('invest-report');
        Route::get('/filter', [ReportsController::class, 'report_filter'])->name('filter');
    });

    // Blog
    Route::group([
        'prefix' => 'blogs',
        'as' => 'blogs.'
    ], function () {
        Route::group([
            'prefix' => 'posts',
            'as' => 'posts.'
        ], function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [PostController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [PostController::class, 'update'])->name('edit');
        });
        Route::group([
            'prefix' => 'categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [PostCategoryController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [PostCategoryController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [PostCategoryController::class, 'update'])->name('edit');
        });
    });
    
    // Web Settings
    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::group([
            'prefix' => 'settings',
            'as' => 'settings.',
        ], function () {
            Route::get('/', [SettingsController::class, 'general_settings'])->name('index');
            Route::match(['get', 'post'], '/create', [SettingsController::class, 'create'])->name('create');
            Route::match(['get', 'post'], '/update', [SettingsController::class, 'update'])->name('update');
        });
        Route::group([
            'prefix' => 'banners',
            'as' => 'banners.',
        ], function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [SettingsController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [SettingsController::class, 'update'])->name('edit');
        });

    });
});

require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/filtter.php';
