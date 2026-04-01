<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestionnaire extends Model
{
    protected $fillable = ['user_id', 'matricule'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function gererCommandes()
    {
        return Commande::with(['client.user', 'ligneCommandes.burger'])->latest()->get();
    }

    public function gererProduits()
    {
        return Burger::with('categorie')->get();
    }

    public function enregistrerPaiement(Commande $commande, float $montantEspeces): Paiement
    {
        return Paiement::create([
            'commande_id'     => $commande->id,
            'montant'         => $commande->total,
            'date_paiement'   => now(),
            'montant_especes' => $montantEspeces,
        ]);
    }

    public function voirStatistiques(): array
    {
        return [
            'commandes_en_cours'   => Commande::whereDate('date', today())
                                        ->whereNotIn('statut', ['payee', 'annulee'])->count(),
            'commandes_validees'   => Commande::whereDate('date', today())
                                        ->where('statut', 'payee')->count(),
            'recettes_journalieres'=> Paiement::whereDate('date_paiement', today())->sum('montant'),
        ];
    }
}
