<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Burger;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Stats du jour
        $stats = [
            'commandes_en_cours'    => Commande::whereDate('date', today())
                                        ->whereNotIn('statut', ['payee', 'annulee'])->count(),
            'commandes_validees'    => Commande::whereDate('date', today())
                                        ->where('statut', 'payee')->count(),
            'recettes_journalieres' => Paiement::whereDate('date_paiement', today())->sum('montant'),
        ];

        // Commandes par mois (12 derniers mois)
        // Compatible MySQL ET PostgreSQL
        $driver = DB::getDriverName();
        $yearExpr   = $driver === 'pgsql' ? 'EXTRACT(YEAR  FROM date)::int'  : 'YEAR(date)';
        $monthExpr  = $driver === 'pgsql' ? 'EXTRACT(MONTH FROM date)::int'  : 'MONTH(date)';
        $yearExprC  = $driver === 'pgsql' ? 'EXTRACT(YEAR  FROM commandes.date)::int' : 'YEAR(commandes.date)';
        $monthExprC = $driver === 'pgsql' ? 'EXTRACT(MONTH FROM commandes.date)::int' : 'MONTH(commandes.date)';

        $commandesParMois = Commande::select(
            DB::raw("$yearExpr  as annee"),
            DB::raw("$monthExpr as mois"),
            DB::raw('COUNT(*) as total')
        )
        ->where('date', '>=', now()->subMonths(12))
        ->groupBy(DB::raw($yearExpr), DB::raw($monthExpr))
        ->orderBy(DB::raw($yearExpr))
        ->orderBy(DB::raw($monthExpr))
        ->get()
        ->map(fn($row) => [
            'label' => sprintf('%04d-%02d', $row->annee, $row->mois),
            'total' => $row->total,
        ]);

        // Produits par catégorie par mois
        $produitsParCategorie = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.burger_id', '=', 'burgers.id')
            ->join('categories', 'burgers.categorie_id', '=', 'categories.id')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->select(
                'categories.libelle as categorie',
                DB::raw("$yearExprC  as annee"),
                DB::raw("$monthExprC as mois"),
                DB::raw('SUM(ligne_commandes.quantite) as quantite')
            )
            ->where('commandes.date', '>=', now()->subMonths(12))
            ->groupBy('categories.libelle', DB::raw($yearExprC), DB::raw($monthExprC))
            ->orderBy(DB::raw($yearExprC))
            ->orderBy(DB::raw($monthExprC))
            ->get();

        return view('gestionnaire.statistiques', compact(
            'stats', 'commandesParMois', 'produitsParCategorie'
        ));
    }
}
