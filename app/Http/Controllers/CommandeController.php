<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Burger;
use App\Models\Notification;
use App\Mail\CommandeConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommandeController extends Controller
{
    // ── CLIENT ─────────────────────────────────────────────

    public function create()
    {
        $burgers = Burger::disponible()->with('categorie')->get();
        return view('commandes.create', compact('burgers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lignes'                   => 'required|array|min:1',
            'lignes.*.burger_id'       => 'required|exists:burgers,id',
            'lignes.*.quantite'        => 'required|integer|min:1',
        ]);

        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $client = $user->client;

        if (!$client) {
            return back()->withErrors(['client' => 'Profil client introuvable.']);
        }

        // Vérification des stocks
        foreach ($request->lignes as $ligne) {
            $burger = Burger::findOrFail($ligne['burger_id']);
            if (!$burger->isDisponible()) {
                return back()->withErrors(['stock' => "Le burger « {$burger->nom} » n'est plus disponible."]);
            }
        }

        $commande = $client->passerCommande($request->lignes);

        // Notification gestionnaire
        Notification::creer(
            'NOUVELLE_COMMANDE',
            "Nouvelle commande #{$commande->id} de {$client->user->name}",
            $commande->id
        );

        // Email confirmation client (non bloquant — ne fait pas échouer la commande)
        try {
            Mail::to($user->email)->send(new CommandeConfirmationMail($commande));
        } catch (\Exception $e) {
            Log::warning("Email confirmation non envoyé pour commande #{$commande->id} : " . $e->getMessage());
        }

        return redirect()->route('client.commandes.index')
            ->with('success', "Commande #{$commande->id} passée avec succès !");
    }

    public function indexClient()
    {
        $client = Auth::user()->client;

        if (!$client) {
            return redirect()->route('login');
        }

        $commandes = $client
            ->commandes()
            ->with('ligneCommandes.burger', 'paiement')
            ->latest()
            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    public function showClient(Commande $commande)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Vérifier manuellement que la commande appartient au client connecté
    if (!$user->client || $user->client->id !== $commande->client_id) {
        abort(403, 'Accès non autorisé.');
    }

    $commande->load('ligneCommandes.burger', 'paiement.facture');
    return view('commandes.show', compact('commande'));
}

    // ── GESTIONNAIRE ────────────────────────────────────────

    public function indexGestionnaire(Request $request)
    {
        $query = Commande::with('client.user', 'ligneCommandes.burger', 'paiement')
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $commandes = $query->paginate(10);
        return view('gestionnaire.commandes.index', compact('commandes'));
    }

    public function showGestionnaire(Commande $commande)
    {
        $commande->load('client.user', 'ligneCommandes.burger', 'paiement.facture', 'gestionnaire.user');
        return view('gestionnaire.commandes.show', compact('commande'));
    }

    public function changerStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_preparation,prete,payee,annulee',
        ]);

        $commande->changerStatut($request->statut);

        // Si commande prête → envoyer email avec facture PDF
        if ($request->statut === 'prete') {
            // La facture est générée lors du paiement,
            // ici on envoie une notification de commande prête
            Notification::creer(
                'CONFIRMATION',
                "Votre commande #{$commande->id} est prête !",
                $commande->id
            );
        }

        return back()->with('success', "Statut mis à jour : {$request->statut}");
    }

    public function annuler(Commande $commande)
    {
        try {
            $commande->annuler();
            return back()->with('success', 'Commande annulée.');
        } catch (\Exception $e) {
            return back()->withErrors(['annulation' => $e->getMessage()]);
        }
    }
}
