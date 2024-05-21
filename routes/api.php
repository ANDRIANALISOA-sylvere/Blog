<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
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

Route::get("post", [PostController::class, 'show']);
Route::post("post", [PostController::class, 'index']);

Route::get("comment", [CommentController::class, 'show']);
Route::post("comment", [CommentController::class, 'index']);

Route::get("categorie",[CategorieController::class,'show']);
Route::post("categorie",[CategorieController::class,'index']);

Route::get("tag",[TagController::class,'show']);
Route::post("tag",[TagController::class,'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
