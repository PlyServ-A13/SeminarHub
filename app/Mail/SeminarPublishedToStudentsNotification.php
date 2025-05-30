<?php

namespace App\Mail;

   use App\Models\Seminar;
   use Illuminate\Bus\Queueable;
   use Illuminate\Contracts\Queue\ShouldQueue; // Optionnel pour la mise en file d'attente
   use Illuminate\Mail\Mailable;
   use Illuminate\Mail\Mailables\Content;
   use Illuminate\Mail\Mailables\Envelope;
   use Illuminate\Queue\SerializesModels;

   class SeminarPublishedToStudentsNotification extends Mailable // implements ShouldQueue
   {
       use Queueable, SerializesModels;

       public Seminar $seminar;

       public function __construct(Seminar $seminar)
       {
           $this->seminar = $seminar;
       }

       public function envelope(): Envelope
       {
           return new Envelope(
               subject: 'Nouveau Séminaire Publié : ' . $this->seminar->theme,
           );
       }

       public function content(): Content
       {
           $this->seminar->loadMissing('presentateur'); // S'assurer que le présentateur est chargé

           return new Content(
               markdown: 'emails.seminars.published_to_students',
               with: [
                   'seminarTitle' => $this->seminar->theme,
                   'presenterName' => optional($this->seminar->presentateur)->prenom . ' ' . optional($this->seminar->presentateur)->name,
                   'seminarDate' => \Carbon\Carbon::parse($this->seminar->date_presentation)->isoFormat('LL'),
                   'seminarTime' => $this->seminar->heure_presentation ? \Carbon\Carbon::parse($this->seminar->heure_presentation)->format('H:i') : '',
                   'seminarUrl' => route('dashboard.etudiant'), // Lien vers le tableau de bord étudiant
               ],
           );
       }

       public function attachments(): array
       {
           return [];
       }
   }