<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:10',
        ]);

        try {
            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password'])
            ]);

            $token = auth()->attempt($request->only('email', 'password'));

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Throwable $th) {
            return response()->json(['erreur' => "Une erreur s'est produite lors de la création du compte", 'details' => $th->getMessage()]);
        }
    }

    public function connnexion(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function deconnexion()
    {
        auth()->logout();
        return response()->json(['message' => "L'utilisateur a été déconnecté avec succès"]);
    }
}
