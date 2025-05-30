<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importez BelongsTo


class Material extends Model
{
    protected $table = 'fichiers';

    public function seminar(): BelongsTo // Déclare la relation "belongsTo" vers le modèle Seminar
    {
        // Assurez-vous que 'seminaire_id' est bien la clé étrangère dans la table 'fichiers'
        // qui pointe vers la clé primaire de la table 'seminaires'.
        return $this->belongsTo(Seminar::class, 'seminaire_id');

        // Si votre clé étrangère s'appelle simplement 'seminar_id',
        // vous pouvez simplifier en :
        // return $this->belongsTo(Seminar::class);
    }

}
