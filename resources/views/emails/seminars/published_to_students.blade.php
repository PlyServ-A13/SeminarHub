<x-mail::message>
# Un nouveau séminaire a été publié !

   Bonjour,

   Un nouveau séminaire passionnant vient d'être ajouté au programme :

   **Thème :** {{ $seminarTitle }}
   **Présenté par :** {{ $presenterName }}
   **Date :** {{ $seminarDate }}@if($seminarTime), à {{ $seminarTime }}@endif

   Nous vous invitons à consulter votre tableau de bord pour plus de détails et pour découvrir les autres séminaires programmés.

   <x-mail::button :url="$seminarUrl">
   Voir le Tableau de Bord Étudiant
   </x-mail::button>

   Cordialement,<br>
   L'équipe {{ config('app.name') }}
</x-mail::message>
