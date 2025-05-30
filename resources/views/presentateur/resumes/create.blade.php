<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Soumettre le Résumé pour : {{ $seminaire->theme }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <form method="POST" action="{{ route('presentateur.seminaires.resumes.store', $seminaire->id) }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Contenu Textuel --}}
                    <div class="mb-6">
                        <label for="contenu" class="block font-medium text-sm text-gray-700">{{ __('Résumé (contenu textuel, optionnel si PDF fourni)') }}</label>
                        <textarea id="contenu" name="contenu" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="8">{{ old('contenu') }}</textarea>
                        @error('contenu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Upload PDF --}}
                    <div class="mb-6">
                        <label for="resume_pdf" class="block font-medium text-sm text-gray-700">{{ __('Ou soumettre un fichier PDF (optionnel si texte fourni)') }}</label>
                        <input id="resume_pdf" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="resume_pdf" accept=".pdf">
                        @error('resume_pdf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @error('error') {{-- Pour l'erreur custom 'au moins un des deux' --}}
                        <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('presentateur.seminaires.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Soumettre le Résumé
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout><x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Soumettre le Résumé pour : {{ $seminaire->theme }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <form method="POST" action="{{ route('presentateur.seminaires.resumes.store', $seminaire->id) }}" enctype="multipart/form-data">                    @csrf

                    {{-- Contenu Textuel --}}
                    <div class="mb-6">
                        <label for="contenu" class="block font-medium text-sm text-gray-700">{{ __('Résumé (contenu textuel, optionnel si PDF fourni)') }}</label>
                        <textarea id="contenu" name="contenu" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="8">{{ old('contenu') }}</textarea>
                        @error('contenu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Upload PDF --}}
                    <div class="mb-6">
                        <label for="resume_pdf" class="block font-medium text-sm text-gray-700">{{ __('Ou soumettre un fichier PDF (optionnel si texte fourni)') }}</label>
                        <input id="resume_pdf" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="resume_pdf" accept=".pdf">
                        @error('resume_pdf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @error('error') {{-- Pour l'erreur custom 'au moins un des deux' --}}
                        <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('presentateur.seminaires.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Soumettre le Résumé
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>