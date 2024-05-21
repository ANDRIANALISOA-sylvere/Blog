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
    function show()
    {
        $post = Post::with(['categories','tags'])->get();

        return response()->json($post, 200);
    }

    function index(Request $request)
    {
        $post = new Post();

        $post->title = $request->title;
        $post->slug = Str::slug($request->slug);
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

        return response()->json($post->load(['categories', 'tags']), 201);
    }
}
