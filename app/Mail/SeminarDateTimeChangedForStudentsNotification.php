<?php

namespace App\Mail;

use App\Models\Seminar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeminarDateTimeChangedForStudentsNotification extends Mailable // Optionnel: implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Seminar $seminar;
    public string $nouvelleDate;
    public string $nouvelleHeure;

    /**
     * Create a new message instance.
     */
    public function __construct(Seminar $seminar, string $nouvelleDate, string $nouvelleHeure)
    {
        $this->seminar = $seminar;
        $this->nouvelleDate = $nouvelleDate;
        $this->nouvelleHeure = $nouvelleHeure;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour : Changement de date/heure pour le séminaire : ' . $this->seminar->theme,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.seminars.datetime_changed_students',
            with: [
                'seminarTitle' => $this->seminar->theme,
                'presenterName' => optional($this->seminar->presentateur)->prenom . ' ' . optional($this->seminar->presentateur)->name,
                'nouvelleDate' => $this->nouvelleDate,
                'nouvelleHeure' => $this->nouvelleHeure,
                'seminarUrl' => route('dashboard.etudiant'), // Lien vers le tableau de bord étudiant
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