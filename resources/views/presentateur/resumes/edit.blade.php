{{-- resources/views/presentateur/resumes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le Résumé pour : {{ $seminaire->theme }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <form method="POST" action="{{ route('presentateur.seminaires.resumes.update', ['seminaire' => $seminaire->id, 'resume' => $resume->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Important pour la mise à jour --}}

                    {{-- Contenu Textuel --}}
                    <div class="mb-6">
                        <label for="contenu" class="block font-medium text-sm text-gray-700">{{ __('Résumé (contenu textuel)') }}</label>
                        <textarea id="contenu" name="contenu" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="8">{{ old('contenu', $resume->contenu) }}</textarea>
                        @error('contenu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Upload PDF --}}
                    <div class="mb-6">
                        <label for="resume_pdf" class="block font-medium text-sm text-gray-700">{{ __('Remplacer/Ajouter un fichier PDF') }}</label>
                        @if($resume->chemin_pdf_resume)
                            <p class="text-sm text-gray-600 mt-1">Fichier actuel : <a href="{{ Storage::url($resume->chemin_pdf_resume) }}" target="_blank" class="text-indigo-600 hover:underline">{{ basename($resume->chemin_pdf_resume) }}</a></p>
                            <p class="text-xs text-gray-500">Laisser vide pour conserver le fichier actuel. Téléverser un nouveau fichier pour le remplacer.</p>
                        @endif
                        <input id="resume_pdf" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="resume_pdf" accept=".pdf">
                        @error('resume_pdf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                     {{-- Option pour supprimer le PDF si on remplit le texte --}}
                    @if($resume->chemin_pdf_resume && !$resume->contenu)
                    <div class="mb-6">
                        <label for="supprimer_pdf_existant_si_texte" class="inline-flex items-center">
                            <input type="checkbox" id="supprimer_pdf_existant_si_texte" name="supprimer_pdf_existant_si_texte" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Si vous ajoutez du contenu textuel, supprimer le PDF existant.') }}</span>
                        </label>
                    </div>
                    @endif


                    @error('error') <p class="text-sm text-red-600 mb-4">{{ $message }}</p> @enderror

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('presentateur.seminaires.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Mettre à Jour le Résumé
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>