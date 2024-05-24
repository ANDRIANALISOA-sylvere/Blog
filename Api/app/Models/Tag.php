<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe Tag représentant le modèle pour les étiquettes dans la base de données.
 */
class Tag extends Model
{
    // Attributs que l'on peut assigner massivement.
    protected $fillable = ["name", "slug"];

    /**
     * Relation Many-to-Many avec le modèle Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        // Retourne la relation avec le modèle Post, spécifiant la table pivot 'post_tags'.
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
