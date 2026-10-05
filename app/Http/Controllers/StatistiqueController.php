<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StatistiquesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class StatistiqueController extends Controller
{
    public function index()
    {
        $ca_par_mois     = $this->getCaParMois();
        $top_produits    = $this->getTopProduits();
        $produits_alerte = $this->getProduitsAlerte();
        $bilan           = $this->getBilan();
        $ventes_semaine  = $this->getVentesSemaine();
        $nouveaux_clients = Client::whereMonth('date_inscription', now()->month)
                                  ->whereYear('date_inscription', now()->year)
                                  ->count();

        return view('statistiques.index', compact(
            'ca_par_mois', 'top_produits', 'produits_alerte',
            'bilan', 'ventes_semaine', 'nouveaux_clients'
        ));
    }

    // ============================================================
    // MÉTHODES RÉUTILISABLES
    // ============================================================

    public function getBilan()
    {
        $total_ventes = Vente::whereYear('date_vente', now()->year)->sum('montant_total');
        $total_achats = Achat::whereYear('date_achat', now()->year)->sum('montant_total');
        return [
            'total_achats' => $total_achats,
            'total_ventes' => $total_ventes,
            'benefice'     => $total_ventes - $total_achats,
        ];
    }

    public function getCaParMois()
    {
        return Vente::select(
            DB::raw('MONTH(date_vente) as mois'),
            DB::raw('YEAR(date_vente) as annee'),
            DB::raw('SUM(montant_total) as total')
        )
        ->whereYear('date_vente', now()->year)
        ->groupBy('annee', 'mois')
        ->orderBy('mois')
        ->get();
    }

    public function getTopProduits()
    {
        return DB::table('lignes_vente')
            ->join('produits', 'lignes_vente.id_produit', '=', 'produits.id_produit')
            ->select(
                'produits.reference',
                'produits.marque',
                'produits.dimension',
                DB::raw('SUM(lignes_vente.quantite) as total_vendu'),
                DB::raw('SUM(lignes_vente.quantite * lignes_vente.prix_unitaire) as recettes')
            )
            ->groupBy(
                'produits.id_produit',
                'produits.reference',
                'produits.marque',
                'produits.dimension'
            )
            ->orderByDesc('total_vendu')
            ->limit(10)
            ->get();
    }

    public function getProduitsAlerte()
    {
        return Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();
    }

    public function getVentesSemaine()
    {
        return Vente::select(
            DB::raw('DATE(date_vente) as jour'),
            DB::raw('COUNT(*) as nb_ventes'),
            DB::raw('SUM(montant_total) as total')
        )
        ->whereBetween('date_vente', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('jour')
        ->orderBy('jour')
        ->get();
    }

    // ============================================================
    // EXPORT PDF
    // ============================================================

    public function exportPdf()
    {
        $bilan        = $this->getBilan();
        $ca_par_mois  = $this->getCaParMois();
        $top_produits = $this->getTopProduits();

        $pdf = Pdf::loadView('exports.statistiques_pdf',
            compact('bilan', 'ca_par_mois', 'top_produits'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('statistiques_' . date('Y-m-d') . '.pdf');
    }

    // ============================================================
    // EXPORT EXCEL
    // ============================================================

    public function exportExcel()
    {
        $bilan        = $this->getBilan();
        $ca_par_mois  = $this->getCaParMois();
        $top_produits = $this->getTopProduits();

        return Excel::download(
            new StatistiquesExport($bilan, $ca_par_mois, $top_produits),
            'statistiques_' . date('Y-m-d') . '.xlsx'
        );
    }
}