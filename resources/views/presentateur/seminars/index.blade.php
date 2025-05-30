{{-- resources/views/presentateur/seminaires/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-800 leading-tight">
            {{ __('~ Mes Séminaires Soumis ~') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="mb-6">
                    <a href="{{ route('presentateur.seminaires.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Soumettre un Nouveau Séminaire') }}
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($seminaires->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thème</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Présentation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure Prévue</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Soumission</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($seminaires as $seminaire)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ Str::limit($seminaire->theme, 50) }} {{-- Limite la longueur du thème affiché --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $seminaire->date_presentation ? \Carbon\Carbon::parse($seminaire->date_presentation)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Afficher heure_presentation si elle existe et n'est pas nulle --}}
                                            {{ $seminaire->heure_presentation ? date('H:i', strtotime($seminaire->heure_presentation)) : 'Non spécifiée' }}
                                        </td>
                                        {{-- Si vous avez une colonne description dans la table seminaires --}}
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $seminaire->description ? Str::limit($seminaire->description, 40) : '-' }}
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($seminaire->statut == 'en_attente') bg-yellow-100 text-yellow-800 @endif
                                                @if($seminaire->statut == 'validé') bg-blue-100 text-blue-800 @endif
                                                @if($seminaire->statut == 'publié') bg-green-100 text-green-800 @endif
                                                {{-- Ajoutez d'autres statuts si nécessaire --}}
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $seminaire->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $seminaire->created_at ? $seminaire->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('presentateur.seminaires.show', $seminaire->id) }}" class="text-indigo-600 hover:text-indigo-900">Détails</a>

                                            {{-- NOUVELLE SECTION POUR LE RÉSUMÉ --}}
                                            @php
                                                // Convertir la date de présentation en objet Carbon pour la comparaison
                                                // Assurez-vous que $seminaire->date_presentation est bien une date valide.
                                                // La date actuelle est aussi nécessaire pour la comparaison.
                                                $datePresentation = \Carbon\Carbon::parse($seminaire->date_presentation);
                                                $aujourdhui = \Carbon\Carbon::now();
                                                $limiteDixJours = $datePresentation->copy()->subDays(10); // Date 10 jours avant la présentation
                                            @endphp

                                            {{-- Condition 1: Le séminaire doit être validé --}}
                                            {{-- Condition 2: La date actuelle doit être dans les 10 jours avant la présentation OU avant (si on permet de le soumettre en avance) --}}
                                            {{--            OU la date actuelle doit être après la date "10 jours avant" ET avant la date de présentation. --}}
                                            {{-- Simplifions : on permet de soumettre dès que c'est validé et jusqu'à la date de présentation, --}}
                                            {{-- mais on pourrait afficher un message si ce n'est pas encore "exactement" 10 jours avant. --}}
                                            {{-- Pour être strict sur "Dix jours avant", on vérifierait $aujourdhui->isSameDay($limiteDixJours) ou une fenêtre autour. --}}
                                            {{-- Ici, on va permettre de soumettre si validé ET si on n'a pas dépassé la date de présentation. --}}

                                            @if ($seminaire->statut == 'validé' && $aujourdhui->lte($datePresentation))
                                                {{-- Vérifier si un résumé existe déjà pour ce séminaire (nécessite une relation 'resume' dans le modèle Seminar) --}}
                                               @if ($seminaire->resume)
                                                    <a href="{{ route('presentateur.seminaires.resumes.edit', ['seminaire' => $seminaire->id, 'resume' => $seminaire->resume->id]) }}" class="text-green-600 hover:text-green-900 ml-2">
                                                        Modifier Résumé
                                                    </a>
                                                @else
                                                    <a href="{{ route('presentateur.seminaires.resumes.create', $seminaire->id) }}" class="text-blue-600 hover:text-blue-900 ml-2">
                                                        Ajouter Résumé
                                                    </a>
                                                @endif

                                                {{-- Message informatif sur la fenêtre des 10 jours --}}
                                                @if ($aujourdhui->lt($limiteDixJours))
                                                    <span class="ml-2 text-xs text-gray-500">(Fenêtre de soumission du résumé à partir du {{ $limiteDixJours->format('d/m/Y') }})</span>
                                                @elseif ($aujourdhui->gt($datePresentation))
                                                    <span class="ml-2 text-xs text-red-500">(Date de présentation dépassée)</span>
                                                @else
                                                    <span class="ml-2 text-xs text-green-500">(Vous pouvez soumettre le résumé)</span>
                                                @endif

                                            @elseif ($seminaire->statut == 'en_attente')
                                                <span class="ml-2 text-xs text-yellow-600">(En attente de validation)</span>
                                            @elseif ($seminaire->statut == 'publié')
                                                <span class="ml-2 text-xs text-green-600">(Résumé soumis et publié)</span>
                                            @endif
                                            {{-- FIN DE LA NOUVELLE SECTION --}}
                                        </td>
                                        {{-- ... (fin de la ligne <tr> et de la boucle foreach) ... --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $seminaires->links() }} {{-- Affiche les liens de pagination si paginate() est utilisé dans le contrôleur --}}
                    </div>
                @else
                    <p class="text-gray-700">Vous n'avez soumis aucun séminaire pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>