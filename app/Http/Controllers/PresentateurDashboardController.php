<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use App\Models\Material; // Assurez-vous que ce modèle pointe vers la table 'fichiers' ou votre équivalent
// Si votre modèle pour les fichiers s'appelle Fichier, utilisez 'use App\Models\Fichier;'
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // Nécessaire pour Carbon::today()

class PresentateurDashboardController extends Controller
{
    /**
     * Display the presentateur dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $presentateurId = $user->id; // ID du présentateur connecté

        // --- RÉCUPÉRATION DES DONNÉES ---

        // Récupérer les séminaires à venir BRUTS (avant la transformation par ->map())
        $upcomingSeminarsQuery = Seminar::where('presentateur_id', $presentateurId)
            ->where('date_presentation', '>=', Carbon::today()->toDateString())
            ->orderBy('date_presentation')
            ->get();

        // --- NOTRE "LOUPE" POUR DÉBOGUER ---
        // Décommentez la ligne suivante pour voir les données brutes des séminaires à venir
        // et arrêtez l'exécution ici pour inspecter.
        // dd('Données brutes des séminaires à venir:', $upcomingSeminarsQuery->toArray());
        // --- FIN DE LA LOUPE ---

        // Maintenant, transformons les séminaires à venir pour ajouter les jours restants, badges, etc.
        $upcomingSeminars = $upcomingSeminarsQuery->map(function ($seminar) {
            // Calcul des jours restants
            $datePresentation = Carbon::parse($seminar->date_presentation);
            // Assurez-vous que Carbon::now() utilise le bon fuseau horaire si nécessaire
            // En comparant uniquement les dates (sans l'heure), le fuseau horaire a moins d'impact pour diffInDays
            $seminar->jours_restants = Carbon::today()->diffInDays($datePresentation, false);

            // Logique pour le badge d'état (exemple simple)
            $seminar->badge_etat_texte = 'Nouveau'; // Valeur par défaut
            $seminar->badge_etat_couleur = 'blue';   // Couleur par défaut

            if ($seminar->jours_restants <= 7 && $seminar->jours_restants >= 0) {
                $seminar->badge_etat_texte = 'Bientôt';
                $seminar->badge_etat_couleur = 'yellow';
            }

            // Vérifier si un résumé a été soumis
            // Assurez-vous que la relation 'resume' existe sur votre modèle Seminar
            // et qu'elle charge correctement un objet Resume ou null.
            if ($seminar->relationLoaded('resume') && $seminar->resume && (!empty($seminar->resume->contenu) || !empty($seminar->resume->chemin_pdf_resume))) {
                $seminar->badge_etat_texte = 'Résumé reçu';
                $seminar->badge_etat_couleur = 'green';
            }
            
            // Si la date limite du résumé est proche et qu'il n'y a pas de résumé
            if (isset($seminar->date_limite_resume) && (!$seminar->relationLoaded('resume') || !$seminar->resume) ) {
                $dateLimiteResume = Carbon::parse($seminar->date_limite_resume);
                // On s'assure que la date limite n'est pas déjà passée pour ce badge
                if (Carbon::today()->diffInDays($dateLimiteResume, false) <= 3 && Carbon::today()->diffInDays($dateLimiteResume, false) >=0) {
                    $seminar->badge_etat_texte = 'Résumé urgent!';
                    $seminar->badge_etat_couleur = 'red';
                }
            }

            // Formater la date et l'heure pour l'affichage
            $seminar->date_presentation_formatee = $datePresentation->isoFormat('D MMMM YYYY'); // Format plus complet
            if ($seminar->heure_presentation) {
                try {
                    $seminar->heure_presentation_formatee = Carbon::parse($seminar->heure_presentation)->format('H\hm');
                } catch (\Exception $e) {
                    $seminar->heure_presentation_formatee = 'Heure invalide'; // Gestion d'erreur si le format est mauvais
                }
            } else {
                $seminar->heure_presentation_formatee = '';
            }

            return $seminar;
        });

        // Récupérer les séminaires déjà présentés par le présentateur connecté
        $presentedSeminars = Seminar::where('presentateur_id', $presentateurId)
            ->where('date_presentation', '<', Carbon::today()->toDateString())
            ->orderBy('date_presentation', 'desc') // Plus récent en premier
            ->get(); // Vous pouvez aussi appliquer un ->map() ici si besoin

        // Récupérer les matériels (fichiers) soumis
        $submittedMaterials = Material::whereHas('seminar', function ($query) use ($presentateurId) {
            $query->where('presentateur_id', $presentateurId);
        })
        ->latest('created_at')
        ->take(5)
        ->get();

        // Récupérer les séminaires en attente de résumé
        $pendingSeminars = Seminar::where('presentateur_id', $presentateurId)
            ->whereNotNull('date_limite_resume') // S'assurer qu'il y a une date limite
            ->where('date_limite_resume', '>=', Carbon::today()->toDateString())
            ->whereDoesntHave('materials') // Ou 'resume' ou 'fichiers' selon votre relation
            ->orderBy('date_limite_resume')
            ->get();

        // --- LOGIQUE POUR LES COMPTES DU RÉCAPITULATIF ---
        $seminairesProgrammesCount = Seminar::where('presentateur_id', $presentateurId)
                                            ->where('statut', 'validé')
                                            ->where('date_presentation', '>=', Carbon::today())
                                            ->count();

        $seminairesTerminesCount = Seminar::where('presentateur_id', $presentateurId)
                                          ->where('date_presentation', '<', Carbon::today())
                                          ->whereIn('statut', ['validé', 'publié'])
                                          ->count();

        $presentationsDisponiblesCount = Seminar::where('presentateur_id', $presentateurId)
                                                ->has('materials') // Ou 'fichiers'
                                                ->count();

        // Passer toutes les variables à la vue
        return view('presentateur.dashboard', compact(
            'user',
            'upcomingSeminars',
            'presentedSeminars',
            'submittedMaterials',
            'pendingSeminars',
            'seminairesProgrammesCount',
            'seminairesTerminesCount',
            'presentationsDisponiblesCount'
        ));
    }
}