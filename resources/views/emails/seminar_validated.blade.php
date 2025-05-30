{{-- resources/views/emails/seminar_validated.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Séminaire</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { font-size: 1.2em; font-weight: bold; margin-bottom: 15px; color: #2c3e50; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #7f8c8d; }
        ul { list-style-type: none; padding-left: 0; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <p class="header">Bonjour {{ $presenterName }},</p>

        <p>Nous avons le plaisir de vous informer que votre demande de séminaire concernant le thème "<strong>{{ $seminarTheme }}</strong>" a été approuvée !</p>

        <p>Votre intervention est officiellement programmée pour le :<br>
           <strong>{{ $presentationDate }} à {{ $presentationTime }}</strong>.
        </p>

        <p>Afin de finaliser l’organisation et garantir le bon déroulement de votre présentation, nous vous invitons à :</p>
        <ul>
            <li>🔹 Envoyer le résumé de votre présentation au plus tard 10 jours avant la date du séminaire. [cite: 5]</li>
        </ul>
        <p>Ce résumé sera utilisé pour la communication officielle et la préparation logistique de votre session.</p>

        <p>Nous comptons sur votre réactivité et votre professionnalisme pour assurer le succès de votre intervention.</p>
        <p>Restant à votre disposition pour toute question,</p>

        <p class="footer">
            Cordialement,<br>
            L’équipe de coordination des séminaires<br>
            {{-- Vous pouvez remplacer "[Nom du secrétariat]" par une variable de configuration ou un texte fixe --}}
            Secrétariat de l'IMSP (par exemple)
        </p>
    </div>
</body>
</html>