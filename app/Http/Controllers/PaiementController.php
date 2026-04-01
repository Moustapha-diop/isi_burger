<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    public function create(Commande $commande)
    {
        if ($commande->paiement) {
            return back()->withErrors(['paiement' => 'Cette commande a déjà été payée.']);
        }

        return view('gestionnaire.paiements.create', compact('commande'));
    }

    public function store(Request $request, Commande $commande)
    {
        if ($commande->paiement) {
            return back()->withErrors(['paiement' => 'Cette commande a déjà été payée.']);
        }

        $request->validate([
            'montant_especes' => 'required|numeric|min:' . $commande->total,
        ]);

        /** @var \App\Models\User $user */
        $user         = Auth::user();
        $gestionnaire = $user->gestionnaire;

        if (!$gestionnaire) {
            return back()->withErrors(['gestionnaire' => 'Profil gestionnaire introuvable.']);
        }

        $paiement = $gestionnaire->enregistrerPaiement($commande, $request->montant_especes);
        $facture  = $paiement->enregistrer(); // génère facture + change statut, retourne la Facture

        // Génération PDF et envoi email (non bloquant)
        try {
            $facture->genererPDF();
            $facture->envoyerEmail();
        } catch (\Exception $e) {
            Log::warning("Facture PDF/email non généré pour commande #{$commande->id} : " . $e->getMessage());
        }

        // Notification
        Notification::creer(
            'FACTURE',
            "Facture {$facture->numero} envoyée pour la commande #{$commande->id}",
            $commande->id
        );

        return redirect()->route('gestionnaire.commandes.show', $commande)
            ->with('success', "Paiement enregistré. Facture {$facture->numero} générée.");
    }
}
