<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PostCategoriesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/blogs')->controller(BlogController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    // Route::post('/{id}', 'update');
});

Route::prefix('/categories')->controller(CategoriesController::class)->group(function () {
    // don't use this make a seeder
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('/comments')->controller(CommentController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    // Route::post('/{id}', 'update');
});

Route::prefix('/postcategories')->controller(PostCategoriesController::class)->group(function () {
    // don't use this use table relation
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
Route::prefix('/users')->controller(UserController::class)->group(function () {
    // don't use this use AuthController
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::post('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user', [AuthController::class, 'me']);
    Route::post( '/logout', [AuthController::class, 'logout']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::post('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});