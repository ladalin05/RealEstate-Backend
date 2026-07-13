<?php

use App\Http\Controllers\Blog\BlogCategoryController;
use App\Http\Controllers\Blog\BlogPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Property\AmenityController;
use App\Http\Controllers\Property\FeatureController;
use App\Http\Controllers\Property\PropertyController;
use App\Http\Controllers\Property\PropertyCategoryController;
use App\Http\Controllers\Interaction\InquiryController;
use App\Http\Controllers\Interaction\ReviewController;
use App\Http\Controllers\Interaction\Requestinfocontroller;
use App\Http\Controllers\Interaction\Tourschedulecontroller;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Location\CommuneController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\DistrictController;
use App\Http\Controllers\Property\AreaController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\AgentController;
use App\Http\Controllers\UserManagement\AgencyController;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\InternalUserController;
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
            'prefix' => 'internal-users',
            'as' => 'internal-users.'
        ], function () {
            Route::get('/', [InternalUserController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [InternalUserController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [InternalUserController::class, 'update'])->name('edit');
            Route::get('/view/{id}', [InternalUserController::class, 'view'])->name('view');
            Route::delete('/delete/{id}', [InternalUserController::class, 'delete'])->name('delete');

            // Staff/admin control actions
            Route::post('/toggle-status/{id}', [InternalUserController::class, 'toggleStatus'])->name('toggleStatus');
            Route::post('/send-reset-link/{id}', [InternalUserController::class, 'sendResetLink'])->name('sendResetLink');
            Route::post('/change-role/{id}', [InternalUserController::class, 'changeRole'])->name('changeRole');
            Route::post('/assign-permissions/{id}', [InternalUserController::class, 'assignPermissions'])->name('assignPermissions');
            Route::get('/activity-log/{id}', [InternalUserController::class, 'activityLog'])->name('activityLog');
        });

        Route::group([
            'prefix' => 'users',
            'as' => 'users.'
        ], function () {
            Route::get('/', [UsersController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [UsersController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [UsersController::class, 'edit'])->name('edit');
            Route::get('/view/{id}', [UsersController::class, 'view'])->name('view');
            Route::delete('/delete/{id}', [UsersController::class, 'delete'])->name('delete');

            Route::post('/toggle-status/{id}', [UsersController::class, 'toggleStatus'])->name('toggleStatus');
            Route::post('/send-reset-link/{id}', [UsersController::class, 'sendResetLink'])->name('sendResetLink');
            Route::post('/change-role/{id}', [UsersController::class, 'changeRole'])->name('changeRole');
        });
        
        Route::group([
            'prefix' => 'roles',
            'as' => 'roles.'
        ], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [RoleController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [RoleController::class, 'update'])->name('edit');
            Route::delete('/delete', [RoleController::class, 'delete'])->name('delete');
        });
        
        Route::group([
            'prefix' => 'agents',
            'as' => 'agents.'
        ], function () {
            Route::get('/', [AgentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [AgentController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/edit/{id}', [AgentController::class, 'edit'])->name('edit');
            Route::get('/view/{id}', [AgentController::class, 'view'])->name('view');
            Route::delete('/delete/{id}', [AgentController::class, 'delete'])->name('delete');

            // Admin control actions (privacy-respecting)
            Route::post('/toggle-status/{id}', [AgentController::class, 'toggleStatus'])->name('toggleStatus');
            Route::post('/send-reset-link/{id}', [AgentController::class, 'sendResetLink'])->name('sendResetLink');
            Route::post('/verify-license/{id}', [AgentController::class, 'verifyLicense'])->name('verifyLicense');
            Route::post('/toggle-featured/{id}', [AgentController::class, 'toggleFeatured'])->name('toggleFeatured');
            Route::get('/properties/{id}', [AgentController::class, 'properties'])->name('properties');
        });

        Route::group([
            'prefix' => 'permissions',
            'as' => 'permissions.'
        ], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PermissionController::class, 'add'])->name('add');
            Route::match(['get', 'post'], '/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::delete('/delete', [PermissionController::class, 'delete'])->name('delete');
        });
    });

    // Properties
    Route::group([
        'prefix' => 'property',
        'as' => 'property.'
    ], function () {
        Route::group([
            'prefix' => 'properties',
            'as'     => 'properties.'
        ], function () {
            Route::get('/',                         [PropertyController::class, 'index'])->name('index');
            Route::get('/show',                     [PropertyController::class, 'showProperty'])->name('show');
            Route::match(['get', 'post'], '/add',   [PropertyController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit',  [PropertyController::class, 'update'])->name('edit');
            Route::get('/delete',                   [PropertyController::class, 'delete'])->name('deleted');
        });

        Route::group([
            'prefix' => 'categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [PropertyCategoryController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [PropertyCategoryController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [PropertyCategoryController::class, 'update'])->name('edit');
            Route::get('/delete', [PropertyCategoryController::class, 'delete'])->name('deleted');
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
        Route::group([
            'prefix' => 'areas',
            'as'     => 'areas.'
        ], function () {
            Route::get('/',        [AreaController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/add', [AreaController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/edit', [AreaController::class, 'update'])->name('edit');
            Route::get('/delete',   [AreaController::class, 'delete'])->name('deleted');
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

    // Interaction
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
            'prefix' => 'request-infos',
            'as' => 'request-infos.'
        ], function () {
            Route::get('/', [RequestInfoController::class, 'index'])->name('index');
            Route::get('/show', [RequestInfoController::class, 'show'])->name('show');
            Route::patch('/read', [RequestInfoController::class, 'markAsRead'])->name('read');
            Route::patch('/reply', [RequestInfoController::class, 'reply'])->name('reply');
            Route::patch('/close', [RequestInfoController::class, 'close'])->name('close');
            Route::delete('/delete', [RequestInfoController::class, 'destroy'])->name('destroy');
        });

        Route::group([
            'prefix' => 'tour-schedules',
            'as' => 'tour-schedules.'
        ], function () {
            Route::get('/', [TourScheduleController::class, 'index'])->name('index');
            Route::get('tour-schedules/{id}', [TourScheduleController::class, 'show'])->name('show');
            Route::patch('tour-schedules/{id}/confirm', [TourScheduleController::class, 'confirm'])->name('confirm');
            Route::patch('tour-schedules/{id}/reject', [TourScheduleController::class, 'reject'])->name('reject');
            Route::get('/delete/{id}', [TourScheduleController::class, 'destroy'])->name('destroy');
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

    // Blog
    Route::group([
        'prefix' => 'blogs',
        'as' => 'blogs.'
    ], function () {
        Route::group([
            'prefix' => 'posts',
            'as' => 'posts.'
        ], function () {
            Route::get('/', [BlogPostController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [BlogPostController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [BlogPostController::class, 'update'])->name('edit');
            Route::get('/delete/{id}', [BlogPostController::class, 'delete'])->name('deleted');
        });
        Route::group([
            'prefix' => 'categories',
            'as' => 'categories.'
        ], function () {
            Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/create', [BlogCategoryController::class, 'create'])->name('add');
            Route::match(['get', 'post'], '/update', [BlogCategoryController::class, 'update'])->name('edit');
            Route::get('/delete/{id}', [BlogCategoryController::class, 'delete'])->name('deleted');
        });
    });

    //Report
    Route::group([
        'prefix' => 'reports',
        'as' => 'reports.'
    ], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::patch('/{report}/toggle', [ReportController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
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
            Route::match(['get', 'post'], '/create', [SettingsController::class, 'general_setting_create'])->name('create');
            Route::match(['get', 'post'], '/update', [SettingsController::class, 'general_setting_update'])->name('update');
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

    // File Upload & Delete
    Route::post('/uploads', [UploadController::class, 'store'])->name('uploads.store');
    Route::delete('/uploads', [UploadController::class, 'destroy'])->name('uploads.destroy');

    //CMD Setting 
    Route::group([
        'prefix' => 'cms',
        'as' => 'cms.'
    ], function () {
        Route::group([
            'prefix' => 'hero',
            'as' => 'hero.',
        ], function () {
            Route::get('/', [CMSController::class, 'cmsHero'])->name('index');
            Route::match(['get', 'post'], '/create', [CMSController::class, 'CmsHeroCreate'])->name('create');
            Route::match(['get', 'post'], '/update', [CMSController::class, 'CmsHeroUpdate'])->name('update');
        });
        
        Route::group([
            'prefix' => 'pages',
            'as' => 'pages.',
        ], function () {
            Route::get('/', [CMSController::class, 'pages'])->name('index');
            Route::match(['get', 'post'], '/create', [CMSController::class, 'general_setting_create'])->name('create');
            Route::match(['get', 'post'], '/update', [CMSController::class, 'general_setting_update'])->name('update');
        });
    });

});

require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/filtter.php';
