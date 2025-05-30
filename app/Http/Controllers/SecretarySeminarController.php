<?php

namespace App\Http\Controllers; // Confirmé : directement dans App\Http\Controllers

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\User; // Nécessaire pour récupérer les étudiants et le présentateur
// use App\Models\Fichier; // Décommentez si vous passez à un modèle Eloquent pour 'fichiers'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SeminarValidatedNotification;
use App\Mail\SeminarPublishedToStudentsNotification;
use App\Mail\SeminarDateTimeChangedPresenterNotification; // Mailable pour changement date/heure présentateur
use App\Mail\SeminarDateTimeChangedForStudentsNotification; // Mailable pour changement date/heure étudiants
// use App\Mail\SeminarRejectedNotification; // Si vous en créez un pour le rejet
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; // Ajouté pour la validation manuelle dans applyDateTimeChanges

class SecretarySeminarController extends Controller
{
    /**
     * Affiche la liste des demandes de séminaire en attente de validation.
     */
    public function indexRequests()
    {
        $pendingSeminars = Seminar::where('statut', 'en_attente')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('secretaire.seminar-requests.index', [
            'pendingSeminars' => $pendingSeminars
        ]);
    }

    /**
     * Valide une demande de séminaire et notifie le présentateur.
     */
    public function validateRequest(Request $request, Seminar $seminar)
    {
        $minPresentationDateCarbon = Carbon::today()->addDays(10);
        $minPresentationDateString = $minPresentationDateCarbon->toDateString(); // Format YYYY-MM-DD pour la règle
        $minPresentationDateFormattedForMessage = $minPresentationDateCarbon->format('d/m/Y');

        $validatedData = $request->validateWithBag('validate_' . $seminar->id, [
           'presentation_date' => 'required|date|after_or_equal:' . $minPresentationDateString,
            'heure_presentation' => 'required|date_format:H:i',
        ],[
            'presentation_date.after_or_equal' => "La date de présentation doit être à partir du {$minPresentationDateFormattedForMessage}. Cela correspond à au moins 10 jours après la date actuelle (" . Carbon::today()->format('d/m/Y') . ") pour laisser le temps au présentateur de soumettre son résumé.",
        ]);

        $seminar->statut = 'validé';
        $seminar->date_presentation = $validatedData['presentation_date'];
        $seminar->heure_presentation = $validatedData['heure_presentation'];
        $seminar->date_limite_resume = Carbon::parse($validatedData['presentation_date'])->subDays(10)->toDateString();

        if ($seminar->save()) {
            if ($seminar->presentateur && $seminar->presentateur->email) {
                try {
                    Mail::to($seminar->presentateur->email)
                        ->send(new SeminarValidatedNotification($seminar));
                } catch (\Exception $e) {
                    Log::error("Erreur d'envoi d'email de validation de séminaire (ID: {$seminar->id}): " . $e->getMessage());
                }
            }

            // $seminar->date_presentation, heure_presentation sont maintenant des objets Carbon grâce aux $casts
            // $seminar->date_limite_resume est une string, donc on la parse pour le formatage.
            $datePresentationFormatted = $seminar->date_presentation->format('d/m/Y');
            $heurePresentationFormatted = $seminar->heure_presentation->format('H:i'); // heure_presentation est casté en datetime:H:i
            $dateLimiteResumeFormatted = Carbon::parse($seminar->date_limite_resume)->format('d/m/Y');

            $messagePourTableNotification = "Votre présentation \"{$seminar->theme}\" est validée pour le {$datePresentationFormatted} à {$heurePresentationFormatted}. En attente d'envoyer du résumé 10 jours avant (le {$dateLimiteResumeFormatted}) pour finaliser le séminaire.";

            DB::table('notifications')->insert([
                'users_id' => $seminar->presentateur_id,
                'message' => $messagePourTableNotification,
                'date_envoi' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('secretary.seminar-requests.index')
                             ->with('success', 'Séminaire validé et programmé pour le ' . $datePresentationFormatted . ' à ' . $heurePresentationFormatted . '. Le présentateur a été notifié et une notification interne créée.');
        } else {
            Log::error("Échec de la sauvegarde lors de la validation du séminaire ID: {$seminar->id}");
            return redirect()->route('secretary.seminar-requests.index')
                             ->with('error', 'Erreur lors de la validation du séminaire.');
        }
    }

    /**
     * Rejette une demande de séminaire.
     */
    public function rejectRequest(Request $request, Seminar $seminar)
    {
        $validatedData = $request->validateWithBag('reject_' . $seminar->id, [
            'raison_refus' => 'nullable|string|max:1000',
        ]);

        if ($seminar->statut === 'en_attente') {
            $seminar->statut = 'rejeté';
            $seminar->raison_refus = $validatedData['raison_refus'] ?? null;
            $seminar->save();

            // Optionnel: Notifier le présentateur du rejet par email
            // if ($seminar->presentateur && $seminar->presentateur->email) { /* ... */ }

            return redirect()->route('secretary.seminar-requests.index')
                            ->with('info', 'La demande de séminaire pour le thème "' . $seminar->theme . '" a été marquée comme rejetée.');
        }

        return redirect()->route('secretary.seminar-requests.index')
                        ->with('error', 'Cette demande de séminaire ne peut plus être rejetée ou a déjà été traitée.');
    }

    /**
     * Affiche la liste des séminaires programmés (statut 'validé' ou 'publié').
     */
    public function indexScheduled()
    {
        $aujourdhui = Carbon::today();
        $seminairesProgrammes = Seminar::with(['presentateur', 'resume', 'materials'])
            ->whereIn('statut', ['validé', 'publié'])
            ->orderBy('date_presentation', 'asc')
            ->orderBy('heure_presentation', 'asc')
            ->paginate(15);

        foreach ($seminairesProgrammes as $seminaire) {
            if ($seminaire->resume) {
                $seminaire->statut_resume = 'Soumis';
                $seminaire->statut_resume_couleur = 'green';
            } elseif ($seminaire->date_limite_resume && $seminaire->date_limite_resume->lt($aujourdhui) && $seminaire->statut !== 'publié') {
                $seminaire->statut_resume = 'En retard';
                $seminaire->statut_resume_couleur = 'red';
            } elseif ($seminaire->date_limite_resume) {
                $seminaire->statut_resume = 'Attendu';
                $seminaire->statut_resume_couleur = 'yellow';
            } else {
                $seminaire->statut_resume = 'N/A';
                $seminaire->statut_resume_couleur = 'gray';
            }
        }
        return view('secretaire.seminaires_programmes.index', compact('seminairesProgrammes'));
    }

    /**
     * Affiche les détails d'un séminaire programmé spécifique pour gestion.
     */
    public function show(Seminar $seminar)
    {
        $seminar->load(['presentateur', 'resume', 'materials']);
        $aujourdhui = Carbon::today();

        if ($seminar->resume) {
            $seminar->statut_resume = 'Soumis';
        } elseif ($seminar->date_limite_resume && $seminar->date_limite_resume->lt($aujourdhui)) {
            $seminar->statut_resume = 'En retard';
        } elseif ($seminar->date_limite_resume) {
            $seminar->statut_resume = 'Attendu';
        } else {
            $seminar->statut_resume = 'N/A (pas de date limite spécifiée)';
        }

        return view('secretaire.seminaires_programmes.show', compact('seminar'));
    }

    /**
     * Publie les informations d'un séminaire et notifie tous les étudiants.
     */
    public function publish(Seminar $seminar)
    {
        if ($seminar->statut !== 'validé') {
            return redirect()->route('secretary.seminars.scheduled')
                             ->with('error', 'Ce séminaire ne peut pas être publié (statut actuel: ' . $seminar->statut . ').');
        }

        if (!$seminar->resume) {
            return redirect()->route('secretary.seminars.scheduled')
                             ->with('error', 'Le résumé pour le séminaire "'.$seminar->theme.'" doit être soumis avant de pouvoir le publier.');
        }

        if (!$seminar->date_presentation) {
             return redirect()->route('secretary.seminars.scheduled')
                             ->with('error', 'La date de présentation du séminaire "'.$seminar->theme.'" n\'est pas définie.');
        }
        
        $datePresentation = $seminar->date_presentation;
        $uneSemaineAvantPublicationMax = $datePresentation->copy()->subWeek();

        if (Carbon::today()->gt($uneSemaineAvantPublicationMax)) {
            return redirect()->route('secretary.seminars.scheduled')
                             ->with('error', 'Il est trop tard pour publier le séminaire "' . $seminar->theme . '". La publication doit se faire au moins une semaine avant sa date de présentation (soit avant le ' . $uneSemaineAvantPublicationMax->addDay()->isoFormat('LL'). ').');
        }

        $seminar->statut = 'publié';
        if ($seminar->save()) {
            Log::info("Séminaire ID {$seminar->id} (\"{$seminar->theme}\") publié par la secrétaire ID: " . (Auth::id() ?? 'Système'));

            $students = User::where('role', 'étudiant')->get();
            $notificationSentCount = 0;
            if ($students->isNotEmpty()) {
                foreach ($students as $student) {
                    if ($student->email) {
                        try {
                            Mail::to($student->email)->send(new SeminarPublishedToStudentsNotification($seminar));
                            $notificationSentCount++;
                        } catch (\Exception $e) {
                            Log::error("Échec de l'envoi de l'e-mail de publication (séminaire ID {$seminar->id}) à l'étudiant ID {$student->id}: " . $e->getMessage());
                        }
                    }
                }
                Log::info("Notification de publication du séminaire ID {$seminar->id} tentative d'envoi à {$notificationSentCount} étudiant(s).");
            } else {
                Log::info("Aucun étudiant trouvé pour la notification de publication du séminaire ID {$seminar->id}.");
            }

            return redirect()->route('secretary.seminars.scheduled')
                             ->with('success', 'Séminaire "'.$seminar->theme.'" publié avec succès. ' . $notificationSentCount . ' étudiant(s) notifié(s).');
        } else {
            Log::error("Échec de la sauvegarde lors de la publication du séminaire ID: {$seminar->id}");
            return redirect()->route('secretary.seminars.scheduled')
                             ->with('error', 'Une erreur est survenue lors de la publication du séminaire "' . $seminar->theme . '".');
        }
    }

    /**
     * Gère l'upload du fichier de présentation final par la secrétaire.
     */
    public function uploadPresentationFile(Request $request, Seminar $seminar)
    {
        $request->validate([
            'presentation_file' => 'required|file|mimes:pdf,ppt,pptx,doc,docx|max:20480', // 20MB
        ]);

        if ($request->hasFile('presentation_file')) {
            $file = $request->file('presentation_file');
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs(
                'seminaires/' . $seminar->id . '/presentations',
                $originalName,
                'public'
            );

            DB::table('fichiers')->insert([
                 'seminaire_id' => $seminar->id,
                 'chemin' => $path,
                 'date_ajout' => now(),
                 'created_at' => now(),
                 'updated_at' => now(),
             ]);

            return back()->with('success', 'Fichier de présentation "' . $originalName . '" téléversé avec succès.');
        }
        return back()->with('error', 'Aucun fichier fourni ou erreur lors du téléversement.');
    }

    /**
     * Affiche la page de confirmation pour la modification de date/heure.
     */
    public function reviewDateTimeChanges(Request $request, Seminar $seminar)
    {
        if (!$seminar->date_presentation || $seminar->date_presentation->isPast()) {
            return redirect()->route('secretary.seminars.show', $seminar->id)
                            ->with('error', 'La date de ce séminaire est déjà passée, il ne peut plus être modifié.');
        }
        if (!in_array($seminar->statut, ['validé', 'publié'])) {
            return redirect()->route('secretary.seminars.show', $seminar->id)
                            ->with('error', 'Ce séminaire ne peut pas être modifié car son statut est : ' . ucfirst($seminar->statut) . '.');
        }

        $errorBagName = 'updateDateTime_' . $seminar->id;
        $validatedData = $request->validateWithBag($errorBagName, [
            'new_date_presentation' => 'required|date|after_or_equal:today',
            'new_heure_presentation' => 'required|date_format:H:i',
        ]);

        return view('secretaire.seminaires_programmes.confirm_datetime_changes', [
            'seminar' => $seminar,
            'new_date_presentation' => $validatedData['new_date_presentation'],
            'new_heure_presentation' => $validatedData['new_heure_presentation'],
        ]);
    }

    /**
     * Applique la modification de date/heure et notifie.
     */
    public function applyDateTimeChanges(Request $request, Seminar $seminar)
    {
        $confirmed_date_presentation = $request->input('confirmed_date_presentation');
        $confirmed_heure_presentation = $request->input('confirmed_heure_presentation');

        $validator = Validator::make([ // Utilisation de la façade Validator
            'date_presentation' => $confirmed_date_presentation,
            'heure_presentation' => $confirmed_heure_presentation,
        ], [
            'date_presentation' => 'required|date|after_or_equal:today',
            'heure_presentation' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            Log::warning("Validation échouée lors de applyDateTimeChanges pour séminaire ID: {$seminar->id}", $validator->errors()->toArray());
            return redirect()->route('secretary.seminars.show', $seminar->id)
                             ->with('error', 'Les données de modification de date/heure proposées sont invalides. Veuillez recommencer le processus.');
        }

        // Stocker les anciennes valeurs AVANT la mise à jour pour la notification
        $ancienneDatePourNotif = $seminar->date_presentation ? $seminar->date_presentation->isoFormat('LL') : 'N/A';
        $ancienneHeurePourNotif = $seminar->heure_presentation ? $seminar->heure_presentation->format('H:i') : 'N/A';
        $etaitPublie = $seminar->statut === 'publié';

        $seminar->date_presentation = $confirmed_date_presentation;
        $seminar->heure_presentation = $confirmed_heure_presentation;
        $seminar->date_limite_resume = Carbon::parse($seminar->date_presentation)->subDays(10)->toDateString();

        if ($seminar->save()) {
            Log::info("Date/heure du séminaire ID {$seminar->id} confirmée et modifiée par la secrétaire ID: " . (Auth::id() ?? 'Système'));

            $nouvelleDatePourNotif = $seminar->date_presentation->isoFormat('LL'); // $seminar->date_presentation est maintenant un objet Carbon (grâce aux casts)
            $nouvelleHeurePourNotif = $seminar->heure_presentation ? $seminar->heure_presentation->format('H:i') : 'N/A';

            // 1. Notifier le présentateur
            if ($seminar->presentateur && $seminar->presentateur->email) {
                try {
                    Mail::to($seminar->presentateur->email)
                       ->send(new SeminarDateTimeChangedPresenterNotification(
                           $seminar,
                           $ancienneDatePourNotif,
                           $ancienneHeurePourNotif,
                           $nouvelleDatePourNotif,
                           $nouvelleHeurePourNotif
                       ));
                    Log::info("Email de notification de changement de date/heure envoyé au présentateur pour séminaire ID {$seminar->id}.");
                } catch (\Exception $e) {
                    Log::error("Erreur d'envoi d'email (présentateur) de modif date/heure (Séminaire ID: {$seminar->id}): " . $e->getMessage());
                }
            } else {
                 Log::warning("Présentateur ou email du présentateur manquant pour séminaire ID: {$seminar->id}. Pas d'email envoyé au présentateur.");
            }

            // 2. Notifier tous les étudiants si le séminaire était déjà publié avant cette modification
            if ($etaitPublie) {
                Log::info("Le séminaire ID: {$seminar->id} était publié. Tentative de notification aux étudiants du changement de date/heure.");
                $students = User::where('role', 'étudiant')->get();
                if ($students->isNotEmpty()) {
                    $studentNotificationCount = 0;
                    foreach ($students as $student) {
                        if ($student->email) {
                            try {
                                Mail::to($student->email)
                                   ->send(new SeminarDateTimeChangedForStudentsNotification(
                                       $seminar,
                                       $nouvelleDatePourNotif,
                                       $nouvelleHeurePourNotif
                                   ));
                                $studentNotificationCount++;
                            } catch (\Exception $e) {
                                Log::error("Erreur d'envoi d'email (étudiant) de modif date/heure (Séminaire ID: {$seminar->id}, Étudiant ID: {$student->id}): " . $e->getMessage());
                            }
                        } else {
                            Log::warning("Email manquant pour l'étudiant ID: {$student->id}. Pas d'email (modif date/heure) envoyé.");
                        }
                    }
                    Log::info("Notification de changement de date/heure (étudiants) envoyée à {$studentNotificationCount} étudiant(s) pour séminaire ID {$seminar->id}.");
                } else {
                    Log::info("Aucun étudiant trouvé pour la notification de changement de date/heure du séminaire ID {$seminar->id}.");
                }
            } else {
                 Log::info("Le séminaire ID: {$seminar->id} n'était pas publié avant la modification de date/heure. Pas de notification aux étudiants pour ce changement spécifique.");
            }

            return redirect()->route('secretary.seminars.show', $seminar->id)
                             ->with('success', 'La date et l\'heure du séminaire "'.$seminar->theme.'" ont été mises à jour. Les notifications pertinentes ont été traitées.');
        } else {
            Log::error("Échec de la sauvegarde lors de la confirmation de modif date/heure du séminaire ID: {$seminar->id}");
            return redirect()->route('secretary.seminars.show', $seminar->id)
                             ->with('error', 'Une erreur est survenue lors de la mise à jour finale de la date/heure.');
        }
    }

    // Optionnel: Méthode pour dépublier un séminaire
    /*
    public function unpublish(Seminar $seminar)
    {
        if ($seminar->statut === 'publié') {
            $seminar->statut = 'validé';
            $seminar->save();
            return redirect()->route('secretary.seminars.scheduled')
                             ->with('success', 'Le séminaire "'.$seminar->theme.'" a été dépublié.');
        }
        return redirect()->route('secretary.seminars.scheduled')
                         ->with('error', 'Ce séminaire n\'est pas actuellement publié.');
    }
    */
}