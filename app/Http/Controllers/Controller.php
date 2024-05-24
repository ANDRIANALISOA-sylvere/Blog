<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   title="Mon API Blog",
 *   version="1.0.0",
 *   description="Cette API permet de gérer un blog.",
 *   @OA\Contact(
 *     email="josephinsylvere@gmail.com",
 *     name="Joséphin Sylvère"
 *   )
 * )
 * @OA\Schema(
 *   schema="User",
 *   type="object",
 *   title="Utilisateur",
 *   description="Un modèle d'utilisateur pour le blog",
 *   properties={
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       description="L'identifiant unique de l'utilisateur"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       type="string",
 *       description="Le nom de l'utilisateur"
 *     ),
 *     @OA\Property(
 *       property="email",
 *       type="string",
 *       format="email",
 *       description="L'email de l'utilisateur"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de création de l'utilisateur"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de la dernière mise à jour de l'utilisateur"
 *     )
 *   }
 * )
 * @OA\Schema(
 *   schema="Category",
 *   type="object",
 *   title="Categorie",
 *   description="Un modèle de catégorie pour le blog",
 *   properties={
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       description="L'identifiant unique de la catégorie"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       type="string",
 *       description="Le nom de la catégorie"
 *     ),
 *     @OA\Property(
 *       property="slug",
 *       type="string",
 *       description="Le slug de la catégorie"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de création de la catégorie"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de la dernière mise à jour de la catégorie"
 *     )
 *   }
 * )
 * @OA\Schema(
 *   schema="Tag",
 *   type="object",
 *   title="Tag",
 *   description="Un modèle de tag pour le blog",
 *   properties={
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       description="L'identifiant unique du tag"
 *     ),
 *     @OA\Property(
 *       property="name",
 *       type="string",
 *       description="Le nom du tag"
 *     ),
 *     @OA\Property(
 *       property="slug",
 *       type="string",
 *       description="Le slug du tag"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de création du tag"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de la dernière mise à jour du tag"
 *     )
 *   }
 * )
 * @OA\Schema(
 *   schema="Comment",
 *   type="object",
 *   title="Comment",
 *   description="Un modèle de commentaire pour le blog",
 *   properties={
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       description="L'identifiant unique du commentaire"
 *     ),
 *     @OA\Property(
 *       property="post_id",
 *       type="integer",
 *       description="L'identifiant du post associé"
 *     ),
 *     @OA\Property(
 *       property="user_id",
 *       type="integer",
 *       description="L'identifiant de l'utilisateur qui a écrit le commentaire"
 *     ),
 *     @OA\Property(
 *       property="content",
 *       type="string",
 *       description="Le contenu du commentaire"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de création du commentaire"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de la dernière mise à jour du commentaire"
 *     )
 *   }
 * )
 * @OA\Schema(
 *   schema="Post",
 *   type="object",
 *   title="Post",
 *   description="Un modèle de post pour le blog",
 *   properties={
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       description="L'identifiant unique du post"
 *     ),
 *     @OA\Property(
 *       property="title",
 *       type="string",
 *       description="Le titre du post"
 *     ),
 *     @OA\Property(
 *       property="content",
 *       type="string",
 *       description="Le contenu du post"
 *     ),
 *     @OA\Property(
 *       property="user_id",
 *       type="integer",
 *       description="L'identifiant de l'utilisateur qui a créé le post"
 *     ),
 *     @OA\Property(
 *       property="created_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de création du post"
 *     ),
 *     @OA\Property(
 *       property="updated_at",
 *       type="string",
 *       format="date-time",
 *       description="La date et l'heure de la dernière mise à jour du post"
 *     )
 *   }
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="auth_bearer",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
