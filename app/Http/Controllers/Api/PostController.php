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
    public function getAllPosts()
    {
        $posts = Post::with(['categories', 'tags', 'user'])->get();

        return response()->json($posts, 200);
    }

    function createPost(Request $request)
    {
        $post = new Post();

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->featured_image = $request->featured_image;
        $post->status = $request->status;
        $post->published_at = $request->published_at;
        $post->user_id = $request->user_id;

        $post->save();

        $categoriesId = $request->categorie_id ?? [];

        if ($request->new_categorie) {
            if (is_array($request->new_categorie)) {
                foreach ($request->new_categorie as $newCat) {
                    $newcategorie = Categorie::firstOrCreate(['name' => $newCat], ['slug' => Str::slug($newCat)]);
                    $categoriesId[] = $newcategorie->id;
                }
            } else {
                $newcategorie = Categorie::firstOrCreate(['name' => $request->new_categorie], ['slug' => Str::slug($request->new_categorie)]);
                $categoriesId[] = $newcategorie->id;
            }
        }
        $post->categories()->attach($categoriesId);

        $tagsId = $request->tag_id ?? [];

        if ($request->new_tag) {
            if (is_array($request->new_tag)) {
                foreach ($request->new_tag as $newTagValue) {
                    $newtag = Tag::firstOrCreate(['name' => $newTagValue], ['slug' => Str::slug($newTagValue)]);
                    $tagsId[] = $newtag->id;
                }
            } else {
                $newtag = Tag::firstOrCreate(['name' => $request->new_tag], ['slug' => Str::slug($request->new_tag)]);
                $tagsId[] = $newtag->id;
            }
        }

        $post->tags()->attach($tagsId);

        return response()->json($post->load(['categories', 'tags', 'user']), 201);
    }

    public function getPostById(Post $post)
    {
        return response()->json($post->load(['categories', 'tags', 'user']));
    }

    public function updatePost(Post $post, Request $request)
    {
        $post->title = $request->title;
        $post->slug = Str::slug($request->slug);
        $post->content = $request->content;
        $post->featured_image = $request->featured_image;
        $post->status = $request->status;
        $post->published_at = $request->published_at;
        $post->user_id = $request->user_id;

        $post->update();

        $categoriesId = $request->categorie_id ?? [];

        if ($request->new_categorie) {
            if (is_array($request->new_categorie)) {
                foreach ($request->new_categorie as $newCat) {
                    $newcategorie = Categorie::firstOrCreate(['name' => $newCat], ['slug' => Str::slug($newCat)]);
                    $categoriesId[] = $newcategorie->id;
                }
            } else {
                $newcategorie = Categorie::firstOrCreate(['name' => $request->new_categorie], ['slug' => Str::slug($request->new_categorie)]);
                $categoriesId[] = $newcategorie->id;
            }
        }
        $post->categories()->sync($categoriesId);

        $tagsId = $request->tag_id ?? [];

        if ($request->new_tag) {
            if (is_array($request->new_tag)) {
                foreach ($request->new_tag as $newTagValue) {
                    $newtag = Tag::firstOrCreate(['name' => $newTagValue], ['slug' => Str::slug($newTagValue)]);
                    $tagsId[] = $newtag->id;
                }
            } else {
                $newtag = Tag::firstOrCreate(['name' => $request->new_tag], ['slug' => Str::slug($request->new_tag)]);
                $tagsId[] = $newtag->id;
            }
        }

        $post->tags()->sync($tagsId);

        return response()->json($post->load(['categories', 'tags', 'user']), 200);
    }

    public function deletePost(Post $post)
    {
        try {
            $post->delete();
            return response()->json(["message" => "Post supprimé avec succès"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Erreur lors de la suppression du post", "details" => $e->getMessage()], 500);
        }
    }
}
