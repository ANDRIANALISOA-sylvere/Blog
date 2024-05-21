<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function createComment(Request $request)
    {
        $comment = new Comment();

        $comment->content = $request->content;
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;

        $comment->save();

        return response()->json($comment->load(['user', 'post']), 201);
    }

    public function getAllPostComment(Post $post)
    {
        $comments = $post->comments()->with(['post', 'user'])->get();

        return response()->json($comments, 200);
    }

    public function getCommentbyId(Comment $comment)
    {
        return response()->json($comment->load(['user', 'post']), 200);
    }

    public function updateComment(Comment $comment, Request $request)
    {
        $comment->content = $request->content;
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;

        $comment->update();

        return response()->json($comment->load(['post', 'user']), 200);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return response()->json(["message" => "Commentaire supprimé avec succès"]);
    }
}
