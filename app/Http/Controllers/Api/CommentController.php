<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function show()
    {
        $comment = Comment::with(['user','post'])->get();

        return response()->json($comment, 200);
    }

    public function index(Request $request)
    {
        $comment = new Comment();

        $comment->content = $request->content;
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;

        $comment->save();

        return response()->json($comment, 201);
    }
}
