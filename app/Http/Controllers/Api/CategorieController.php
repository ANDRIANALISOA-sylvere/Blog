<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    function getAllCategories()
    {
        $categorie = Categorie::with('posts')->get();

        return response()->json($categorie, 200);
    }

    function createCategorie(Request $request)
    {
        $categorie = new Categorie();

        $categorie->name = $request->name;
        $categorie->slug = $request->slug;

        $categorie->save();

        return response()->json($categorie, 201);
    }
}
