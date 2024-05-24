<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe modèle pour les catégories.
 */
class Categorie extends Model
{
    // Attributs modifiables en masse.
    protected $fillable = ["name","slug"];

    /**
     * Relation de plusieurs à plusieurs avec le modèle Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        // Définit la relation avec le modèle Post via la table pivot 'post_categories'.
        return $this->belongsToMany(Post::class, 'post_categories');
    }
}
