<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Contrôleur pour la gestion des catégories
 */
class CategorieController extends Controller
{
    /**
     * Récupère toutes les catégories avec leurs posts associés
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories()
    {
        try {
            $categories = Categorie::with('posts')->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des catégories', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Crée une nouvelle catégorie avec validation des données
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCategorie(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        try {
            $categorie = new Categorie();
            $categorie->name = $validatedData['name'];
            $categorie->slug = Str::slug($validatedData['name']);
            $categorie->save();

            return response()->json($categorie->load('posts'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La création de la catégorie a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère une catégorie par son ID avec ses posts associés
     *
     * @param Categorie $categorie
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategorieById(Categorie $categorie)
    {
        try {
            return response()->json($categorie->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération de la catégorie', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Met à jour une catégorie existante avec validation des données
     *
     * @param Categorie $categorie
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCategorie(Categorie $categorie, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $categorie->name = $validatedData['name'];
            $categorie->slug = Str::slug($validatedData['name']);

            $categorie->update();

            return response()->json($categorie->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La mise à jour de la catégorie a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère tous les posts d'une catégorie spécifique
     *
     * @param Categorie $categorie
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryPosts(Categorie $categorie)
    {
        try {
            return response()->json($categorie->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des posts de la catégorie', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupére les catégories d'un post spécifique
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostCategories(Post $post)
    {
        try {
            return response()->json($post->load('categories'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des catégories du post', 'details' => $e->getMessage()], 500);
        }
    }


    /**
     * Supprime une catégorie et renvoie un message de succès
     *
     * @param Categorie $categorie
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategorie(Categorie $categorie)
    {
        try {
            $categorie->delete();

            return response()->json(["message" => "Catégorie a été supprimé avec succès"]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'La suppression de la catégorie a échoué', 'details' => $th->getMessage()], 500);
        }
    }
}
