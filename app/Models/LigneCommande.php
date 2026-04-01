<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $fillable = ['commande_id', 'burger_id', 'quantite', 'prix_unitaire'];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function burger()
    {
        return $this->belongsTo(Burger::class);
    }

    public function getSousTotal(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}
