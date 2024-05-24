<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Récupère tous les posts avec leurs catégories, tags et utilisateur associés.
     *
     * @OA\Get(
     *     path="/api/posts",
     *     operationId="getAllPosts",
     *     tags={"Posts"},
     *     summary="Récupère la liste de tous les posts",
     *     description="Retourne une liste de tous les posts avec leurs détails",
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
     */
    public function getAllPosts()
    {
        try {
            $posts = Post::with(['categories', 'tags', 'user'])->get();

            return response()->json($posts);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erreur lors de la récupération des posts'], 500);
        }
    }

    /**
     * Crée un nouveau post avec validation des données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/posts",
     *     operationId="createPost",
     *     tags={"Posts"},
     *     summary="Crée un nouveau post",
     *     description="Enregistre un nouveau post avec les données fournies après validation.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du nouveau post",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post créé",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    function createPost(Request $request)
    {
        $post = new Post();

        // Validation des données requises pour la création d'un post
        $validateData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|numeric',
            'featured_image' => 'nullable|url',
            'published_at' => 'nullable|date'
        ]);

        try {
            // Attribution des données validées au nouveau post
            $post->title = $validateData['title'];
            $post->slug = Str::slug($validateData['title']);
            $post->content = $validateData['content'];
            $post->featured_image = $validateData['featured_image'];
            $post->status = $validateData['status'];
            $post->published_at = $validateData['published_at'];
            $post->user_id = $validateData['user_id'];

            $post->save();

            // Gestion des catégories associées au post
            $categoriesId = $request->categorie_id ?? [];

            // Création de nouvelles catégories si spécifiées
            if ($request->new_categorie) {
                $newCategories = is_array($request->new_categorie) ? $request->new_categorie : [$request->new_categorie];
                foreach ($newCategories as $newCat) {
                    $newcategorie = Categorie::firstOrCreate(['name' => $newCat], ['slug' => Str::slug($newCat)]);
                    $categoriesId[] = $newcategorie->id;
                }
            }
            $post->categories()->attach($categoriesId);

            // Gestion des tags associés au post
            $tagsId = $request->tag_id ?? [];

            // Création de nouveaux tags si spécifiés
            if ($request->new_tag) {
                $newTags = is_array($request->new_tag) ? $request->new_tag : [$request->new_tag];
                foreach ($newTags as $newTagValue) {
                    $newtag = Tag::firstOrCreate(['name' => $newTagValue], ['slug' => Str::slug($newTagValue)]);
                    $tagsId[] = $newtag->id;
                }
            }

            $post->tags()->attach($tagsId);

            return response()->json($post->load(['categories', 'tags', 'user']), 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => "L'enregistrement du nouveau post a échoué"], 500);
        }
    }

    /**
     * Récupère un post par son ID avec ses catégories, tags et utilisateur associés.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     operationId="getPostById",
     *     tags={"Posts"},
     *     summary="Récupère un post par son ID",
     *     description="Retourne un post et ses relations (catégories, tags, utilisateur) par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du post",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function getPostById(Post $post)
    {
        try {
            $postChargé = $post->load(['categories', 'tags', 'user']);
            return response()->json($postChargé);
        } catch (\Throwable $th) {
            return response()->json(['erreur' => 'Impossible de récupérer le post spécifié'], 500);
        }
    }

    /**
     * Récupère les catégories d'un post spécifique.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/posts/{id}/categories",
     *     operationId="getPostCategories",
     *     tags={"Posts"},
     *     summary="Récupère les catégories d'un post",
     *     description="Retourne les catégories associées à un post spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du post",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     * Récupère les tags d'un post spécifique.
     *
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/posts/{id}/tags",
     *     operationId="getPostTags",
     *     tags={"Posts"},
     *     summary="Récupère les tags d'un post",
     *     description="Retourne les tags associés à un post spécifique.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du post",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     */
    public function getPostTags(Post $post)
    {
        try {
            return response()->json($post->load('tags'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des tags du post', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Met à jour un post existant avec validation des données.
     *
     * @param \App\Models\Post $post
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     operationId="updatePost",
     *     tags={"Posts"},
     *     summary="Met à jour un post existant",
     *     description="Met à jour un post existant avec les données fournies après validation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du post",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données mises à jour du post",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mise à jour réussie",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function updatePost(Post $post, Request $request)
    {
        // Validation des données requises pour la mise à jour d'un post
        $validateData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|numeric',
            'featured_image' => 'nullable|url',
            'published_at' => 'nullable|date'
        ]);

        try {
            // Mise à jour des données du post
            $post->title = $validateData['title'];
            $post->slug = Str::slug($validateData['title']);
            $post->content = $validateData['content'];
            $post->featured_image = $validateData['featured_image'];
            $post->status = $validateData['status'];
            $post->published_at = $validateData['published_at'];
            $post->user_id = $validateData['user_id'];
            $post->update();

            // Gestion des catégories associées au post
            $categoriesId = $request->categorie_id ?? [];

            // Ajout ou mise à jour de nouvelles catégories si spécifiées
            if ($request->new_categorie) {
                $newCategories = is_array($request->new_categorie) ? $request->new_categorie : [$request->new_categorie];
                foreach ($newCategories as $newCat) {
                    $newcategorie = Categorie::firstOrCreate(['name' => $newCat], ['slug' => Str::slug($newCat)]);
                    $categoriesId[] = $newcategorie->id;
                }
            }

            $post->categories()->sync($categoriesId);

            $tagsId = $request->tag_id ?? [];

            if ($request->new_tag) {
                $newTags = is_array($request->new_tag) ? $request->new_tag : [$request->new_tag];
                foreach ($newTags as $newTagValue) {
                    $newtag = Tag::firstOrCreate(['name' => $newTagValue], ['slug' => Str::slug($newTagValue)]);
                    $tagsId[] = $newtag->id;
                }
            }

            $post->tags()->sync($tagsId);

            return response()->json($post->load(['categories', 'tags', 'user']), 200);
        } catch (\Throwable $th) {
            return response()->json(['erreur' => "La mise à jour du post a échoué", 'détails' => $th->getMessage()], 500);
        }
    }

    /**
     * Supprime un post et renvoie un message de succès.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     operationId="deletePost",
     *     tags={"Posts"},
     *     summary="Supprime un post",
     *     description="Supprime un post spécifié par son ID et renvoie un message de succès.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du post",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post supprimé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Le post a été supprimé avec succès")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function deletePost(Post $post)
    {
        try {
            $post->delete();
            return response()->json(["message" => "Le post a été supprimé avec succès"], 200);
        } catch (\Exception $e) {
            return response()->json(["erreur" => "Une erreur s'est produite lors de la suppression du post", "détails" => $e->getMessage()], 500);
        }
    }
}
