<?php

use App\Http\Controllers\Api\AuthController;
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
| Ici, vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider et toutes seront
| assignées au groupe de middleware "api". Faites quelque chose de génial !
|
*/

// Routes pour le AuthController
Route::controller(AuthController::class)->group(function () {
    Route::post("register", 'register');
    Route::post("login", 'login');
    Route::post("logout", 'logout');
    Route::post('refresh', 'refresh');
});

// Routes pour le CategorieController
Route::controller(CategorieController::class)->group(function () {
    Route::get("categories", 'getAllCategories'); // Récupère toutes les catégories
    Route::get("categories/{categorie}", 'getCategorieById'); // Récupère une catégorie par son ID
    Route::get("categories/{categorie}/posts", 'getCategoryPosts'); // Récupérer tous les posts d'une catégorie spécifique
    Route::post("categories", 'createCategorie'); // Crée une nouvelle catégorie
    Route::put("categories/{categorie}", 'updateCategorie'); // Met à jour une catégorie
    Route::delete("categories/{categorie}", 'deleteCategorie'); // Supprime une catégorie
});

// Routes pour le CommentController
Route::controller(CommentController::class)->group(function () {
    Route::get("posts/{post}/comments", 'getAllPostComment'); // Récupère tous les commentaires d'un post
    Route::get("comments/{comment}", 'getCommentbyId'); // Récupère un commentaire par son ID
    Route::post("comments", 'createComment'); // Crée un nouveau commentaire
    Route::put("comments/{comment}", 'updateComment'); // Met à jour un commentaire
    Route::delete("comments/{comment}", 'deleteComment'); // Supprime un commentaire
});

// Routes pour le PostController
Route::controller(PostController::class)->group(function () {
    Route::get("posts", 'getAllPosts'); // Récupère tous les posts
    Route::get("users/{user}/posts", 'getUserPosts'); // Récupérer tous les posts d'un utilisateur spécifique
    Route::get("posts/{post}/categories", 'getPostCategories'); // Récupère les catégories d'un post spécifique
    Route::get("posts/{post}", 'getPostById'); // Récupère un post par son ID
    Route::get("posts/{post}/tags", 'getPostTags'); // Récupère les tags d'un post spécifique
    Route::post("posts", 'createPost'); // Crée un nouveau post
    Route::put("posts/{post}", 'updatePost'); // Met à jour un post
    Route::delete("posts/{post}", 'deletePost'); // Supprime un post
});

// Routes pour le TagController
Route::controller(TagController::class)->group(function () {
    Route::get("tags", 'getAllTags'); // Récupère tous les tags
    Route::get("tags/{tag}", 'getTagById'); // Récupère un tag par son ID
    Route::get("tags/{tag}/posts", 'getTagPosts'); // Récupérer tous les posts d'un tag spécifique
    Route::post("tags", 'createTag'); // Crée un nouveau tag
    Route::put("tags/{tag}", 'updateTag'); // Met à jour un tag
    Route::delete("tags/{tag}", 'deleteTag'); // Supprime un tag
});

