<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'commande_id', 'montant', 'date_paiement', 'montant_especes',
    ];

    protected $casts = [
        'date_paiement'   => 'datetime',
        'montant'         => 'float',
        'montant_especes' => 'float',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public function enregistrer(): Facture
    {
        // Crée la facture associée après paiement
        $facture = $this->facture()->create([
            'numero' => 'FAC-' . now()->format('Ymd') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            'date'   => now(),
        ]);

        // Met à jour le statut de la commande
        $this->commande->changerStatut('payee');

        return $facture;
    }
}
