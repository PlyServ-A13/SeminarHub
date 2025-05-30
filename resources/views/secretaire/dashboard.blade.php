{{-- resources/views/secretaire/dashboard.blade.php --}}
<x-app-layout>

   @push('styles')
        <link rel="stylesheet" href="{{ asset('css/secretaire-dashboard.css') }}">
    @endpush

    <x-slot name="header">
        <h2 class="header font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' ~ Tableau de Bord - Secrétaire Scientifique ~ ') }}
        </h2>
    </x-slot>

    <div class="dashboard-container py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Section 1: Tâches en Attente (Aperçu Rapide) --}}
            <div class="quick-overview">
                <h3>Aperçu et Tâches en Attente</h3>
                
               {{-- Section 1: Tâches en Attente sous forme de cartes --}}
                
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-label">Nouvelles demandes de séminaire à valider</div>
                        <div class="stat-value">{{ $pendingRequestsCount ?? 0 }}</div>
                        <a href="{{ route('secretary.seminar-requests.index') }}" class="stat-action">
                            Gérer les demandes
                        </a>
                    </div>

                    <div class="stat-card">
                        <div class="stat-label">Résumés de séminaire attendus</div>
                        <div class="stat-value">{{ $upcomingSummariesDueCount ?? 0 }}</div>
                        <a href="{{ route('secretary.seminars.scheduled') }}#summaries" class="stat-action">
                            Suivre les résumés
                        </a>
                    </div>

                    <div class="stat-card">
                        <div class="stat-label">Programme de séminaires à publier</div>
                        <div class="stat-value">{{ $seminarsToPublishCount ?? 0 }}</div>
                        <a href="{{ route('secretary.seminars.scheduled') }}#publishing" class="stat-action">
                            Gérer les publications
                        </a>
                    </div>

                    <div class="stat-card">
                        <div class="stat-label">Fichiers de séminaires terminés à mettre en ligne</div>
                        {{-- MISE À JOUR DE LA VARIABLE CI-DESSOUS --}}
                        <div class="stat-value">{{ $completedSeminarsNeedingFilesCount ?? 0 }}</div>
                        {{-- Vous pourriez vouloir un lien différent ici, par exemple vers une section pour gérer les fichiers des séminaires passés --}}
                        <a href="{{ route('secretary.seminars.scheduled') }}#files" class="stat-action"> 
                            Gérer les fichiers
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section 2: Actions Principales --}}
            <div class="action-cards">
                <div class="action-card">
                    <h4>Gestion des Demandes de Séminaire</h4>
                    <p>Consulter, valider ou rejeter les nouvelles propositions de séminaires.</p>
                    <a href="{{ route('secretary.seminar-requests.index') }}" class="card-link">
                        Accéder
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <div class="action-card">
                    <h4>Gestion des Séminaires Programmés</h4>
                    <p>Suivre les résumés, publier les informations, et gérer les fichiers de présentation post-événement.</p>
                    <a href="{{ route('secretary.seminars.scheduled') }}" class="card-link">
                        Accéder
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>