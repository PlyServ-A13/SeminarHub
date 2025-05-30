<?php

namespace App\Mail;

use App\Models\Seminar; // Assurez-vous que le chemin vers votre modèle Seminar est correct
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon; // Pour le formatage de la date

class SeminarValidatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Seminar $seminar; // Rendre la variable $seminar publique pour y accéder dans la vue

    /**
     * Create a new message instance.
     */
    public function __construct(Seminar $seminar)
    {
        $this->seminar = $seminar;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Formater la date pour l'objet de l'email
        $formattedDate = Carbon::parse($this->seminar->date_presentation)->isoFormat('LL'); // ex: 23 mai 2025
        $formattedTime = Carbon::parse($this->seminar->heure_presentation)->format('H\hi'); // ex: 14h00

        return new Envelope(
            subject: 'Confirmation de votre Séminaire – ' . $formattedDate . ' à ' . $formattedTime,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Les variables passées ici seront disponibles dans la vue Blade de l'email
        // Nous passons l'objet $seminar entier, mais vous pouvez aussi passer des variables spécifiques.
        return new Content(
            view: 'emails.seminar_validated', // Le nom de votre vue Blade pour l'email
            with: [
                'presenterName' => $this->seminar->presentateur->name, // Assurez-vous que la relation 'presentateur' existe
                'presentationDate' => Carbon::parse($this->seminar->date_presentation)->isoFormat('dddd D MMMM YYYY'), // ex: mercredi 23 mai 2025
                'presentationTime' => Carbon::parse($this->seminar->heure_presentation)->format('H\hi'), // ex: 14h00
                'seminarTheme' => $this->seminar->theme, // Si vous voulez l'ajouter au corps de l'email
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}