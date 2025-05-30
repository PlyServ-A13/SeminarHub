<?php

namespace App\Http\Controllers;

use App\Models\Seminar; // Importez votre modèle Seminar
use App\Models\Fichier; // Importez votre modèle Fichier (si vous en avez un et utilisez l'Option A)
use Illuminate\Support\Facades\DB; // Nécessaire si vous utilisez l'Option B pour le compte des fichiers
use Carbon\Carbon; // Pour la gestion des dates
// Inutile d'importer Request ici si la méthode ne l'utilise pas en paramètre

class CountDashboardController extends Controller
{
    public function afficherRecapitulatif()
    {
        // 1. Séminaires programmés
        $seminairesProgrammesCount = Seminar::where('statut', 'validé')
                                            ->where('date_presentation', '>=', Carbon::today())
                                            ->count();

        // 2. Séminaires terminés
        $seminairesTerminesCount = Seminar::where('date_presentation', '<', Carbon::today())
                                          ->whereIn('statut', ['validé', 'publié'])
                                          ->count();

        // 3. Présentations disponibles
        // Option A: En utilisant une relation Eloquent (méthode préférée)
        // Assurez-vous que votre modèle App\Models\Seminar a une relation comme celle-ci :
        // public function fichiers() {
        //     return $this->hasMany(Fichier::class, 'seminaire_id');
        // }
        // Et que vous avez un modèle App\Models\Fichier.
        $presentationsDisponiblesCount = Seminar::has('fichiers')->count();

        // Option B: En utilisant une requête directe sur la table 'fichiers' (décommentez si vous préférez cette option)
        /*
        $presentationsDisponiblesCount = DB::table('fichiers')
                                             ->distinct('seminaire_id')
                                             ->count('seminaire_id');
        */

        // Assurez-vous que le nom de la vue ici correspond au fichier Blade
        // où se trouve votre section <div class="recapitulatif ...">
        return view('nom_de_votre_vue_principale_ou_dashboard', [
            'seminairesProgrammesCount' => $seminairesProgrammesCount,
            'seminairesTerminesCount' => $seminairesTerminesCount,
            'presentationsDisponiblesCount' => $presentationsDisponiblesCount,
        ]);
    }
}