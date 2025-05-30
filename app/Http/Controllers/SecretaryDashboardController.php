<?php

namespace App\Http\Controllers; // CHANGEMENT ICI : le namespace est App\Http\Controllers

use App\Http\Controllers\Controller;
use App\Models\Seminar; // Assurez-vous que le nom du modèle est correct et importé
use Illuminate\Http\Request; // Peut être utile si vous ajoutez des filtres ou d'autres fonctionnalités plus tard
use Illuminate\Support\Facades\DB; // Non utilisé directement dans cette méthode, mais peut être utile ailleurs
use Carbon\Carbon;

class SecretaryDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de la secrétaire avec les compteurs.
     */
    public function index()
    {
        $now = Carbon::now(); // Obtenir la date ET l'heure actuelles

        // Compteur 1: Nouvelles demandes de séminaire à valider
        $pendingRequestsCount = Seminar::where('statut', 'en_attente')->count();

        // Compteur 2: Résumés de séminaire attendus
        $upcomingSummariesDueCount = Seminar::where('statut', 'validé')
            ->whereNotNull('date_limite_resume')
            ->where('date_limite_resume', '>=', $now->toDateString()) // Comparer avec la date actuelle
            ->whereDoesntHave('resume')
            ->count();

        // Compteur 3: Programme de séminaires à publier
        $seminarsToPublishCount = Seminar::where('statut', 'validé')
            ->whereHas('resume')
            ->count();

        // Compteur 4: Fichiers de séminaires terminés à mettre en ligne
        // (Statut 'publié' ET date ET heure de présentation passées)
        // ET qui n'ont pas encore de fichiers.
        $completedSeminarsNeedingFilesCount = Seminar::where('statut', 'publié')
            ->where(function ($query) use ($now) {
                // Cas 1: La date de présentation est strictement dans le passé
                $query->where('date_presentation', '<', $now->toDateString())
                      // Cas 2: La date de présentation est aujourd'hui ET l'heure de présentation est passée
                      ->orWhere(function ($subQuery) use ($now) {
                          $subQuery->where('date_presentation', '=', $now->toDateString())
                                   ->where('heure_presentation', '<', $now->format('H:i:s'));
                      });
            })
            ->whereDoesntHave('materials') // Ne compter que ceux sans fichiers
            ->count();


        return view('secretaire.dashboard', compact(
            'pendingRequestsCount',
            'upcomingSummariesDueCount',
            'seminarsToPublishCount',
            'completedSeminarsNeedingFilesCount'
        ));
    }
}