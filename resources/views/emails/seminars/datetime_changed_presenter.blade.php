<x-mail::message>
# Modification de la programmation de votre séminaire

Bonjour {{ $presenterName }},

Veuillez noter que la date et/ou l'heure de votre séminaire **"{{ $seminarTitle }}"** a été modifiée.

**Ancienne programmation :**
- Date : {{ $ancienneDate }}
- Heure : {{ $ancienneHeure }}

**Nouvelle programmation :**
- Date : {{ $nouvelleDate }}
- Heure : {{ $nouvelleHeure }}

La date limite pour la soumission de votre résumé a également pu être ajustée en conséquence.
Vous pouvez consulter les détails de votre séminaire via le lien ci-dessous.

<x-mail::button :url="$seminarUrl">
Voir les détails de mon séminaire
</x-mail::button>

Cordialement,<br>
Le Secrétariat Scientifique de {{ config('app.name') }}
</x-mail::message>