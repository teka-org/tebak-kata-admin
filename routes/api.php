<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AvatarController

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);


Route::post('/avatar', [\App\Http\Controllers\AvatarController::class, 'CreateAvatar']);
Route::get('/avatar', [\App\Http\Controllers\AvatarController::class, 'getAllAvatar']);
Route::put('/avatar/{id}', [\App\Http\Controllers\AvatarController::class, 'updateAvatar']);
Route::delete('/avatar/{id}', [\App\Http\Controllers\AvatarController::class, 'deleteAvatar']);

Route::post('/diamond', [\App\Http\Controllers\DiamondController::class, 'createDiamond']);
Route::get('/diamond', [\App\Http\Controllers\DiamondController::class, 'getDiamond']);
Route::put('/diamond/{id}', [\App\Http\Controllers\DiamondController::class, 'updateDiamond']);
Route::delete('/diamond/{id}', [\App\Http\Controllers\DiamondController::class, 'deleteDiamond']);

