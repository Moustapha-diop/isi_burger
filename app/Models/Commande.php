<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'client_id', 'gestionnaire_id', 'date', 'statut', 'total',
    ];

    protected $casts = [
        'date'  => 'datetime',
        'total' => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(Gestionnaire::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function calculerTotal(): float
    {
        $total = $this->ligneCommandes->sum(fn($l) => $l->getSousTotal());
        $this->update(['total' => $total]);
        return $total;
    }

    public function changerStatut(string $statut): void
    {
        $statutsValides = ['en_attente', 'en_preparation', 'prete', 'payee', 'annulee'];
        if (!in_array($statut, $statutsValides)) {
            throw new \InvalidArgumentException("Statut invalide : $statut");
        }
        $this->update(['statut' => $statut]);
    }

    public function annuler(): void
    {
        if (in_array($this->statut, ['payee'])) {
            throw new \Exception("Impossible d'annuler une commande déjà payée.");
        }

        // Restituer le stock pour chaque ligne
        $this->loadMissing('ligneCommandes.burger');
        foreach ($this->ligneCommandes as $ligne) {
            $ligne->burger->increment('stock', $ligne->quantite);
        }

        $this->changerStatut('annulee');
    }
}
