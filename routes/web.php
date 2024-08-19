<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get("/",[PostController::class,'index'])->name('index');
Route::get("/post/{post}",[PostController::class,'showpost'])->name("postbyid");
