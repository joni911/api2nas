<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public endpoints
Route::get('/health', [ApiController::class, 'health']);
Route::post('/data2nas', [ApiController::class, 'storeData']);
Route::get('/data2nas/{id}', [ApiController::class, 'getData']);
