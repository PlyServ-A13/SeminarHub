<?php

namespace App\Http\Controllers;

// Assurez-vous que le namespace est correct par rapport à votre structure de dossiers
// Si Controller.php est dans App\Http\Controllers, alors 'use App\Http\Controllers\Controller;' n'est pas nécessaire.
// 'use Illuminate\Routing\Controller as BaseController;' est souvent utilisé, mais si vous héritez directement, c'est bon.
// Pour simplifier, je vais assumer que vous héritez du contrôleur de base de Laravel.
use Illuminate\Routing\Controller as BaseController; // Ou juste use Controller; si Controller.php est dans le même namespace
use App\Models\Seminar;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Ajouté pour Storage::
use Carbon\Carbon;

class PresentateurResumeController extends BaseController // Ou Controller si votre fichier Controller.php est dans le même namespace
{
    public function create(Seminar $seminaire)
    {
        // Vérifications de sécurité et de logique métier
        if ($seminaire->presentateur_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        if ($seminaire->statut !== 'validé') {
            return redirect()->route('presentateur.seminaires.index')->with('error', 'Ce séminaire n\'est pas encore validé.');
        }
        if (Carbon::now()->gt(Carbon::parse($seminaire->date_presentation))) {
            return redirect()->route('presentateur.seminaires.index')->with('error', 'La date de présentation de ce séminaire est dépassée.');
        }

        return view('presentateur.resumes.create', compact('seminaire'));
    }

    public function store(Request $request, Seminar $seminaire)
    {
        // Vérifications de sécurité et de logique métier
        if ($seminaire->presentateur_id !== Auth::id() || $seminaire->statut !== 'validé' || Carbon::now()->gt(Carbon::parse($seminaire->date_presentation))) {
            abort(403, 'Action non autorisée ou conditions non remplies.');
        }

        $request->validate([
            'contenu' => 'nullable|string|max:65535', // Max pour TEXT SQL
            'resume_pdf' => 'nullable|file|mimes:pdf|max:5120', // PDF de 5MB max
        ]);

        // Vérifier qu'au moins l'un des deux (texte ou PDF) est fourni
        if (empty($request->input('contenu')) && !$request->hasFile('resume_pdf')) {
            return back()->withErrors(['error' => 'Vous devez fournir soit un contenu textuel, soit un fichier PDF pour le résumé.'])->withInput();
        }

        $data = [
            'seminaire_id' => $seminaire->id,
            'date_envoi' => now(),
            // Si 'contenu' est null (par exemple, champ vide et middleware ConvertEmptyStringsToNull actif),
            // alors utilise une chaîne vide '' pour satisfaire la contrainte NOT NULL de la DB.
            'contenu' => $request->input('contenu') ?? '',
        ];

        if ($request->hasFile('resume_pdf')) {
            $path = $request->file('resume_pdf')->store('resumes_pdf', 'public');
            $data['chemin_pdf_resume'] = $path;
        }

        Resume::updateOrCreate(
            ['seminaire_id' => $seminaire->id],
            $data
        );

        return redirect()->route('presentateur.seminaires.index')->with('success', 'Résumé soumis avec succès !');
    }

    public function edit(Seminar $seminaire, Resume $resume)
    {
        if ($seminaire->presentateur_id !== Auth::id() || $resume->seminaire_id !== $seminaire->id) {
            abort(403, 'Accès non autorisé.');
        }
        // Ajouter d'autres vérifications si nécessaire (ex: date de présentation non dépassée pour modification)
        // if (Carbon::now()->gt(Carbon::parse($seminaire->date_presentation))) {
        //     return redirect()->route('presentateur.seminaires.index')->with('error', 'Impossible de modifier le résumé après la date de présentation.');
        // }

        return view('presentateur.resumes.edit', compact('seminaire', 'resume'));
    }

    public function update(Request $request, Seminar $seminaire, Resume $resume)
    {
        if ($seminaire->presentateur_id !== Auth::id() || $resume->seminaire_id !== $seminaire->id) {
            abort(403, 'Accès non autorisé.');
        }
        // Ajouter d'autres vérifications si nécessaire

        $request->validate([
            'contenu' => 'nullable|string|max:65535', // Max pour TEXT SQL
            'resume_pdf' => 'nullable|file|mimes:pdf|max:5120', // PDF de 5MB max
            'supprimer_pdf_existant' => 'nullable|boolean', // Champ optionnel pour gérer la suppression du PDF
        ]);

        $nouveauContenuTexteSoumis = $request->input('contenu');
        $hasNouveauFichierPdf = $request->hasFile('resume_pdf');
        $veutSupprimerPdfExistant = $request->boolean('supprimer_pdf_existant');

        // Condition pour s'assurer qu'il reste au moins une forme de contenu
        // Si on ne soumet rien de nouveau ET qu'on demande à supprimer le PDF existant ET que le contenu textuel existant est vide
        if (!$hasNouveauFichierPdf && is_null($nouveauContenuTexteSoumis) && $veutSupprimerPdfExistant && empty($resume->contenu) && $resume->chemin_pdf_resume) {
            return back()->withErrors(['error' => 'Si vous supprimez le PDF, vous devez fournir un contenu textuel.'])->withInput();
        }
        // Si on ne soumet rien de nouveau ET que l'existant (texte et PDF) est déjà vide (ne devrait pas arriver si la logique de store est bonne)
        if (!$hasNouveauFichierPdf && is_null($nouveauContenuTexteSoumis) && empty($resume->chemin_pdf_resume) && empty($resume->contenu) && !$veutSupprimerPdfExistant) {
             return back()->withErrors(['error' => 'Vous devez fournir soit un contenu textuel, soit un fichier PDF pour le résumé.'])->withInput();
        }


        // Mise à jour du contenu textuel
        // Si le champ 'contenu' est explicitement soumis dans la requête (même s'il est vide)
        if ($request->has('contenu')) {
            // Si la valeur soumise est null (par exemple, champ vide + middleware ConvertEmptyStringsToNull),
            // on la transforme en chaîne vide pour la base de données. Sinon, on prend la valeur soumise.
            $resume->contenu = $nouveauContenuTexteSoumis ?? '';
        }
        // Si le champ 'contenu' n'est PAS soumis dans la requête, $resume->contenu conserve sa valeur actuelle.

        // Mise à jour du fichier PDF
        if ($hasNouveauFichierPdf) {
            // Supprimer l'ancien PDF s'il existe
            if ($resume->chemin_pdf_resume) {
                Storage::disk('public')->delete($resume->chemin_pdf_resume);
            }
            // Stocker le nouveau PDF
            $path = $request->file('resume_pdf')->store('resumes_pdf', 'public');
            $resume->chemin_pdf_resume = $path;

            // Optionnel: Si un nouveau PDF est uploadé, vider le contenu textuel ?
            // if ($request->boolean('effacer_texte_si_pdf_upload')) {
            //     $resume->contenu = '';
            // }
        } elseif ($veutSupprimerPdfExistant) {
            // Si l'utilisateur a coché une case pour supprimer le PDF existant (et n'en a pas uploadé un nouveau)
            if ($resume->chemin_pdf_resume) {
                Storage::disk('public')->delete($resume->chemin_pdf_resume);
                $resume->chemin_pdf_resume = null;
            }
        }

        // S'assurer qu'après toutes les opérations, si chemin_pdf_resume est null, contenu ne soit pas null.
        // (Cette logique est maintenant gérée par l'assignation de $resume->contenu plus haut)
        // if (is_null($resume->chemin_pdf_resume) && is_null($resume->contenu)) {
        //     $resume->contenu = ''; // Fallback au cas où, mais la logique ci-dessus devrait couvrir.
        // }


        $resume->date_envoi = now();
        $resume->save(); // C'est ici que l'erreur se produisait

        return redirect()->route('presentateur.seminaires.index')->with('success', 'Résumé mis à jour avec succès !');
    }
}