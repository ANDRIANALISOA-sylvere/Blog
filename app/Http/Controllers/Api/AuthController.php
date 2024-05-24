<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Authentification",
 *     description="Opérations d'authentification pour les utilisateurs"
 * )
 */

// Contrôleur pour l'authentification des utilisateurs
class AuthController extends Controller
{
    // Constructeur avec middleware pour sécuriser les routes
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Authentification"},
     *     summary="Connexion des utilisateurs",
     *     description="Authentifie un utilisateur et renvoie un token JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de connexion de l'utilisateur",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", description="Adresse email de l'utilisateur"),
     *             @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(
     *                 property="authorization",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", description="Token JWT"),
     *                 @OA\Property(property="type", type="string", description="Type de token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        // Gestion de l'échec de la connexion
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Réponse en cas de succès
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Authentification"},
     *     summary="Enregistrement des utilisateurs",
     *     description="Enregistre un nouvel utilisateur et renvoie un token JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l'utilisateur à enregistrer",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", description="Nom de l'utilisateur"),
     *             @OA\Property(property="email", type="string", format="email", description="Adresse email de l'utilisateur"),
     *             @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Message de succès"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(
     *                 property="authorization",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", description="Token JWT"),
     *                 @OA\Property(property="type", type="string", description="Type de token")
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tentative de connexion automatique après enregistrement
        $token = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Réponse en cas de succès
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logoutUser",
     *     tags={"Authentification"},
     *     summary="Déconnexion des utilisateurs",
     *     description="Déconnecte l'utilisateur et invalide le token JWT.",
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Utilisateur déconnecté avec succès")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     operationId="refreshToken",
     *     tags={"Authentification"},
     *     summary="Rafraîchissement du token JWT",
     *     description="Rafraîchit le token JWT de l'utilisateur et renvoie un nouveau token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Token JWT nécessaire pour l'authentification",
     *         @OA\Schema(
     *             type="string",
     *             format="bearer token"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token rafraîchi",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(
     *                 property="authorization",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", description="Nouveau token JWT"),
     *                 @OA\Property(property="type", type="string", description="Type de token")
     *             )
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
