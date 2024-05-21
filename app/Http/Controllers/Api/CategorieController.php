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
        $categorie = Categorie::with('posts')->get();

        return response()->json($categorie, 200);
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
        $categorie->name = $request->name;
        $categorie->slug = Str::slug($request->name);

        $categorie->update();

        return response()->json($categorie->load('posts'), 200);
    }

    public function deleteCategorie(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json(["message"=>"Catégorie a été supprimé avec succès"]);
    }
}
