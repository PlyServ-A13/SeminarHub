{{-- resources/views/secretaire/seminaires_programmes/confirm_datetime_changes.blade.php --}}
<x-app-layout>
    @push('styles')
        <link href="{{ asset('css/secretaire-confirm-changes.css') }}" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirmer la Modification de Date/Heure pour : {{ Str::limit($seminar->theme, 40) }}
        </h2>
    </x-slot>

    <div class="confirmation-container py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="detail-section">
                <h3>Veuillez confirmer les modifications suivantes :</h3>

                <div class="confirmation-grid">
                    <div class="confirmation-column">
                        <h4>Date Actuelle :</h4>
                        <p>{{ $seminar->date_presentation ? $seminar->date_presentation->isoFormat('LL') : 'N/A' }}</p>
                        <h4>Heure Actuelle :</h4>
                        <p>{{ $seminar->heure_presentation ? $seminar->heure_presentation->format('H:i') : 'N/A' }}</p>
                    </div>
                    <div class="confirmation-column">
                        <h4>Nouvelle Date Proposée :</h4>
                        <p class="highlight-new">{{ \Carbon\Carbon::parse($new_date_presentation)->isoFormat('LL') }}</p>
                        <h4>Nouvelle Heure Proposée :</h4>
                        <p class="highlight-new">{{ \Carbon\Carbon::parse($new_heure_presentation)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="confirmation-message">
                    En validant, la date et l'heure du séminaire seront mises à jour. Le présentateur et tous les étudiants (si le séminaire est déjà publié) seront notifiés par e-mail des modifications.
                </div>

                <form action="{{ route('secretary.seminars.applyDateTimeChanges', $seminar->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="confirmed_date_presentation" value="{{ $new_date_presentation }}">
                    <input type="hidden" name="confirmed_heure_presentation" value="{{ $new_heure_presentation }}">

                    <div class="confirmation-actions">
                        <a href="{{ route('secretary.seminars.show', $seminar->id) }}" class="btn btn-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            Valider les Modifications et Notifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>