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
    });

    // Type
    Route::group([
        'prefix' => 'type',
        'as' => 'type.'
    ], function () {
        Route::get('/', [TypeController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'add', [TypeController::class, 'create'])->name('add');
        Route::match(['get', 'post'], 'edit/{id}', [TypeController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [TypeController::class, 'delete'])->name('deleted');
    });

    // Types
    Route::group([
        'prefix' => 'types',
        'as' => 'types.'
    ], function () {
        Route::get('/', [TypesController::class, 'types'])->name('index');
        Route::get('types/{slug}/{id}', [TypesController::class, 'types_property'])->name('property');
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

    // Properties
    Route::group([
        'prefix' => 'property',
        'as' => 'property.'
    ], function () {
        Route::get('/', [PropertyController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [PropertyController::class, 'create'])->name('add');
        Route::match(['get', 'post'], '/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [PropertyController::class, 'destroy'])->name('deleted');
        Route::get('/search', [PropertyController::class, 'property_filter'])->name('filter');
    });

    Route::group([
        'prefix' => 'reports',
        'as' => 'reports.'
    ], function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/filter', [ReportsController::class, 'report_filter'])->name('filter');
    });

    // Sub Admins
    Route::group([
        'prefix' => 'sub_admin',
        'as' => 'sub_admin.'
    ], function () {
        Route::get('/', [UserController::class, 'admin_list'])->name('index');
        Route::match(['get', 'post'] ,'/add', [UserController::class, 'admin_create'])->name('add');
        Route::match(['get', 'post'] ,'/edit/{id}', [UserController::class, 'admin_update'])->name('edit');
        Route::delete('/delete/{id}', [UserController::class, 'admin_delete'])->name('delete');
        Route::get('/filter', [UserController::class, 'filter_sub_admin'])->name('filter');
    });

    // Transactions
    Route::group([
        'prefix' => 'transactions',
        'as' => 'transactions.'
    ], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/filter', [TransactionController::class, 'transaction_filter'])->name('filter');
        Route::post('/export', [TransactionController::class, 'transaction_export'])->name('export');
    });
    
    // Web Settings
    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::group([
            'prefix' => 'general',
            'as' => 'general.'
        ], function () {
                Route::get('/', [SettingsController::class, 'general_settings'])->name('index');
                Route::post('/create', [SettingsController::class, 'general_setting_create'])->name('create');
                Route::post('/update', [SettingsController::class, 'general_setting_update'])->name('update');
        });

        Route::get('/email', [SettingsController::class, 'email_settings'])->name('email');
        Route::post('/email', [SettingsController::class, 'update_email_settings'])->name('email.update');

        Route::get('/test_smtp', [SettingsController::class, 'test_smtp_settings'])->name('test_smtp');
        Route::get('/social_login', [SettingsController::class, 'social_login_settings'])->name('social_login');
        Route::post('/social_login', [SettingsController::class, 'update_social_login_settings'])->name('social_login.update');

        Route::get('/recaptcha', [SettingsController::class, 'recaptcha_settings'])->name('recaptcha');
        Route::post('/recaptcha', [SettingsController::class, 'update_recaptcha_settings'])->name('recaptcha.update');

        Route::get('/web_ads', [SettingsController::class, 'web_ads_settings'])->name('web_ads');
        Route::post('/web_ads', [SettingsController::class, 'update_web_ads_settings'])->name('web_ads.update');

        Route::group([
            'prefix' => 'content_page',
            'as' => 'content_page.',
        ], function () {
            Route::get('/', [SettingsController::class, 'content_page'])->name('index');
            Route::match(['get', 'post'], '/create', [SettingsController::class, 'content_page_create'])->name('create');
            Route::match(['get', 'post'], '/update', [SettingsController::class, 'content_page_update'])->name('update');
        });

    });
});

require __DIR__.'/auth.php';
require __DIR__ . '/admin.php';
