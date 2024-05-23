<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategorieController extends Controller
{
    public function getAllCategories()
    {
        try {
            $categories = Categorie::with('posts')->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des catégories'], 500);
        }
    }

    public function createCategorie(Request $request)
    {
        $categorie = new Categorie();

        $categorie->name = $request->name;
        $categorie->slug = Str::slug($request->name);

        $categorie->save();

        return response()->json($categorie, 201);
    }

    public function getCategorieById(Categorie $categorie)
    {
        return response()->json($categorie->load('posts'), 200);
    }

    public function updateCategorie(Categorie $categorie, Request $request)
    {
        // Validation des données de la requête
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $categorie->name = $validatedData['name'];
            $categorie->slug = Str::slug($validatedData['name']);

            $categorie->update();

            return response()->json($categorie->load('posts'), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La mise à jour de la catégorie a échoué'], 500);
        }
    }

    public function deleteCategorie(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json(["message"=>"Catégorie a été supprimé avec succès"]);
    }
}
