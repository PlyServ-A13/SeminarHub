<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Facade pour les logs
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if (!$user) {
            // Log si l'utilisateur n'est pas trouvé après l'inscription (ce qui serait anormal)
            Log::error('RegisterResponse: Utilisateur non trouvé après l\'inscription.');
            return redirect(config('fortify.home'));
        }

        $userRole = $user->role;

        // Log pour afficher le rôle de l'utilisateur tel qu'il est récupéré
        Log::info('RegisterResponse - Rôle de l\'utilisateur détecté : \'' . $userRole . '\'');

        // Détermination de la page d'accueil en fonction du rôle
        $home = match ($userRole) {
            'présentateur' => route('presentateur.dashboard'),
            'étudiant'     => route('dashboard.etudiant'),
            'secretaire'   => route('dashboard.secretaire'),
            default        => config('fortify.home'),
        };

        // Log pour afficher l'URL vers laquelle la redirection est prévue
        Log::info('RegisterResponse - Redirection prévue vers : ' . $home);

        // Log d'avertissement si le rôle ne correspond à aucun cas spécifique,
        // indiquant une redirection vers la page par défaut de Fortify.
        if ($userRole !== 'présentateur' && $userRole !== 'étudiant' && $userRole !== 'secretaire') {
            Log::warning('RegisterResponse - Le rôle (\'' . $userRole . '\') n\'a pas correspondu à un cas spécifique, redirection vers la page par défaut de Fortify : ' . config('fortify.home'));
        }

        // Redirection de l'utilisateur
        return redirect()->intended($home);
        // Pour un test plus direct sans la logique "intended", vous pouvez décommenter la ligne suivante
        // et commenter celle du dessus :
        // return redirect($home);
    }
}
