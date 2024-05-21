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

//POST
Route::get("posts", [PostController::class, 'getAllPosts']);
Route::get("posts/{post}", [PostController::class, 'getPostById']);
Route::post("posts", [PostController::class, 'createPost']);
Route::put("posts/{post}",[PostController::class,'updatePost']);
Route::delete("posts/{post}",[PostController::class,'deletePost']);

//COMMENT
Route::get("comments", [CommentController::class, 'getAllComments']);
Route::post("comments", [CommentController::class, 'createComment']);

//CATEGORIE
Route::get("categories",[CategorieController::class,'getAllCategories']);
Route::post("categories",[CategorieController::class,'createCategorie']);

//TAG
Route::get("tags",[TagController::class,'getAllTags']);
Route::post("tags",[TagController::class,'createTag']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
