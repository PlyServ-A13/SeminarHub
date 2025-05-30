{{-- resources/views/etudiant/dashboard.blade.php --}}

<x-app-layout>

     @push('styles')
        <link rel="stylesheet" href="{{ asset('css/etudiant-dashboard.css') }}">
    @endpush

    <x-slot name="header">
        <h2 class="dashboard-header">
            {{ __('Tableau de Bord Étudiant') }}
        </h2>
    </x-slot>

    <div class="dashboard-container">
        <div class="dashboard-content">
            {{-- Message de bienvenue --}}
            <div class="welcome-section">
                <div class="welcome-card">
                    <div class="welcome-title">
                        Bienvenue, {{ $userName }} !
                    </div>
                    <div class="welcome-subtitle">
                        Consultez les séminaires programmés et accédez aux ressources.
                    </div>
                </div>
            </div>

            <div>
                 <form method="GET" action="{{ route('dashboard.etudiant') }}" class="search-form">
                        <div class="search-group">
                            <input type="text" name="search_global" class="search-input"
                                   placeholder="Rechercher par thème ou présentateur..."
                                   value="{{ request('search_upcoming') }}">
                            <button type="submit" class="search-button">
                                Rechercher
                            </button>
                        </div>
                    </form>

                    

            </div>

            {{-- Séminaires à Venir --}}
            <div class="seminaire-section upcoming">
                <div class="seminaire-content">
                    <h3 class="section-title"> ~ Séminaires à Venir ~ </h3>
                    
                    @if($seminairesAVenir->count() > 0)
                        <div class="seminaire-list">
                            @foreach($seminairesAVenir as $seminaire)
                                <div class="seminaire-card">
                                    <h4 class="seminaire-theme">{{ $seminaire->theme }}</h4>
                                    <p class="seminaire-presentateur">
                                        Par : {{ $seminaire->presentateur->prenom ?? '' }} {{ $seminaire->presentateur->name ?? 'N/A' }}
                                    </p>
                                    <p class="seminaire-date">
                                        Date : {{ \Carbon\Carbon::parse($seminaire->date_presentation)->format('d/m/Y') }}
                                        @if($seminaire->heure_presentation)
                                            à {{ date('H:i', strtotime($seminaire->heure_presentation)) }}
                                        @endif
                                    </p>

                                    @if($seminaire->resume && ($seminaire->resume->contenu || $seminaire->resume->chemin_pdf_resume))
                                        <div class="resume-container" x-data="{ open: false }">
                                            <button @click="open = !open" type="button" class="resume-toggle">
                                                <i class="fas fa-eye toggle-icon" x-show="!open"></i>
                                                <i class="fas fa-eye-slash toggle-icon" x-show="open" style="display: none;"></i>
                                                <span x-text="open ? 'Cacher le résumé' : 'Voir le résumé de la présentation'"></span>
                                            </button>

                                            <div x-show="open" class="resume-content">
                                                @if($seminaire->resume->contenu)
                                                    <p class="resume-text">{{ $seminaire->resume->contenu }}</p>
                                                @elseif($seminaire->resume->chemin_pdf_resume)
                                                    <a href="{{ Storage::url($seminaire->resume->chemin_pdf_resume) }}" target="_blank" class="pdf-link">
                                                        <i class="fas fa-file-pdf"></i>
                                                        Consulter le résumé (PDF)
                                                    </a>
                                                @else
                                                    <p class="no-resume">Aucun contenu de résumé disponible.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-result">
                            @if(request('search_global'))
                                Aucun séminaire à venir ne correspond à votre recherche "{{ request('search_global') }}".
                            @else
                                Aucun séminaire programmé pour le moment.
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- Séminaires Passés --}}
            <div class="seminaire-section past">
                <div class="seminaire-content">
                    <h3 class="section-title"> ~ Séminaires Passés ~ </h3>
                    
                    @if($seminairesPasses->count() > 0)
                        <div class="seminaire-list">
                            @foreach($seminairesPasses as $seminaire)
                                <div class="seminaire-card">
                                    <h4 class="seminaire-theme">{{ $seminaire->theme }}</h4>
                                    <p class="seminaire-presentateur">
                                        Par : {{ $seminaire->presentateur->prenom ?? '' }} {{ $seminaire->presentateur->name ?? 'N/A' }}
                                    </p>
                                    <p class="seminaire-date">
                                        Date : {{ \Carbon\Carbon::parse($seminaire->date_presentation)->format('d/m/Y') }}
                                    </p>
                                    @if($seminaire->materials->count() > 0)
                                        <div class="materials-section">
                                            <span class="materials-title">Fichier(s) de présentation :</span>
                                            <ul class="materials-list">
                                            @foreach($seminaire->materials as $fichier)
                                                <li class="material-item">
                                                    <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="material-link">
                                                        <i class="fas fa-download"></i>
                                                        Télécharger {{ basename($fichier->chemin) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p class="no-materials">(Aucun fichier de présentation disponible)</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-result">
                            @if(request('search_global'))
                                Aucun séminaire passé ne correspond à votre recherche "{{ request('search_global') }}".
                            @else
                                Aucun séminaire passé à afficher.
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>