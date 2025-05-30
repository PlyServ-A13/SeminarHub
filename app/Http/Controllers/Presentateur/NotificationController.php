<?php

namespace App\Http\Controllers\Presentateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seminar; // Assurez-vous d'importer votre modèle Seminar
use Carbon\Carbon;      // Pour formater les dates

class NotificationController extends Controller
{
    public function listValidatedSeminarNotifications()
    {
        $user = Auth::user();

        // Récupérer tous les séminaires de l'utilisateur connecté qui ont le statut 'validé'
        $validatedSeminars = Seminar::where('presentateur_id', $user->id)
                                    ->where('statut', 'validé')
                                    ->orderBy('date_presentation', 'desc') // Optionnel: trier par date
                                    ->get();

        return view('presentateur.notifications_valides', compact('validatedSeminars'));
    }
}