{{-- resources/views/presentateur/seminars/show.blade.php --}}
<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/presentateur-seminar-show.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails du Séminaire : {{ Str::limit($seminaire->theme, 60) }}
        </h2>
    </x-slot>

    <div class="seminar-detail-container py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Carte Informations Générales --}}
            <div class="detail-card">
                <h3>Informations Générales</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Thème :</label>
                        <span>{{ $seminaire->theme }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Date de Présentation :</label>
                        <span>{{ $seminaire->date_presentation ? $seminaire->date_presentation->isoFormat('LL') : 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Heure Prévue :</label>
                        <span>{{ $seminaire->heure_presentation ? $seminaire->heure_presentation->format('H:i') : 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Statut :</label>
                        <span class="status-badge {{ $seminaire->statut === 'publié' ? 'status-published' : 
                              ($seminaire->statut === 'validé' ? 'status-validated' : 
                              ($seminaire->statut === 'rejeté' ? 'status-rejected' : 'status-default')) }}">
                            {{ ucfirst($seminaire->statut) }}
                        </span>
                    </div>
                    @if($seminaire->statut === 'rejeté' && $seminaire->raison_refus)
                    <div class="detail-item md:col-span-2">
                        <label>Raison du Rejet :</label>
                        <p class="text-red-700 whitespace-pre-wrap">{{ $seminaire->raison_refus }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Carte Résumé --}}
            <div class="detail-card">
                <h3>Résumé</h3>
                @if($seminaire->resume)
                    <div class="detail-item">
                        <label>Date Limite de Soumission :</label>
                        <span>{{ $seminaire->date_limite_resume ? $seminaire->date_limite_resume->isoFormat('LL') : 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <label>Date d'envoi :</label>
                        <span>{{ $seminaire->resume->date_envoi ? \Carbon\Carbon::parse($seminaire->resume->date_envoi)->isoFormat('LL') : ($seminaire->resume->created_at ? $seminaire->resume->created_at->isoFormat('LL') : 'N/A') }}</span>
                    </div>
                    
                    @if($seminaire->resume->contenu)
                        <div class="detail-item mt-4">
                            <label>Contenu du Résumé :</label>
                            <div class="resume-content-show">
                                {{ $seminaire->resume->contenu }}
                            </div>
                        </div>
                    @endif

                    @if($seminaire->resume->chemin_pdf_resume)
                        <div class="detail-item mt-4">
                            <label>Fichier PDF :</label>
                            <a href="{{ Storage::url($seminaire->resume->chemin_pdf_resume) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-file-pdf mr-2"></i> Consulter le Résumé (PDF)
                            </a>
                        </div>
                    @endif
                @else
                    <div class="detail-item">
                        <label>Date Limite de Soumission :</label>
                        <span>{{ $seminaire->date_limite_resume ? $seminaire->date_limite_resume->isoFormat('LL') : 'N/A' }}</span>
                    </div>
                    <p class="text-gray-500">Aucun résumé n'a encore été soumis pour ce séminaire.</p>
                    
                    @if($seminaire->statut === 'validé' && $seminaire->date_limite_resume && ($seminaire->date_limite_resume->isFuture() || $seminaire->date_limite_resume->isToday()))
                        <div class="mt-4">
                            <a href="{{ route('presentateur.seminaires.resumes.create', $seminaire->id) }}" class="action-btn btn-success">
                                Soumettre le résumé
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Carte Fichiers de Présentation --}}
            <div class="detail-card">
                <h3>Fichiers de Présentation</h3>
                @if($seminaire->materials && $seminaire->materials->count() > 0)
                    <ul class="file-list-show">
                        @foreach($seminaire->materials as $fichier)
                            <li>
                                <a href="{{ Storage::url($fichier->chemin) }}" target="_blank">
                                    <i class="fas fa-file-alt"></i> {{ basename($fichier->chemin) }}
                                </a>
                                <span class="text-xs text-gray-500 ml-2">(Ajouté le: {{ \Carbon\Carbon::parse($fichier->date_ajout ?? $fichier->created_at)->isoFormat('LL') }})</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Aucun fichier de présentation disponible pour le moment</p>
                @endif
            </div>

            <div class="mt-8">
                <a href="{{ route('presentateur.seminaires.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Retour à Mes Séminaires
                </a>
            </div>
        </div>
    </div>
</x-app-layout>