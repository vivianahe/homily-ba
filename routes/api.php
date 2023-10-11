<?php

use App\Http\Controllers\HomiliesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("homilies", HomiliesController::class);
Route::get('/homilies_desc', [HomiliesController::class, 'getDescHomily']);
Route::post('/contact', [HomiliesController::class, 'postFrmContact']);
Route::apiResource("users", UserController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource("prayers", PrayerController::class);


Route::middleware('auth:api')->group(function () {
    Route::apiResource("getHomilies", HomiliesController::class);
    Route::post('/addHomilies', [HomiliesController::class, 'store']);
});
