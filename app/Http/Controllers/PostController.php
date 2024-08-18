<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(): View
    {
        $post = Post::paginate(5);

        return View('home.home', [
            "posts" => $post
        ]);
    }

    public function createPost(Request $request)
    {
        try {
            $post = new Post();

            $post->title = $request->title;
            $post->slug = Str::slug($post->title);
            $post->content = $request->content;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;

            $post->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function showpost(String $slug): View
    {
        $post = Post::where("slug",$slug)->firstOrFail();

        return view('post', [
            "post" => $post
        ]);
    }

    public function updatePost(Request $request, Post $post)
    {
        try {
            $post->title = $request->title;
            $post->slug = Str::slug($post->title);
            $post->content = $request->content;
            $post->user_id = $request->user_id;
            $post->category_id = $request->category_id;

            $post->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deletePost(Post $post)
    {
        $post->delete();
    }
}
