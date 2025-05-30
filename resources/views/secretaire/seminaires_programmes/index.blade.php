{{-- resources/views/secretaire/seminaires_programmes/index.blade.php --}}
<x-app-layout> {{-- Ou votre layout de secrétaire si différent --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des Séminaires Programmés
        </h2>
    </x-slot>

    @push('styles')
        {{-- Si vous avez des styles spécifiques pour cette page --}}
        <style>
            .status-badge { padding: 0.25em 0.6em; font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem; text-transform: uppercase; }
            .status-green { background-color: #DEF7EC; color: #047857; } /* Tailwind green-100 / green-700 */
            .status-red { background-color: #FEE2E2; color: #B91C1C; } /* Tailwind red-100 / red-700 */
            .status-yellow { background-color: #FEF3C7; color: #B45309; } /* Tailwind amber-100 / amber-700 */
            .status-blue { background-color: #DBEAFE; color: #1D4ED8; } /* Tailwind blue-100 / blue-700 */
            .status-gray { background-color: #F3F4F6; color: #4B5563; } /* Tailwind gray-100 / gray-600 */
        </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thème</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Présentateur</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut Séminaire</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résumé (Date Limite)</th>
                                    {{-- NOUVELLE COLONNE (en-tête déjà ajouté par vous) --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fichier Présentation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($seminairesProgrammes as $seminaire)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($seminaire->theme, 35) }}</div> {{-- Un peu moins long pour faire de la place --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $seminaire->presentateur->prenom ?? '' }} {{ $seminaire->presentateur->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($seminaire->presentateur->email ?? '', 25) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $seminaire->date_presentation ? $seminaire->date_presentation->isoFormat('LL') : 'N/A' }}</div> {{-- Utilisation de l'objet Carbon directement --}}
                                            <div class="text-xs text-gray-500">{{ $seminaire->heure_presentation ? $seminaire->heure_presentation->format('H:i') : '' }}</div> {{-- Utilisation de l'objet Carbon directement --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge {{ $seminaire->statut === 'publié' ? 'status-blue' : 'status-yellow' }}">
                                                {{ $seminaire->statut === 'publié' ? 'Publié' : 'Validé' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-{{$seminaire->statut_resume_couleur ?? 'gray'}}"> {{-- ajout de ?? 'gray' --}}
                                                {{ $seminaire->statut_resume }}
                                            </span>
                                            @if($seminaire->date_limite_resume && $seminaire->statut_resume !== 'Soumis')
                                            <div class="text-xs text-gray-500 mt-1">Limite: {{ $seminaire->date_limite_resume->isoFormat('LL') }}</div> {{-- Utilisation de l'objet Carbon directement --}}
                                            @elseif($seminaire->resume && $seminaire->resume->created_at) {{-- Utilisation de created_at pour le résumé si date_envoi n'existe pas --}}
                                            <div class="text-xs text-gray-500 mt-1">Envoyé le: {{ \Carbon\Carbon::parse($seminaire->resume->date_envoi ?? $seminaire->resume->created_at)->isoFormat('LL') }}</div>
                                            @endif
                                        </td>
                                        {{-- CELLULE POUR LE FICHIER DE PRÉSENTATION --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                // S'assurer que date_presentation et heure_presentation sont des objets Carbon
                                                // grâce aux $casts dans votre modèle Seminar.php
                                                $presentationDateTime = null;
                                                if ($seminaire->date_presentation && $seminaire->heure_presentation) {
                                                    // On combine la date et l'heure pour obtenir un objet Carbon complet
                                                    $presentationDateTime = $seminaire->date_presentation->copy()->setTimeFrom($seminaire->heure_presentation);
                                                } elseif ($seminaire->date_presentation) {
                                                    // Si seulement la date est là, on considère le début de la journée
                                                    $presentationDateTime = $seminaire->date_presentation->copy()->startOfDay();
                                                }

                                                // Vrai si la date ET l'heure de présentation sont passées, ou si la date est passée (si l'heure n'est pas définie)
                                                $isSeminarFinished = $presentationDateTime && $presentationDateTime->isPast();
                                            @endphp

                                            @if(in_array($seminaire->statut, ['validé', 'publié']))
                                                @if($isSeminarFinished) {{-- On ne propose l'upload que si le séminaire est terminé --}}
                                                    <a href="{{ route('secretary.seminars.show', $seminaire->id) }}#fichiers-section"
                                                    class="font-medium text-indigo-600 hover:text-indigo-900">
                                                        @if($seminaire->materials && $seminaire->materials->count() > 0)
                                                            Gérer ({{ $seminaire->materials->count() }})
                                                        @else
                                                            Uploader
                                                        @endif
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">(Présentation à venir ou en cours)</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400 italic">-</span>
                                            @endif
                                        </td>
                                        {{-- CELLULE POUR LES ACTIONS --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('secretary.seminars.show', $seminaire->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Gérer</a>
                                           @if($seminaire->statut === 'validé')
                                                <form action="{{ route('secretary.seminars.publish', $seminaire->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir publier ce séminaire ? Cela enverra un e-mail à tous les étudiants.');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 focus:outline-none">Publier</button>
                                                </form>
                                            @elseif($seminaire->statut === 'publié')
                                                {{-- On pourrait mettre un bouton "Dépublier" ici si besoin --}}
                                                <span class="text-xs text-gray-400 italic">(Déjà publié)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- AJUSTEMENT DU COLSPAN --}}
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Aucun séminaire programmé pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $seminairesProgrammes->links() }} {{-- Affichage des liens de pagination --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>