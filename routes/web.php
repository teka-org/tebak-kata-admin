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

// Route::get('/', function () {
//     return view('index');
// });
Route::get('/', [\App\Http\Controllers\Controller::class, 'index']);

Route::get('/users', function () {
    return view('pages.users.users');
});

Route::get('/avatar', [\App\Http\Controllers\AvatarController::class, 'index']);
Route::get('/avatar/create', [\App\Http\Controllers\AvatarController::class, 'viewCreateAvatar']);
Route::post('/avatar/create', [\App\Http\Controllers\AvatarController::class, 'adminCreateAvatar']);
Route::delete('/avatar/delete/{id}', [\App\Http\Controllers\AvatarController::class, 'adminDeletevatar']);
Route::get('/avatar/edit/{id}', [\App\Http\Controllers\AvatarController::class, 'viewEditAvatar']);
Route::put('/avatar/edit/{id}', [\App\Http\Controllers\AvatarController::class, 'adminUpdateAvatar']);


Route::get('/quiz', [\App\Http\Controllers\QuizController::class, 'index']);
Route::get('/quiz/create', [\App\Http\Controllers\QuizController::class, 'viewCreateQuiz']);
Route::post('/quiz/create', [\App\Http\Controllers\QuizController::class, 'adminCreateQuiz']);
Route::delete('/quiz/delete/{id}', [\App\Http\Controllers\QuizController::class, 'adminDeleteQuiz']);
Route::get('/quiz/edit/{id}', [\App\Http\Controllers\QuizController::class, 'viewEditQuiz']);
Route::put('/quiz/edit/{id}', [\App\Http\Controllers\QuizController::class, 'adminUpdateQuiz']);


Route::get('/payment', function () {
    return view('pages.payment.payment');
});
