<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiManagementController;
use App\Http\Controllers\DataApiController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint
Route::get('/health', [ApiController::class, 'health']);

// Rute untuk manajemen user
Route::middleware(['auth'])->resource('user-management', UserManagementController::class)->names([
    'index' => 'user-management.index',
    'create' => 'user-management.create',
    'store' => 'user-management.store',
    'edit' => 'user-management.edit',
    'update' => 'user-management.update',
    'destroy' => 'user-management.destroy'
]);

// Rute untuk manajemen API key
Route::middleware(['auth'])->resource('api-management', ApiManagementController::class)->names([
    'index' => 'api-management.index',
    'create' => 'api-management.create',
    'store' => 'api-management.store',
    'show' => 'api-management.show',
    'edit' => 'api-management.edit',
    'update' => 'api-management.update',
    'destroy' => 'api-management.destroy'
]);

// Rute untuk manajemen data upload
Route::middleware(['auth'])->resource('data-api', DataApiController::class)->names([
    'index' => 'data-api.index',
    'show' => 'data-api.show',
    'destroy' => 'data-api.destroy'
]);

// Registrasi publik dimatikan. Akun hanya bisa dibuat oleh admin melalui User Management.
Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
