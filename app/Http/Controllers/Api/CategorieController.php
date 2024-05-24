<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Contrôleur pour la gestion des catégories
 *
 * @OA\Tag(
 *     name="Catégories",
 *     description="Opérations sur les catégories"
 * )
 */
class CategorieController extends Controller
{
    /**
     * Récupère toutes les catégories avec leurs posts associés
     *
     * @OA\Get(
     *     path="/api/categories",
     *     operationId="getAllCategories",
     *     tags={"Catégories"},
     *     summary="Récupère toutes les catégories",
     *     description="Retourne une liste de toutes les catégories avec leurs posts associés.",
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/categories",
     *     operationId="createCategorie",
     *     tags={"Catégories"},
     *     summary="Crée une nouvelle catégorie",
     *     description="Enregistre une nouvelle catégorie avec les données fournies après validation.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la nouvelle catégorie",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nom de la catégorie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     operationId="getCategorieById",
     *     tags={"Catégories"},
     *     summary="Récupère une catégorie par son ID",
     *     description="Retourne une catégorie et ses posts associés par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     operationId="updateCategorie",
     *     tags={"Catégories"},
     *     summary="Met à jour une catégorie",
     *     description="Met à jour une catégorie existante avec les données fournies après validation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données mises à jour de la catégorie",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nom de la catégorie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie mise à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/categories/{id}/posts",
     *     operationId="getCategoryPosts",
     *     tags={"Catégories"},
     *     summary="Récupère tous les posts d'une catégorie",
     *     description="Retourne tous les posts associés à une catégorie spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
     * Supprime une catégorie et renvoie un message de succès
     *
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     operationId="deleteCategorie",
     *     tags={"Catégories"},
     *     summary="Supprime une catégorie",
     *     description="Supprime une catégorie et renvoie un message de succès.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la catégorie à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Message de succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
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
