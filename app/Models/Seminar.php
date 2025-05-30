<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Ajoutez ceci si vous utilisez HasOne pour resume

class Seminar extends Model
{
    use HasFactory;

    protected $table = 'seminaires';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'theme',
        'date_presentation',
        'heure_presentation', // Assurez-vous que cette colonne existe dans votre table 'seminaires'
        // 'description', // Si vous avez une colonne 'description' dans 'seminaires'
        'presentateur_id',
        'statut',
        // 'date_limite_resume',
    ];

    protected $casts = [
        'date_presentation' => 'date', // ou 'datetime' si vous stockez l'heure avec
        'heure_presentation' => 'datetime:H:i:s', // Pour ne garder que l'heure, mais Eloquent le traitera comme un objet date/heure complet. Parfois, on le laisse en string si on ne fait que l'afficher. Mieux: 'datetime:H:i' si vous voulez le manipuler comme heure. Souvent, on stocke l'heure comme string si elle est séparée de la date. Si c'est un type TIME en BDD, le laisser en string ou le caster en 'datetime:H:i' est une option.
        'date_limite_resume' => 'date',
    ];

    /**
     * Récupère les fichiers (materials) associés à ce séminaire.
     * Cela suppose que vous avez un modèle App\Models\Fichier.
     */
    public function materials(): HasMany
    {
        // Vérifiez que le modèle Fichier existe et est correctement nommé/importé
        // et que 'seminaire_id' est la clé étrangère dans la table 'fichiers'
        return $this->hasMany(Fichier::class, 'seminaire_id', 'id');
    }

    /**
     * Récupère le résumé associé à ce séminaire.
     * Cela suppose que vous avez un modèle App\Models\Resume.
     */
    public function resume(): HasOne // Ou HasMany si un séminaire peut avoir plusieurs résumés
    {
        // Vérifiez que le modèle Resume existe et est correctement nommé/importé
        // et que 'seminaire_id' est la clé étrangère dans la table 'resumes'
        return $this->hasOne(Resume::class, 'seminaire_id', 'id');
    }

    /**
     * Relation avec le présentateur (User).
     */
    public function presentateur()
    {
        return $this->belongsTo(User::class, 'presentateur_id');
    }

    public function fichiers()
    {
        return $this->hasMany(Fichier::class, 'seminaire_id'); // Assurez-vous que Fichier::class est correct
    }

}