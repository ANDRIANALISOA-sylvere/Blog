<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Opérations sur les tags"
 * )
 */
class TagController extends Controller
{
    /**
     * Récupère tous les tags avec leurs posts associés.
     *
     * @OA\Get(
     *     path="/api/tags",
     *     operationId="getAllTags",
     *     tags={"Tags"},
     *     summary="Récupère tous les tags",
     *     description="Retourne une liste de tous les tags avec leurs posts associés.",
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTags()
    {
        try {
            $tags = Tag::with('posts')->get();
            return response()->json($tags, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des tags', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère un tag par son ID avec ses posts associés.
     *
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     operationId="getTagById",
     *     tags={"Tags"},
     *     summary="Récupère un tag par son ID",
     *     description="Retourne un tag et ses posts associés en fonction de l'ID spécifié.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du tag",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(ref="#/components/schemas/Tag")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagById(Tag $tag)
    {
        try {
            return response()->json($tag->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du tag', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Crée un nouveau tag avec validation des données de la requête.
     *
     * @OA\Post(
     *     path="/api/tags",
     *     operationId="createTag",
     *     tags={"Tags"},
     *     summary="Crée un nouveau tag",
     *     description="Enregistre un nouveau tag avec les données fournies après validation.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du nouveau tag",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nom du tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag créé",
     *         @OA\JsonContent(ref="#/components/schemas/Tag")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTag(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $tag = new Tag();
            $tag->name = $validatedData['name'];
            $tag->slug = Str::slug($validatedData['name']);
            $tag->save();

            return response()->json($tag->load('posts'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La création du tag a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Met à jour un tag existant avec validation des données.
     *
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     operationId="updateTag",
     *     tags={"Tags"},
     *     summary="Met à jour un tag existant",
     *     description="Met à jour un tag avec les données fournies après validation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du tag à mettre à jour",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données mises à jour pour le tag",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nom du tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Tag")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param Tag $tag
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTag(Tag $tag, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $tag->name = $validatedData['name'];
            $tag->slug = Str::slug($validatedData['name']);
            $tag->update();

            return response()->json($tag->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La mise à jour du tag a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère les posts associés à un tag spécifique.
     *
     * @OA\Get(
     *     path="/api/tags/{id}/posts",
     *     operationId="getTagPosts",
     *     tags={"Tags"},
     *     summary="Récupère les posts associés à un tag spécifique",
     *     description="Retourne tous les posts associés à un tag spécifié par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du tag",
     *         @OA\Schema(
     *             type="integer"
     *         )
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
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagPosts(Tag $tag)
    {
        try {
            return response()->json($tag->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des posts du tag', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprime un tag et renvoie un message de succès.
     *
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     operationId="deleteTag",
     *     tags={"Tags"},
     *     summary="Supprime un tag",
     *     description="Supprime un tag spécifié par son ID et renvoie un message de succès.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du tag à supprimer",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Le tag a été supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTag(Tag $tag)
    {
        try {
            $tag->delete();
            return response()->json(["message" => "Le tag a été supprimé avec succès"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La suppression du tag a échoué', 'details' => $e->getMessage()], 500);
        }
    }
}
