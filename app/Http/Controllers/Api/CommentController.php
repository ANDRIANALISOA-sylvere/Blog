<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des commentaires
 *
 * @OA\Tag(
 *     name="Commentaires",
 *     description="Opérations sur les commentaires"
 * )
 */
class CommentController extends Controller
{
    /**
     * Crée un commentaire avec validation des données
     *
     * @OA\Post(
     *     path="/api/comments",
     *     operationId="createComment",
     *     tags={"Commentaires"},
     *     summary="Crée un nouveau commentaire",
     *     description="Enregistre un nouveau commentaire avec les données fournies après validation.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du nouveau commentaire",
     *         @OA\JsonContent(
     *             required={"content", "post_id", "user_id"},
     *             @OA\Property(property="content", type="string", description="Contenu du commentaire"),
     *             @OA\Property(property="post_id", type="integer", description="ID du post associé"),
     *             @OA\Property(property="user_id", type="integer", description="ID de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commentaire créé",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:posts,id',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        try {
            $comment = Comment::create($validatedData);
            return response()->json($comment->load(['user', 'post']), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La création du commentaire a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère tous les commentaires d'un post
     *
     * @OA\Get(
     *     path="/api/posts/{post_id}/comments",
     *     operationId="getAllPostComments",
     *     tags={"Commentaires"},
     *     summary="Récupère tous les commentaires d'un post",
     *     description="Retourne tous les commentaires associés à un post spécifique.",
     *     @OA\Parameter(
     *         name="post_id",
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
     *            @OA\Items(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPostComment(Post $post)
    {
        try {
            $comments = $post->comments()->with(['post', 'user'])->get();
            return response()->json($comments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des commentaires', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère un commentaire par son ID
     *
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     operationId="getCommentById",
     *     tags={"Commentaires"},
     *     summary="Récupère un commentaire par son ID",
     *     description="Retourne un commentaire spécifique par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du commentaire",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentbyId(Comment $comment)
    {
        try {
            return response()->json($comment->load(['user', 'post']), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du commentaire', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Met à jour un commentaire existant avec validation des données
     *
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     operationId="updateComment",
     *     tags={"Commentaires"},
     *     summary="Met à jour un commentaire existant",
     *     description="Met à jour un commentaire avec les données fournies après validation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du commentaire à mettre à jour",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données mises à jour du commentaire",
     *         @OA\JsonContent(
     *             required={"content", "post_id", "user_id"},
     *             @OA\Property(property="content", type="string", description="Contenu du commentaire"),
     *             @OA\Property(property="post_id", type="integer", description="ID du post associé"),
     *             @OA\Property(property="user_id", type="integer", description="ID de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param \App\Models\Comment $comment
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateComment(Comment $comment, Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:posts,id',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        try {
            $comment->update($validatedData);
            return response()->json($comment->load(['post', 'user']), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La mise à jour du commentaire a échoué', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprime un commentaire et renvoie un message de succès
     *
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     operationId="deleteComment",
     *     tags={"Commentaires"},
     *     summary="Supprime un commentaire",
     *     description="Supprime un commentaire spécifié par son ID et renvoie un message de succès.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du commentaire à supprimer",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Le commentaire a été supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json(["message" => "Commentaire supprimé avec succès"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La suppression du commentaire a échoué', 'details' => $e->getMessage()], 500);
        }
    }
}
