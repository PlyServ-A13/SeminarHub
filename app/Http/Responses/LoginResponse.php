<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        $userRole = Auth::user()->role;

        $home = match ($userRole) {
            'présentateur' => route('presentateur.dashboard'), // <-- Correction ici
            'étudiant' => route('dashboard.etudiant'),
            'secretaire' => route('secretary.dashboard'),
            default => config('fortify.home'),
        };

        return redirect()->intended($home);
    }
}