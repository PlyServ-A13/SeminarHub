<?php

namespace App\Http\Controllers;

use App\Models\Seminar; // Assurez-vous que c'est le bon chemin vers votre modèle Seminar
use Illuminate\Http\Request; // Important pour la recherche
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Si vous l'utilisez pour les notifications personnalisées

class EtudiantDashboardController extends Controller
{
    public function index(Request $request) // Injection de Request pour la recherche
    {
        $user = Auth::user();
        // Construction du nom de l'utilisateur pour l'affichage
        $userName = ($user->prenom ? $user->prenom . ' ' : '') . $user->name;

        $aujourdhui = Carbon::today(); // Date actuelle pour comparaison

        // Récupérer le terme de recherche global depuis la requête
        $searchTerm = $request->input('search_global');

        // 1. Construire la requête de base pour les Séminaires à Venir
        // NOUVELLE LOGIQUE : Statut 'publié' ET date de présentation future ou aujourd'hui
        $queryAVenir = Seminar::with(['presentateur', 'resume']) // Charger les relations nécessaires
                                    ->where('statut', 'publié') // <-- MODIFICATION ICI : 'validé' devient 'publié'
                                    ->where('date_presentation', '>=', $aujourdhui);

        // 2. Construire la requête de base pour les Séminaires Passés
        // Statut 'publié' ET date de présentation passée (logique inchangée ici)
        $queryPasses = Seminar::with(['presentateur', 'materials']) // Charger les relations nécessaires
                                ->where('statut', 'publié')
                                ->where('date_presentation', '<', $aujourdhui);

        // 3. Appliquer le filtre de recherche si un terme est fourni
        if ($searchTerm) {
            // Définition de la logique de recherche (appliquée aux deux requêtes)
            $searchLogic = function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    // Recherche dans le thème du séminaire
                    $q->where('theme', 'LIKE', "%{$searchTerm}%")
                      // OU recherche dans le nom ou prénom du présentateur
                      ->orWhereHas('presentateur', function ($subQuery) use ($searchTerm) {
                          $subQuery->where('name', 'LIKE', "%{$searchTerm}%")
                                   ->orWhere('prenom', 'LIKE', "%{$searchTerm}%");
                      });
                });
            };

            // Appliquer la logique de recherche aux deux requêtes
            $queryAVenir->where($searchLogic);
            $queryPasses->where($searchLogic);
        }

        // 4. Exécuter les requêtes, trier et récupérer les résultats
        $seminairesAVenir = $queryAVenir->orderBy('date_presentation', 'asc')
                                        ->orderBy('heure_presentation', 'asc')
                                        ->get();

        $seminairesPasses = $queryPasses->orderBy('date_presentation', 'desc')
                                       ->get(); // Ou ->paginate(10) si vous souhaitez la pagination pour les séminaires passés

        // 5. Logique pour récupérer les notifications (optionnelle, inchangée)
        /*
        if ($user) { // S'assurer que l'utilisateur est connecté pour récupérer ses notifications
            $notifications = DB::table('notifications')
                                 ->where('users_id', $user->id) // Assurez-vous que la colonne est bien 'users_id'
                                 ->orderBy('date_envoi', 'desc')
                                 ->take(5) // Par exemple, les 5 plus récentes
                                 ->get();
        } else {
            $notifications = collect(); // Collection vide si pas d'utilisateur
        }
        */

        // Passer les données à la vue
        return view('etudiant.dashboard', [
            'userName' => $userName,
            'seminairesAVenir' => $seminairesAVenir,
            'seminairesPasses' => $seminairesPasses,
            'searchGlobal' => $searchTerm, // Renvoyer le terme de recherche pour l'afficher dans l'input
            // 'notifications' => $notifications, // Décommentez si vous passez les notifications
        ]);
    }
}