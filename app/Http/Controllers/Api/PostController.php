<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function show()
    {
        $post = Post::all();

        return response()->json($post,200);
    }

    function index(Request $request)
    {
        $post = new Post();

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->content = $request->content;
        $post->featured_image = $request->featured_image;
        $post->status = $request->status;
        $post->published_at = $request->published_at;
        $post->user_id = $request->user_id;

        $post->save();

        return response()->json($post,201);
    }
}
