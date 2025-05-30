{{-- resources/views/secretaire/seminaires_programmes/show.blade.php --}}
<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/secretaire-seminar-show.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        {{-- <style>
            .css-test {
                position: fixed;
                top: 10px;
                right: 10px;
                background: lime;
                padding: 10px;
                z-index: 9999;
            }
        </style> --}}
    @endpush

    {{-- <div class="css-test">
        @if(File::exists(public_path('css/secretaire-seminar-show.css')))
            CSS trouvé et chargé!
        @else
            ERREUR: CSS non trouvé à {{ public_path('css/secretaire-seminar-show.css') }}
        @endif
    </div> --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion du Séminaire : {{ Str::limit($seminar->theme, 50) }}
        </h2>
    </x-slot>

    <div class="seminar-detail-container py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages de session --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Section 1: Informations Générales --}}
            <section class="detail-section">
                <h3 class="detail-title">Informations Générales</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Thème :</label>
                        <p>{{ $seminar->theme }}</p>
                    </div>
                    <div class="detail-item">
                        <label>Présentateur :</label>
                        <p>{{ $seminar->presentateur->prenom ?? '' }} {{ $seminar->presentateur->name ?? 'N/A' }} ({{ $seminar->presentateur->email ?? 'N/A' }})</p>
                    </div>
                    <div class="detail-item">
                        <label>Date et Heure :</label>
                        <p>{{ $seminar->date_presentation ? $seminar->date_presentation->isoFormat('LLLL') : 'N/A' }}</p>
                    </div>
                    <div class="detail-item">
                        <label>Statut du séminaire :</label>
                        <p>
                            <span class="status-badge {{ $seminar->statut === 'publié' ? 'status-published' : ($seminar->statut === 'validé' ? 'status-validated' : 'status-default') }}">
                                {{ ucfirst($seminar->statut) }}
                            </span>
                        </p>
                    </div>
                </div>
            </section>

            {{-- Section 4: Modifier la Date et l'Heure du Séminaire --}}
            @php
                $isSeminarNotPast = $seminar->date_presentation && method_exists($seminar->date_presentation, 'isPast') && !$seminar->date_presentation->isPast();
            @endphp

            {{-- Le formulaire s'affiche si le statut est 'validé' OU 'publié' ET si la date n'est pas passée --}}
            @if(in_array($seminar->statut, ['validé', 'publié']) && $isSeminarNotPast)
            <section class="detail-section mt-6">
                <h3 class="detail-title">Modifier la Date et l'Heure de Présentation</h3>
                <form action="{{ route('secretary.seminars.reviewDateTimeChanges', $seminar->id) }}" method="POST">
                    @csrf
                    <div class="date-time-form-grid"> {{-- Assurez-vous que cette classe CSS est définie --}}
                        {{-- Champ Nouvelle Date --}}
                        <div>
                            <label for="new_date_presentation" class="block text-sm font-medium text-gray-700">Nouvelle date :</label>
                            <input type="date" name="new_date_presentation" id="new_date_presentation"
                                   value="{{ old('new_date_presentation', ($seminar->date_presentation ? $seminar->date_presentation->format('Y-m-d') : '')) }}"
                                   min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" required>
                            @error('new_date_presentation', 'updateDateTime_' . $seminar->id)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champ Nouvelle Heure --}}
                        <div>
                            <label for="new_heure_presentation" class="block text-sm font-medium text-gray-700">Nouvelle heure :</label>
                            <input type="time" name="new_heure_presentation" id="new_heure_presentation"
                                   value="{{ old('new_heure_presentation', ($seminar->heure_presentation ? $seminar->heure_presentation->format('H:i') : '')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" required>
                            @error('new_heure_presentation', 'updateDateTime_' . $seminar->id)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bouton Vérifier --}}
                        <div class="flex items-end">
                            <button type="submit" class="btn btn-orange w-full"> {{-- Assurez-vous que .btn et .btn-orange sont définis --}}
                                Vérifier
                            </button>
                        </div>
                    </div>
                </form>
            </section>
            {{-- Message si les conditions ne sont pas remplies pour la modification --}}
            @elseif(!in_array($seminar->statut, ['validé', 'publié']) || !$isSeminarNotPast)
            <section class="detail-section mt-6">
                 <p class="text-sm text-gray-500">
                    @if(!in_array($seminar->statut, ['validé', 'publié']))
                        La date et l'heure de ce séminaire ne peuvent pas être modifiées car son statut est '{{ ucfirst($seminar->statut) }}' (il doit être 'Validé' ou 'Publié').
                    @elseif(!$isSeminarNotPast && $seminar->date_presentation)
                        La date et l'heure de ce séminaire ne peuvent plus être modifiées car la date de présentation ({{ $seminar->date_presentation->isoFormat('LL') }}) est passée ou se déroule aujourd'hui.
                    @else
                         La modification de la date et l'heure n'est pas possible pour ce séminaire actuellement (raison inconnue ou date non définie).
                    @endif
                 </p>
            </section>
            @endif

            {{-- Section 2: Gestion du Résumé --}}
            <section class="detail-section">
                <h3 class="detail-title">Gestion du Résumé</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Statut du résumé :</label>
                        <p><span class="status-badge">{{ $seminar->statut_resume }}</span></p>
                    </div>
                    @if($seminar->date_limite_resume)
                    <div class="detail-item">
                        <label>Date limite de soumission du résumé :</label>
                        <p>{{ $seminar->date_limite_resume->isoFormat('LL') }}</p>
                    </div>
                    @endif
                </div>
                @if($seminar->resume)
                    <div class="mt-4">
                        <h4 class="text-md font-semibold mb-2">Contenu du résumé soumis :</h4>
                        @if($seminar->resume->contenu)
                            <div class="resume-content">
                                {{ $seminar->resume->contenu }}
                            </div>
                        @elseif($seminar->resume->chemin_pdf_resume)
                            <a href="{{ Storage::url($seminar->resume->chemin_pdf_resume) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-file-pdf mr-2"></i> Consulter le résumé (PDF)
                            </a>
                        @else
                            <p class="text-gray-500">Aucun contenu de résumé à afficher.</p>
                        @endif
                    </div>
                @else
                    <p class="mt-4 text-gray-500">Aucun résumé n'a encore été soumis pour ce séminaire.</p>
                @endif
            </section>

            {{-- Section 3: Gestion des Fichiers de Présentation --}}
            <section class="detail-section" id="fichiers-section">
                <h3 class="detail-title">Fichiers de Présentation</h3>
                @php
                    $presentationDate = $seminar->date_presentation;
                    $isPastOrTodayForFiles = $presentationDate && ($presentationDate->isPast() || $presentationDate->isToday());
                @endphp

                @if(in_array($seminar->statut, ['validé', 'publié']) && $isPastOrTodayForFiles)
                    <form action="{{ route('secretary.seminars.uploadFile', $seminar->id) }}" method="POST" enctype="multipart/form-data" class="mb-6 pb-6 border-b">
                        @csrf
                        <div class="mb-4">
                            <label for="presentation_file" class="block text-sm font-medium text-gray-700 mb-1">Ajouter un fichier de présentation :</label>
                            <input type="file" name="presentation_file" id="presentation_file" class="file-input" required>
                            @error('presentation_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Téléverser le fichier
                        </button>
                    </form>
                @else
                    <div class="p-4 mb-6 text-sm text-gray-700 bg-gray-100 rounded-md border border-gray-200">
                        @if(!in_array($seminar->statut, ['validé', 'publié']))
                            <p>L'upload de fichiers n'est pas disponible pour les séminaires avec le statut actuel ({{ $seminar->statut }}).</p>
                        @elseif($presentationDate && !$isPastOrTodayForFiles)
                            <p>L'upload de fichiers de présentation sera disponible une fois la date du séminaire ({{ $presentationDate->isoFormat('LL') }}) atteinte ou passée.</p>
                        @else
                             <p>L'upload de fichiers n'est pas disponible pour ce séminaire actuellement.</p>
                        @endif
                    </div>
                @endif

                <h4 class="text-md font-semibold mb-2 {{ (in_array($seminar->statut, ['validé', 'publié']) && $isPastOrTodayForFiles) ? 'mt-0' : 'mt-6' }}">Fichiers déjà téléversés :</h4>
                @if($seminar->materials && $seminar->materials->count() > 0)
                    <ul class="file-list">
                        @foreach($seminar->materials as $fichier)
                            <li>
                                <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-file-alt mr-2"></i> {{ basename($fichier->chemin) }}
                                    <span class="text-xs text-gray-500 ml-2">(Ajouté le: {{ \Carbon\Carbon::parse($fichier->date_ajout ?? $fichier->created_at)->isoFormat('LL') }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Aucun fichier de présentation n'a encore été téléversé pour ce séminaire.</p>
                @endif
            </section>

            <div class="mt-8 text-sm">
                <a href="{{ route('secretary.seminars.scheduled') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Retour à la liste des séminaires programmés</a>
            </div>
        </div>
    </div>
</x-app-layout>