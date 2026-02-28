<?php

use App\Mail\Verification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\DbMockup\ProjectController;
use App\Http\Controllers\DbMockup\EndpointController;

 // clear cache
 Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return redirect()->back();
})->name('clear-cache');


Route::get('/send-test-email', function () {
    Mail::to('chamnab.roeun.rc@gmail.com', 'CHAMNAB')->send(new Verification());
});

Route::get('change-language/{lang}', [LanguageController::class, 'index'])->name('lang');

Route::middleware(['auth', 'abilities'])->group(function () {
    // prefix: settings/users-management
    Route::get('settings/users-management/users', [UserController::class, 'index'])->name('settings.users-management.users.index');
    Route::get('settings/users-management/users/add', [UserController::class, 'add'])->name('settings.users-management.users.add');
    Route::get('settings/users-management/users/edit/{id}', [UserController::class, 'edit'])->name('settings.users-management.users.edit');
    Route::post('settings/users-management/users/save/{id?}', [UserController::class, 'save'])->name('settings.users-management.users.save');
    Route::delete('settings/users-management/users/delete/{id}', [UserController::class, 'delete'])->name('settings.users-management.users.delete');
    Route::match(['get', 'post'], 'settings/users-management/users/permission/{id}', [UserController::class, 'permission'])->name('settings.users-management.users.permission');
    Route::match(['get', 'post'], 'settings/users-management/users/change-password/{id}', [UserController::class, 'changePassword'])->name('settings.users-management.users.change-password');
    Route::get('settings/users-management/users/account', [UserController::class, 'account'])->name('settings.users-management.users.account');
    Route::get('settings/users-management/users/account/change-password', [AccountController::class, 'changePassword'])->name('settings.users-management.users.account.change-password');
    Route::get('settings/users-management/roles', [RoleController::class, 'index'])->name('settings.users-management.roles.index');
    Route::get('settings/users-management/roles/add', [RoleController::class, 'add'])->name('settings.users-management.roles.add');
    Route::get('settings/users-management/roles/edit/{id}', [RoleController::class, 'edit'])->name('settings.users-management.roles.edit');
    Route::post('settings/users-management/roles/save/{id?}', [RoleController::class, 'save'])->name('settings.users-management.roles.save');
    Route::delete('settings/users-management/roles/detele/{id}', [RoleController::class, 'detele'])->name('settings.users-management.roles.delete');

    // 
    Route::get('dbmockup/projects', [ProjectController::class, 'index'])->name('dbmockup.projects.index');
    Route::get('dbmockup/projects/add', [ProjectController::class, 'add'])->name('dbmockup.projects.add');
    Route::get('dbmockup/projects/edit/{id}', [ProjectController::class, 'edit'])->name('dbmockup.projects.edit');
    Route::post('dbmockup/projects/save/{id?}', [ProjectController::class, 'save'])->name('dbmockup.projects.save');
    Route::delete('dbmockup/projects/detele/{id}', [ProjectController::class, 'detele'])->name('dbmockup.projects.delete');


    Route::get('dbmockup/endpoint', [EndpointController::class, 'index'])->name('dbmockup.endpoint.index');
    Route::get('dbmockup/endpoint/add', [EndpointController::class, 'add'])->name('dbmockup.endpoint.add');
    Route::get('dbmockup/endpoint/edit/{id}', [EndpointController::class, 'edit'])->name('dbmockup.endpoint.edit');
    Route::post('dbmockup/endpoint/save/{id?}', [EndpointController::class, 'save'])->name('dbmockup.endpoint.save');
    Route::delete('dbmockup/endpoint/detele/{id}', [EndpointController::class, 'detele'])->name('dbmockup.endpoint.delete');
});