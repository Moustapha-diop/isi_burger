<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\FactureMail;

class Facture extends Model
{
    protected $fillable = ['paiement_id', 'numero', 'date', 'fichier_pdf'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

    public function genererPDF(): string
    {
        // Nécessite le package barryvdh/laravel-dompdf
        // Installer avec : composer require barryvdh/laravel-dompdf
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            Log::warning("DomPDF non installé. Exécutez : composer require barryvdh/laravel-dompdf");
            return '';
        }

        $commande = $this->paiement->commande->load('ligneCommandes.burger', 'client.user');

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.facture', [
                'facture'  => $this,
                'commande' => $commande,
            ]);

            $dir = storage_path('app/public/factures');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $filename = 'factures/' . $this->numero . '.pdf';
            $pdf->save(storage_path('app/public/' . $filename));

            $this->update(['fichier_pdf' => $filename]);
            return $filename;
    }

    public function envoyerEmail(): void
    {
        $client = $this->paiement->commande->client->user;

        if (!$this->fichier_pdf) {
            $this->genererPDF();
        }

        Mail::to($client->email)->send(new FactureMail($this));
    }
}
