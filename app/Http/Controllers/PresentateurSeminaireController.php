<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;
// Si vous avez besoin de Carbon pour des manipulations de dates spécifiques ici :
// use Carbon\Carbon;

class PresentateurSeminaireController extends Controller
{
    /**
     * Affiche la liste des séminaires soumis par le présentateur.
     */
    public function index()
    {
        $presentateurId = Auth::id();
        $seminaires = Seminar::where('presentateur_id', $presentateurId)
                            ->orderBy('created_at', 'desc') // Ou 'date_presentation' si plus pertinent
                            ->paginate(15); // Ajout de la pagination

        return view('presentateur.seminars.index', [
            'seminaires' => $seminaires
        ]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle demande de séminaire.
     */
    public function create()
    {
        return view('presentateur.seminars.create');
    }

    /**
     * Enregistre une nouvelle demande de séminaire.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'theme' => 'required|string|max:500', // Max 500 pour le thème si besoin
        ]);

        $seminar = new Seminar();
        $seminar->theme = $validatedData['theme'];
        $seminar->presentateur_id = Auth::id();
        $seminar->statut = 'en_attente'; // Statut initial
        // Les champs date_presentation, heure_presentation, date_limite_resume seront null par défaut
        // s'ils sont bien configurés comme nullable dans votre migration et base de données.

        $seminar->save();

        return redirect()->route('presentateur.dashboard') // Ou 'presentateur.seminaires.index'
                         ->with('success', 'Votre demande de séminaire a été soumise avec succès !');
    }
    
    /**
     * Affiche les détails d'un séminaire spécifique pour le présentateur.
     */
    public function show(Seminar $seminaire) // Utilisation du Route Model Binding implicite
    {
        // S'assurer que le séminaire appartient au présentateur connecté
        // Cette vérification est cruciale pour la sécurité.
        if ($seminaire->presentateur_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce séminaire.');
        }

        // Charger les relations nécessaires pour la vue
        // (Résumé et Fichiers de présentation - appelés 'materials' dans votre modèle Seminar)
        $seminaire->load(['resume', 'materials']); 

        // Retourner la vue avec le séminaire et ses relations chargées
        return view('presentateur.seminars.show', compact('seminaire'));
    }
}