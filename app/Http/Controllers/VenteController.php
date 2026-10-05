<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    // Liste des ventes
    public function index(Request $request)
    {
        $query = Vente::with(['client', 'utilisateur']);

        if ($request->filled('date')) {
            $query->whereDate('date_vente', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $ventes = $query->orderByDesc('date_vente')->paginate(15);

        return view('ventes.index', compact('ventes'));
    }

    // Formulaire de vente
    public function create()
    {
        $clients  = Client::orderBy('nom')->get();
        $produits = Produit::where('quantite_stock', '>', 0)
                           ->orderBy('reference')
                           ->get();

        return view('ventes.create', compact('clients', 'produits'));
    }

    // Enregistrer une vente
    public function store(Request $request)
    {
        $request->validate([
            'id_client'               => 'required|exists:clients,id_client',
            'produits'                => 'required|array|min:1',
            'produits.*.id_produit'   => 'required|exists:produits,id_produit',
            'produits.*.quantite'     => 'required|integer|min:1',
            'produits.*.prix'         => 'required|numeric|min:0',
        ], [
            'id_client.required' => 'Veuillez sélectionner un client.',
            'produits.required'  => 'Veuillez ajouter au moins un produit.',
        ]);

        // ============================================================
        // VÉRIFICATION DU STOCK AVANT LA VENTE
        // ============================================================
        foreach ($request->produits as $item) {
            $produit = Produit::findOrFail($item['id_produit']);

            if ($item['quantite'] > $produit->quantite_stock) {
                return redirect()->back()
                    ->withInput()
                    ->with('error',
                        "⚠️ Stock insuffisant pour le produit
                        <strong>{$produit->reference} — {$produit->marque} {$produit->dimension}</strong>.
                        Stock disponible : <strong>{$produit->quantite_stock} unité(s)</strong>.
                        Quantité demandée : <strong>{$item['quantite']} unité(s)</strong>."
                    );
            }

            if ($produit->quantite_stock === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error',
                        "⚠️ Le produit
                        <strong>{$produit->reference} — {$produit->marque} {$produit->dimension}</strong>
                        est en rupture de stock !"
                    );
            }
        }

        // ============================================================
        // ENREGISTREMENT DE LA VENTE
        // ============================================================
        DB::transaction(function () use ($request) {
            $montant_total = 0;

            // Récupérer le client pour la remise
            $client = Client::findOrFail($request->id_client);
            $remise = $client->remise ?? 0;

            // Créer la vente
            $vente = Vente::create([
                'date_vente'     => today(),
                'montant_total'  => 0,
                'remise'         => $remise,
                'statut'         => 'validee',
                'id_client'      => $request->id_client,
                'id_utilisateur' => Auth::id(),
            ]);

            // Ajouter les lignes de vente
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['id_produit']);

                // Calcul sous-total avec remise
                $sous_total = $item['quantite'] * $item['prix'];
                $sous_total_remise = $sous_total - ($sous_total * $remise / 100);

                LigneVente::create([
                    'id_vente'      => $vente->id_vente,
                    'id_produit'    => $item['id_produit'],
                    'quantite'      => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                    'remise'        => $remise,
                    'sous_total'    => $sous_total_remise,
                ]);

                // Décrémenter le stock
                $produit->decrement('quantite_stock', $item['quantite']);

                $montant_total += $sous_total_remise;
            }

            // Mettre à jour le montant total
            $vente->update(['montant_total' => $montant_total]);

            // Générer automatiquement la facture
            Facture::create([
                'numero'        => Facture::genererNumero(),
                'date_facture'  => today(),
                'montant_total' => $montant_total,
                'statut'        => 'emise',
                'id_vente'      => $vente->id_vente,
            ]);
        });

        return redirect()->route('ventes.index')
                         ->with('success', '✅ Vente enregistrée et facture générée avec succès.');
    }

    // Détail d'une vente
    public function show(Vente $vente)
    {
        $vente->load([
            'client',
            'utilisateur',
            'lignes.produit',
            'facture',
        ]);

        return view('ventes.show', compact('vente'));
    }
}