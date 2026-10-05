<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_ventes_jour'  => Vente::whereDate('date_vente', today())
                                         ->sum('montant_total'),
            'total_ventes_mois'  => Vente::whereMonth('date_vente', now()->month)
                                         ->sum('montant_total'),
            'nb_ventes_jour'     => Vente::whereDate('date_vente', today())
                                         ->count(),
            'nb_clients'         => Client::count(),
            'nb_produits'        => Produit::count(),
            'produits_alerte'    => Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')
                                           ->count(),
        ];

        // 5 dernières ventes
        $ventes_recentes = Vente::with(['client', 'utilisateur'])
            ->orderByDesc('date_vente')
            ->limit(5)
            ->get();

        // Top 5 produits les plus vendus
        $top_produits = DB::table('lignes_vente')
            ->join('produits', 'lignes_vente.id_produit', '=', 'produits.id_produit')
            ->select(
                'produits.reference',
                'produits.marque',
                'produits.dimension',
                DB::raw('SUM(lignes_vente.quantite) as total_vendu')
            )
            ->groupBy(
                'produits.id_produit',
                'produits.reference',
                'produits.marque',
                'produits.dimension'
            )
            ->orderByDesc('total_vendu')
            ->limit(5)
            ->get();

        // Produits en alerte de stock
        $produits_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')
                                  ->get();

        return view('dashboard.index', compact(
            'stats',
            'ventes_recentes',
            'top_produits',
            'produits_alerte'
        ));
    }
}