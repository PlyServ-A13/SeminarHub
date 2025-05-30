{{-- resources/views/secretaire/seminar-requests/index.blade.php --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seminar-requests-index.css') }}">
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('~ Gestion des Demandes de Séminaires ~') }}
            </h2>
            {{-- Correction pour le compteur total si pagination --}}
            @if($pendingSeminars && method_exists($pendingSeminars, 'total'))
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                Demande en attente : {{ $pendingSeminars->total() > 0 ? $pendingSeminars->total() : $pendingSeminars->count() }}
            </span>
            @elseif($pendingSeminars)
             <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                Demande en attente : {{ $pendingSeminars->count() }}
            </span>
            @endif
        </div>
    </x-slot>

    {{-- Le conteneur principal pour la page, avec l'initialisation d'Alpine.js pour la modale --}}
    <div class="py-8" x-data="{
        showRejectModal: false,
        rejectionReason: '',
        seminarToRejectId: null,
        seminarToRejectTheme: '',
        rejectFormAction: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('info')) {{-- Ajout pour le message 'info' du rejet --}}
                <div class="mb-6 p-4 bg-blue-100 text-blue-700 rounded-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            <div class="table-container bg-white overflow-hidden shadow-sm sm:rounded-lg"> {{-- Retiré la classe table-container redondante --}}
                <div class="p-6 border-b border-gray-200">
                    @if($pendingSeminars && $pendingSeminars->count() > 0)
                        <div class="overflow-x-auto"> {{-- Renommé table-container en overflow-x-auto pour clarté --}}
                            <table class="custom-table min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="custom-col-1 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Présentateur
                                        </th>
                                        <th scope="col" class="custom-col-2 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thème
                                        </th>
                                        <th scope="col" class="custom-col-3 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Soumis le
                                        </th>
                                        <th scope="col" class="custom-col-4 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pendingSeminars as $seminar)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <span class="text-blue-600 font-medium">
                                                            {{ substr($seminar->presentateur->name ?? 'N/A', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $seminar->presentateur->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $seminar->presentateur->email ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-normal break-words max-w-xs"> {{-- whitespace-normal et break-words pour le thème --}}
                                                <div class="text-sm font-semibold text-gray-900">{{ $seminar->theme }}</div>
                                                {{-- La div pour $seminar->description est commentée car la colonne n'existe pas dans la table seminaires --}}
                                                {{-- <div class="text-sm text-gray-500 line-clamp-2 max-w-xs">
                                                    {{ $seminar->description }}
                                                </div> --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($seminar->created_at)->isoFormat('LL') }}
                                                <span class="block text-xs text-gray-400">
                                                    {{ $seminar->created_at->diffForHumans() }}
                                                </span>
                                            </td>
                                             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex flex-col space-y-3"> {{-- Votre conteneur d'actions --}}

                                                    <div class="form-fields-container"> {{-- Votre conteneur --}}
                                                        <div class="date-time-group"> {{-- Votre conteneur --}}
                                                            <div class="field-container"> {{-- Votre conteneur --}}
                                                                <label for="presentation_date_{{ $seminar->id }}" class="field-label">Date de présentation</label>
                                                                <input type="date"
                                                                    form="validateForm_{{ $seminar->id }}"
                                                                    name="presentation_date"
                                                                    id="presentation_date_{{ $seminar->id }}"
                                                                    required
                                                                    class="field-input @error('presentation_date', 'validate_' . $seminar->id) input-error @enderror"
                                                                    min="{{ now()->addDays(2)->toDateString() }}"
                                                                    value="{{ old('presentation_date') }}">
                                                                @error('presentation_date', 'validate_' . $seminar->id)
                                                                    <p class="error-message">{{ $message }}</p>
                                                                @enderror
                                                            </div>

                                                            <div class="field-container"> {{-- Votre conteneur --}}
                                                                <label for="presentation_time_{{ $seminar->id }}" class="field-label">Heure de présentation</label>
                                                                <input type="time"
                                                                    form="validateForm_{{ $seminar->id }}"
                                                                    name="heure_presentation"
                                                                    id="presentation_time_{{ $seminar->id }}"
                                                                    required
                                                                    class="field-input @error('heure_presentation', 'validate_' . $seminar->id) input-error @enderror"
                                                                    value="{{ old('heure_presentation', '14:00') }}">
                                                                @error('heure_presentation', 'validate_' . $seminar->id)
                                                                    <p class="error-message">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-buttons-container"> {{-- Votre conteneur --}}
                                                        <form id="validateForm_{{ $seminar->id }}"
                                                            action="{{ route('secretary.seminar-requests.validate', $seminar->id) }}"
                                                            method="POST"
                                                            class="form-button-wrapper"> {{-- Votre classe --}}
                                                            @csrf
                                                            <button type="submit" class="validate-button"> {{-- Votre classe --}}
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="button-icon" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                                Valider
                                                            </button>
                                                        </form>

                                                        {{-- MODIFICATION DU BOUTON REJETER --}}
                                                        {{-- Le formulaire original est enlevé, le bouton déclenche la modale --}}
                                                        <div class="form-button-wrapper"> {{-- Votre classe pour l'emplacement --}}
                                                            <button type="button" {{-- Changé en type="button" --}}
                                                                    @click="
                                                                        if (confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')) {
                                                                            seminarToRejectId = {{ $seminar->id }};
                                                                            seminarToRejectTheme = '{{ addslashes(str_replace(["\'", "\n", "\r"], ["\\\'", "\\n", "\\r"], $seminar->theme)) }}';
                                                                            rejectFormAction = '{{ route('secretary.seminar-requests.reject', $seminar->id) }}';
                                                                            rejectionReason = '';
                                                                            showRejectModal = true;
                                                                        }
                                                                    "
                                                                    class="reject-button"> {{-- Votre classe CSS pour le bouton "Rejeter" --}}
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="button-icon" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                                Rejeter
                                                            </button>
                                                        </div>
                                                        {{-- FIN DE LA MODIFICATION DU BOUTON REJETER --}}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($pendingSeminars && method_exists($pendingSeminars, 'hasPages') && $pendingSeminars->hasPages())
                            <div class="mt-6 px-1">
                                {{ $pendingSeminars->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Aucune demande en attente</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Toutes les demandes de séminaires ont été traitées ou il n'y en a aucune de nouvelle.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Ce code est à ajouter une seule fois, de préférence à la fin de la div principale x-data --}}
            {{-- Les classes Tailwind ici sont pour la structure et l'apparence de base de la modale. --}}
            {{-- Vous pouvez les surcharger ou les remplacer par vos propres classes CSS si vous le souhaitez. --}}
            <div x-show="showRejectModal"
                 style="display: none;"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title"
                 role="dialog"
                 aria-modal="true"
                 @keydown.escape.window="showRejectModal = false; rejectionReason = '';">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showRejectModal"
                         @click="showRejectModal = false; rejectionReason = '';"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" {{-- Pas de classes dark ici, pour que le fond soit neutre ou stylisé par vous --}}
                         aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="showRejectModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-bottom transition-all transform bg-white shadow-xl rounded-lg sm:align-middle"> {{-- Pas de classes dark ici pour le panneau principal de la modale --}}
                        
                        <div class="flex justify-between items-center pb-3">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Raison du Refus pour : <span x-text="seminarToRejectTheme" class="font-semibold"></span>
                            </h3>
                            <button @click="showRejectModal = false; rejectionReason = '';" class="text-gray-400 hover:text-gray-600">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form :action="rejectFormAction" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="modal_rejection_reason" class="block text-sm font-medium text-gray-700">Raison du refus (optionnel)</label>
                                <textarea x-model="rejectionReason"
                                          name="raison_refus"
                                          id="modal_rejection_reason"
                                          rows="4"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm text-gray-700"
                                          placeholder="Entrez la raison du refus ici..."></textarea>
                            </div>

                            <div class="pt-2 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Enregistrer le Refus
                                </button>
                                <button type="button"
                                        @click="showRejectModal = false; rejectionReason = '';"
                                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Fin de la Modale --}}
        </div> {{-- Fin de la div x-data --}}
    </div>
</x-app-layout>