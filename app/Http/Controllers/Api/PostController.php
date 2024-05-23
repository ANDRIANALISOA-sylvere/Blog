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
    // Récupère tous les posts avec leurs catégories, tags et utilisateur associés
    public function getAllPosts()
    {
        try {
            $posts = Post::with(['categories', 'tags', 'user'])->get();

            return response()->json($posts);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erreur lors de la récupération des posts'], 500);
        }
    }

    // Crée un nouveau post avec validation des données
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

    // Récupère un post par son ID avec ses catégories, tags et utilisateur associés
    public function getPostById(Post $post)
    {
        try {
            $postChargé = $post->load(['categories', 'tags', 'user']);
            return response()->json($postChargé);
        } catch (\Throwable $th) {
            return response()->json(['erreur' => 'Impossible de récupérer le post spécifié'], 500);
        }
    }

    // Met à jour un post existant avec validation des données
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

    // Supprime un post et renvoie un message de succès
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
