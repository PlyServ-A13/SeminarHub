{{-- resources/views/presentateur/dashboard.blade.php --}}

<x-app-layout> {{-- On ouvre le composant de layout --}}

    {{-- Pour injecter les styles spécifiques à cette page dans le <head> du layout principal --}}
    {{-- Ceci suppose que votre layouts.app.blade.php a un @stack('styles') dans son <head> --}}
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/presentateur-dashboard.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @endpush

    {{-- Si votre app-layout gère un slot nommé "header" (comme c'est souvent le cas avec Jetstream) --}}
    {{-- Vous pouvez le définir ici. Sinon, intégrez votre header directement dans le contenu principal ci-dessous. --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de Bord Présentateur') }}
        </h2>
    </x-slot> --}}

    {{-- Tout le contenu principal de votre page vient directement ici. --}}
    {{-- Ce contenu sera injecté dans la variable $slot du fichier layouts.app.blade.php --}}
    <div class="presenter-dashboard">

        <div class="dashboard-container">
            <aside class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav-menu">
                        <li class="nav-item {{ request()->routeIs('presentateur.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('presentateur.dashboard') }}" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <span class="nav-text">Tableau de bord</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('presentateur.seminaires.index') ? 'active' : '' }}">
                            <a href="{{ route('presentateur.seminaires.index') }}" class="nav-link">
                                <i class="fas fa-calendar-alt nav-icon"></i>
                                <span class="nav-text">Mes Séminaires</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('presentateur.seminaires.create') ? 'active' : '' }}">
                            <a href="{{ route('presentateur.seminaires.create') }}" class="nav-link">
                                <i class="fas fa-file-upload nav-icon"></i>
                                <span class="nav-text">Soumettre un séminaire</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('presentateur.notifications.seminaires_valides') ? 'active' : '' }}">
                            <a href="{{ route('presentateur.notifications.seminaires_valides') }}" class="nav-link">
                                <i class="fas fa-bell nav-icon"></i>
                                <span class="nav-text">Notifications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}" class="nav-link">
                                <i class="fas fa-cog nav-icon"></i>
                                <span class="nav-text">Paramètres</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="nav-link logout-button">
                                    <i class="fas fa-sign-out-alt nav-icon"></i>
                                    <span class="nav-text">Déconnexion</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>

                
            </aside>

            <main class="main-content">
                <div class="content-wrapper">
                    <section class="welcome-section">
                        <div class="welcome-card">
                            <h2 class="welcome-title">Bienvenue sur SeminarHub Présentateur</h2>
                            <p class="welcome-message">
                                Gérez efficacement vos séminaires académiques et partagez les connaissances avec la communauté.
                            </p>
                        </div>
                    </section>

                    <section class="stats-section">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon-container">
                                    <img src="{{ asset('images/1.png') }}" alt="Calendrier" class="stat-icon">
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-title">Séminaires programmés</h3>
                                    <p class="stat-value">{{ $seminairesProgrammesCount ?? 0 }}</p> {{-- Ajout de ?? 0 par sécurité --}}
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon-container">
                                    <img src="{{ asset('images/2.png') }}" alt="Terminé" class="stat-icon">
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-title">Séminaires terminés</h3>
                                    <p class="stat-value">{{ $seminairesTerminesCount ?? 0 }}</p> {{-- Ajout de ?? 0 --}}
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon-container">
                                    <img src="{{ asset('images/3.png') }}" alt="Présentations" class="stat-icon">
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-title">Présentations disponibles</h3>
                                    <p class="stat-value">{{ $presentationsDisponiblesCount ?? 0 }}</p> {{-- Ajout de ?? 0 --}}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="upcoming-seminars"
                            x-data="{
                                initialLimit: 2,
                                showAll: false,
                                seminarsCount: {{ isset($upcomingSeminars) ? $upcomingSeminars->count() : 0 }}
                            }">
                        <div class="section-header">
                            <h3 class="section-title">Séminaires à venir</h3>
                            {{-- Afficher "Voir tout" s'il y a plus de séminaires que la limite initiale ET qu'ils ne sont pas tous affichés --}}
                            <template x-if="seminarsCount > initialLimit && !showAll">
                                <a href="#" @click.prevent="showAll = true" class="view-all-link">Voir tout</a>
                            </template>
                            {{-- Afficher "Voir moins" si tous sont affichés ET qu'il y a plus de séminaires que la limite initiale --}}
                            <template x-if="seminarsCount > initialLimit && showAll">
                                <a href="#" @click.prevent="showAll = false" class="view-all-link">Voir moins</a>
                            </template>
                        </div>

                        @if(isset($upcomingSeminars) && $upcomingSeminars->isNotEmpty())
                            <div class="seminar-list">
                                <ul class="seminar-items">
                                    @foreach($upcomingSeminars as $seminar)
                                    {{--
                                        On utilise $loop->index (qui commence à 0) pour comparer avec initialLimit.
                                        Les éléments sont affichés si:
                                        1. showAll est vrai (l'utilisateur a cliqué sur "Voir tout")
                                        OU
                                        2. L'index de l'élément est inférieur à initialLimit (pour l'affichage initial)
                                    --}}
                                    <li class="seminar-item"
                                        x-show="showAll || {{ $loop->index }} < initialLimit"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        style="display: none;" {{-- Alpine.js gérera l'affichage initial, on cache par défaut pour éviter un flash de contenu --}}
                                        >
                                        <div class="seminar-content">
                                            <div class="seminar-main-info">
                                                <h4 class="seminar-theme">{{ $seminar->theme }}</h4>
                                                <p class="seminar-presenter">
                                                    Présenté par : {{ $seminar->presentateur->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="seminar-date-info">
                                                <p class="seminar-date">
                                                    <span class="date-label">Date de présentation :</span>
                                                    {{ $seminar->date_presentation_formatee ?? $seminar->date_presentation }}
                                                    @if(isset($seminar->heure_presentation_formatee) && $seminar->heure_presentation_formatee)
                                                        , {{ $seminar->heure_presentation_formatee }}
                                                    @elseif($seminar->heure_presentation)
                                                        , {{ \Carbon\Carbon::parse($seminar->heure_presentation)->format('H\hm') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="seminar-meta">
                                            <span class="status-badge {{ $seminar->badge_etat_couleur ?? 'gray' }}">
                                                {{ $seminar->badge_etat_texte ?? 'Indéfini' }}
                                            </span>
                                            @if(isset($seminar->jours_restants) && $seminar->jours_restants >= 0)
                                            <span class="days-remaining">
                                                <i class="far fa-clock"></i> @if($seminar->jours_restants == 0)
                                                    Aujourd'hui
                                                @elseif($seminar->jours_restants == 1)
                                                    Demain (1 jour restant)
                                                @else
                                                    {{ $seminar->jours_restants }} jours restants
                                                @endif
                                            </span>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="no-seminars-message">Aucun séminaire à venir pour le moment.</p>
                        @endif
                    </section>
                </div>
            </main>
        </div>
    </div>

</x-app-layout> {{-- On ferme le composant de layout --}}