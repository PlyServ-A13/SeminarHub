<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PresentateurDashboardController;
use App\Http\Controllers\PresentateurSeminaireController;
use App\Http\Controllers\PresentateurMaterielController;
use App\Http\Controllers\PresentateurResumeController;
use App\Http\Controllers\EtudiantDashboardController;
use App\Http\Controllers\SecretaryDashboardController;
use App\Http\Controllers\SecretarySeminarController; // Assurez-vous que le namespace est correct
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation; // Utilisé pour votre route de test
use App\Http\Controllers\CountDashboardController; // Assurez-vous que ce contrôleur existe et que le namespace est correct

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route de dashboard générique de Jetstream/Fortify
    Route::get('/dashboard', function () {
        // Cette route pourrait rediriger vers le dashboard spécifique au rôle
        // ou afficher une page de sélection si un utilisateur a plusieurs rôles (plus complexe)
        // Pour l'instant, elle mène à la vue 'dashboard' par défaut.
        // Il est souvent préférable de la supprimer ou de la faire pointer vers une logique
        // de redirection basée sur le rôle après la connexion.
        // Par exemple, dans votre LoginResponse de Fortify.
        if (Auth::user()->role === 'étudiant') {
            return redirect()->route('dashboard.etudiant');
        } elseif (Auth::user()->role === 'présentateur') {
            return redirect()->route('presentateur.dashboard');
        } elseif (Auth::user()->role === 'secretaire') {
            return redirect()->route('secretary.dashboard');
        }
        return view('dashboard'); // Fallback
    })->name('dashboard');
});


// Routes pour la Secrétaire Scientifique
Route::middleware(['auth:sanctum', 'verified', 'role:secretaire'])
    ->prefix('secretaire')
    ->name('secretary.') // Préfixe pour les noms de route : secretary.quelquechose
    ->group(function () {
        Route::get('/dashboard', [SecretaryDashboardController::class, 'index'])->name('dashboard');

        // Gestion des demandes de séminaire
        Route::get('/demandes-seminaires', [SecretarySeminarController::class, 'indexRequests'])->name('seminar-requests.index');
        Route::post('/demandes-seminaires/{seminar}/valider', [SecretarySeminarController::class, 'validateRequest'])->name('seminar-requests.validate');
        Route::post('/demandes-seminaires/{seminar}/rejeter', [SecretarySeminarController::class, 'rejectRequest'])->name('seminar-requests.reject');

        // Gestion des séminaires programmés
        Route::get('/seminaires-programmes', [SecretarySeminarController::class, 'indexScheduled'])->name('seminars.scheduled');
        Route::get('/seminaires-programmes/{seminar}', [SecretarySeminarController::class, 'show'])->name('seminars.show');
        Route::post('/seminaires-programmes/{seminar}/publier', [SecretarySeminarController::class, 'publish'])->name('seminars.publish');
        Route::post('/seminaires-programmes/{seminar}/uploader-fichier', [SecretarySeminarController::class, 'uploadPresentationFile'])->name('seminars.uploadFile');
        
        // ROUTE POUR LA MISE À JOUR DE DATE/HEURE (confirmée comme étant PUT)
        Route::put('/seminaires-programmes/{seminar}/update-datetime', [SecretarySeminarController::class, 'updateDateTime'])->name('seminars.updateDateTime');
        Route::post('/seminaires-programmes/{seminar}/review-datetime-changes', [SecretarySeminarController::class, 'reviewDateTimeChanges'])->name('seminars.reviewDateTimeChanges');
        Route::post('/seminaires-programmes/{seminar}/apply-datetime-changes', [SecretarySeminarController::class, 'applyDateTimeChanges'])->name('seminars.applyDateTimeChanges');
        
        // Optionnel : Route pour marquer le résumé comme reçu
        // Route::post('/seminaires-programmes/{seminar}/marquer-resume-recu', [SecretarySeminarController::class, 'markSummaryReceived'])->name('seminars.markSummaryReceived');
    });

// Routes pour les Présentateurs
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:présentateur' // Attention à l'accent ici si votre middleware 'role' le gère ainsi
])->prefix('presentateur')->name('presentateur.')->group(function () {

    Route::get('/dashboard', [PresentateurDashboardController::class, 'index'])->name('dashboard');

    // Gestion des séminaires par le présentateur
    Route::prefix('seminaires')->name('seminaires.')->group(function () {
        Route::get('/', [PresentateurSeminaireController::class, 'index'])->name('index');
        Route::get('/creer', [PresentateurSeminaireController::class, 'create'])->name('create');
        Route::post('/', [PresentateurSeminaireController::class, 'store'])->name('store');
        Route::get('/{seminaire}', [PresentateurSeminaireController::class, 'show'])->name('show');

        // Gestion des résumés par le présentateur
        Route::prefix('/{seminaire}/resumes')->name('resumes.')->group(function () {
            Route::get('/creer', [PresentateurResumeController::class, 'create'])->name('create');
            Route::post('/', [PresentateurResumeController::class, 'store'])->name('store');
            Route::get('/{resume}/modifier', [PresentateurResumeController::class, 'edit'])->name('edit');
            Route::put('/{resume}', [PresentateurResumeController::class, 'update'])->name('update');
        });
    });

    // Gestion des matériels (fichiers de présentation) par le présentateur
    Route::prefix('materiels')->name('materiels.')->group(function () {
        Route::get('/', [PresentateurMaterielController::class, 'index'])->name('index');
        Route::get('/soumettre', [PresentateurMaterielController::class, 'create'])->name('create');
        // Ajoutez ici la route POST pour le store des matériels si nécessaire
    });

    // Page de notifications pour les séminaires validés du présentateur
    // Assurez-vous que le namespace du contrôleur est correct
    Route::get('/notifications-seminaires-valides', [\App\Http\Controllers\Presentateur\NotificationController::class, 'listValidatedSeminarNotifications'])->name('notifications.seminaires_valides');
});


// Route pour le Dashboard Étudiant
Route::get('/etudiant/dashboard', [EtudiantDashboardController::class, 'index'])
    ->name('dashboard.etudiant') // Le nom de route est 'dashboard.etudiant'
    ->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:étudiant']); // Attention à l'accent


// Route de test pour la résolution de l'action Fortify (à garder pour débogage si besoin)
Route::get('/test-action', function () {
    try {
        $actionInstance = app(UpdatesUserProfileInformation::class);
        dd('ACTION RÉSOLUE AVEC SUCCÈS!', $actionInstance);
    } catch (\Exception $e) {
        dd('ERREUR LORS DE LA RÉSOLUTION DE L ACTION:', $e);
    }
});

// Route pour votre tableau de bord récapitulatif (CountDashboardController)
// Assurez-vous que ce contrôleur existe et que le namespace est correct.
// S'il n'est pas dans App\Http\Controllers, précisez le namespace complet.
// Exemple : Route::get('/mon-tableau-de-bord-recap', [App\Http\Controllers\QuelquePart\CountDashboardController::class, 'afficherRecapitulatif'])->name('dashboard.recap');
Route::get('/mon-tableau-de-bord-recap', [CountDashboardController::class, 'afficherRecapitulatif'])
    ->name('dashboard.recap')
    ->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']); // Ajoutez le middleware de rôle si nécessaire