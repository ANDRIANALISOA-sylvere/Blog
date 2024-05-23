<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Récupère tous les tags avec leurs posts associés.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTags()
    {
        try {
            $tags = Tag::with('posts')->get();
            return response()->json($tags, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des tags', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Crée un nouveau tag avec validation des données de la requête.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTag(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $tag = new Tag();
            $tag->name = $validatedData['name'];
            $tag->slug = Str::slug($validatedData['name']);
            $tag->save();

            return response()->json($tag->load('posts'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La création du tag a échoué', 'details' => $e->getMessage()], 500);
        }
    }
}
