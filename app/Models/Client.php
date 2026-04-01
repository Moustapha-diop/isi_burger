<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['user_id', 'adresse', 'telephone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function passerCommande(array $lignes): Commande
    {
        $commande = $this->commandes()->create([
            'date'   => now(),
            'statut' => 'en_attente',
            'total'  => 0,
        ]);

        $total = 0;
        foreach ($lignes as $ligne) {
            $burger = Burger::findOrFail($ligne['burger_id']);
            $quantite = (int) $ligne['quantite'];
            $commande->ligneCommandes()->create([
                'burger_id'     => $burger->id,
                'quantite'      => $quantite,
                'prix_unitaire' => $burger->prix,
            ]);
            // Décrémenter le stock
            $burger->decrement('stock', $quantite);
            $total += $burger->prix * $quantite;
        }

        $commande->update(['total' => $total]);
        return $commande->fresh();
    }

    public function consulterCatalogue()
    {
        return Burger::where('archive', false)->whereHas('categorie')->get();
    }

    public function voirMesCommandes()
    {
        return $this->commandes()->with('ligneCommandes.burger')->latest()->get();
    }
}
