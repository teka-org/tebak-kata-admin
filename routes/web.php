<?php

use Illuminate\Support\Facades\Route;

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
    return view('layouts.master');
});

Route::get('/avatar', [\App\Http\Controllers\AvatarController::class, 'index']);
Route::delete('/avatar/delete/{id}', [\App\Http\Controllers\AvatarController::class, 'adminDeletevatar']);
Route::get('/avatar/edit/{id}', [\App\Http\Controllers\AvatarController::class, 'viewEditAvatar']);
Route::put('/avatar/edit/{id}', [\App\Http\Controllers\AvatarController::class, 'adminUpdateAvatar']);

Route::get('/users', function () {
    return view('pages.users.users');
});

Route::get('/quiz', function () {
    return view('pages.quiz.quiz');
});
Route::get('/payment', function () {
    return view('pages.payment.payment');
});
