<?php

namespace App\Mail;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FactureMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Facture $facture) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre facture {$this->facture->numero} – ISI BURGER",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.facture',
        );
    }

    public function attachments(): array
    {
        if ($this->facture->fichier_pdf) {
            $path = storage_path('app/public/' . $this->facture->fichier_pdf);
            if (file_exists($path)) {
                return [
                    Attachment::fromPath($path)
                        ->as($this->facture->numero . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        }
        return [];
    }
}
