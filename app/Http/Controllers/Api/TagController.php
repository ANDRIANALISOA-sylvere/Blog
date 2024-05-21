<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    function show()
    {
        $tag = Tag::with('posts')->get();

        return response()->json($tag, 200);
    }

    function index(Request $request)
    {
        $tag = new Tag();

        $tag->name = $request->name;
        $tag->slug = $request->slug;

        $tag->save();

        return response()->json($tag, 201);
    }
}
