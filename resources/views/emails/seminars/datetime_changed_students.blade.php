<x-mail::message>
# Mise à Jour de la Programmation d'un Séminaire

Bonjour,

Veuillez noter qu'un changement de date et/ou d'heure a été effectué pour le séminaire suivant :

**Thème :** {{ $seminarTitle }}
**Présenté par :** {{ $presenterName }}

**Nouvelle programmation :**
- Date : {{ $nouvelleDate }}
- Heure : {{ $nouvelleHeure }}

Merci de prendre note de ce changement.

<x-mail::button :url="$seminarUrl">
Consulter le Tableau de Bord
</x-mail::button>

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>