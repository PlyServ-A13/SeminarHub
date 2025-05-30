{{-- resources/views/presentateur/notifications_valides.blade.php --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/presentateur-notification.css') }}">
@endpush

<x-app-layout>
    <header class="header">
        <div class="">
            <h2 class="notifications-title">
                SeminarHub - Notifications
            </h2>
        </div>
    </header>

    <div class="dashboard-layout-container">
        <div class="dashboard-content">
            <div class="container">
                <div class="notifications-card">
                    <h3 class="notifications-heading">
                        <i class="fas fa-bell mr-2"></i> Notifications de Séminaires Validés
                    </h3>

                    @if($validatedSeminars->isNotEmpty())
                        <div class="notifications-list">
                            @foreach ($validatedSeminars as $seminar)
                                @php
                                    $datePresentation = \Carbon\Carbon::parse($seminar->date_presentation)->format('d/m/Y');
                                    $heurePresentation = \Carbon\Carbon::parse($seminar->heure_presentation)->format('H:i');
                                    $dateLimiteResume = $seminar->date_limite_resume ? \Carbon\Carbon::parse($seminar->date_limite_resume)->format('d/m/Y') : 'N/A';
                                @endphp
                                <div class="notification-item">
                                    <p>
                                        Votre présentation <strong class="highlight">{{ $seminar->theme }}</strong>
                                        est validée pour le <strong class="highlight">{{ $datePresentation }}</strong>
                                        à <strong class="highlight">{{ $heurePresentation }}</strong>.
                                    </p>
                                    <p class="note">
                                        Résumé à soumettre avant le <strong>{{ $dateLimiteResume }}</strong>.
                                    </p>
                                    <p class="meta">
                                        Demandé le : {{ $seminar->created_at->format('d/m/Y') }}
                                        @if($seminar->updated_at != $seminar->created_at)
                                            | Mis à jour : {{ $seminar->updated_at->format('d/m/Y H:i') }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-notifications">Aucun séminaire validé pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
