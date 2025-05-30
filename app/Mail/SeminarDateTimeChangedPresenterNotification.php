<?php

namespace App\Mail;

use App\Models\Seminar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarDateTimeChangedPresenterNotification extends Mailable // Optionnel: implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Seminar $seminar;
    public string $ancienneDate;
    public string $ancienneHeure;
    public string $nouvelleDate;
    public string $nouvelleHeure;

    /**
     * Create a new message instance.
     */
    public function __construct(Seminar $seminar, string $ancienneDate, string $ancienneHeure, string $nouvelleDate, string $nouvelleHeure)
    {
        $this->seminar = $seminar;
        $this->ancienneDate = $ancienneDate;
        $this->ancienneHeure = $ancienneHeure;
        $this->nouvelleDate = $nouvelleDate;
        $this->nouvelleHeure = $nouvelleHeure;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Modification de la date/heure de votre séminaire : ' . $this->seminar->theme,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.seminars.datetime_changed_presenter',
            with: [
                'seminarTitle' => $this->seminar->theme,
                'presenterName' => $this->seminar->presentateur->prenom . ' ' . $this->seminar->presentateur->name,
                'ancienneDate' => $this->ancienneDate,
                'ancienneHeure' => $this->ancienneHeure,
                'nouvelleDate' => $this->nouvelleDate,
                'nouvelleHeure' => $this->nouvelleHeure,
                'seminarUrl' => route('presentateur.seminaires.show', $this->seminar->id), // Lien vers la page du séminaire pour le présentateur
            ],
        );
    }

    /**
     * Get the attachments for the message.
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}