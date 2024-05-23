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
| Ici, vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider et toutes seront
| assignées au groupe de middleware "api". Faites quelque chose de génial !
|
*/

// Routes pour les posts
Route::get("posts", [PostController::class, 'getAllPosts']); // Récupère tous les posts
Route::get("posts/{post}", [PostController::class, 'getPostById']); // Récupère un post par son ID
Route::post("posts", [PostController::class, 'createPost']); // Crée un nouveau post
Route::put("posts/{post}", [PostController::class, 'updatePost']); // Met à jour un post
Route::delete("posts/{post}", [PostController::class, 'deletePost']); // Supprime un post

//Routes pour les users
Route::get("users/{user}/posts", [PostController::class, 'getUserPosts']); // Récupérer tous les posts d'un utilisateur spécifique

// Routes pour les commentaires
Route::get("posts/{post}/comments", [CommentController::class, 'getAllPostComment']); // Récupère tous les commentaires d'un post
Route::get("comments/{comment}", [CommentController::class, 'getCommentbyId']); // Récupère un commentaire par son ID
Route::post("comments", [CommentController::class, 'createComment']); // Crée un nouveau commentaire
Route::put("comments/{comment}", [CommentController::class, 'updateComment']); // Met à jour un commentaire
Route::delete("comments/{comment}", [CommentController::class, 'deleteComment']); // Supprime un commentaire

// Routes pour les catégories
Route::get("categories", [CategorieController::class, 'getAllCategories']); // Récupère toutes les catégories
Route::get("categories/{categorie}", [CategorieController::class, 'getCategorieById']); // Récupère une catégorie par son ID
Route::get("categories/{categorie}/posts", [CategorieController::class, 'getCategoryPosts']); // Récupérer tous les posts d'une catégorie spécifique
Route::get("posts/{post}/categories", [PostController::class, 'getPostCategories']); // Récupère les catégories d'un post spécifique
Route::post("categories", [CategorieController::class, 'createCategorie']); // Crée une nouvelle catégorie
Route::put("categories/{categorie}", [CategorieController::class, 'updateCategorie']); // Met à jour une catégorie
Route::delete("categories/{categorie}", [CategorieController::class, 'deleteCategorie']); // Supprime une catégorie

// Routes pour les tags
Route::get("tags", [TagController::class, 'getAllTags']); // Récupère tous les tags
Route::get("tags/{tag}", [TagController::class, 'getTagById']); // Récupère un tag par son ID
Route::get("tags/{tag}/posts", [TagController::class, 'getTagPosts']); // Récupérer tous les posts d'un tag spécifique
Route::get("posts/{post}/tags", [PostController::class, 'getPostTags']); // Récupère les tags d'un post spécifique
Route::post("tags", [TagController::class, 'createTag']); // Crée un nouveau tag
Route::put("tags/{tag}", [TagController::class, 'updateTag']); // Met à jour un tag
Route::delete("tags/{tag}", [TagController::class, 'deleteTag']); // Supprime un tag

// Route pour récupérer les informations de l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

