<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des commentaires
 */
class CommentController extends Controller
{
    /**
     * Crée un commentaire avec validation des données
     *
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
