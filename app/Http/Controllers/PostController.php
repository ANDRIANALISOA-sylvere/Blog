<?php

namespace App\Http\Controllers;

class PostController extends Controller
{
    public function index()
    {
        return View("home.home",[
            'name'=> "josephin sylvere"
        ]);
    }
}
