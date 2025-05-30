{{-- resources/views/presentateur/seminars/create.blade.php --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seminars-create.css') }}">
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title">
            {{ __('~ SOUMETTRE UN SÉMINAIRE ~') }}
        </h2>
    </x-slot>

    <div class="form-container">
        <form method="POST" action="{{ route('presentateur.seminaires.store') }}">  {{-- Nous gardons ceci pour l'instant --}}
            @csrf  {{-- <<<---- AJOUTEZ CETTE LIGNE ICI --}}

           <div>
                <label for="theme">Thème du Séminaire</label>
                <input id="theme" type="text" name="theme" value="{{ old('theme') }}" required autofocus>
                @error('theme') {{-- Ajout pour afficher les erreurs de validation --}}
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="button-group">
                {{-- Si 'presentateur.dashboard' est une route nommée qui existe, c'est bien. Sinon, mettez l'URL directe. --}}
                <a href="{{ route('presentateur.dashboard') }}" class="cancel-btn">Annuler</a>
                <button type="submit" class="submit-btn">Soumettre</button>
            </div>
        </form>
    </div>
</x-app-layout>