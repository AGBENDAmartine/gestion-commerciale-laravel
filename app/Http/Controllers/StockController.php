<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Entrepot;
use App\Models\StockEntrepot;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    // Liste des stocks
    public function index(Request $request)
    {
        $query = Produit::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%$search%")
                  ->orWhere('marque', 'like', "%$search%")
                  ->orWhere('dimension', 'like', "%$search%");
            });
        }

        // Filtre produits en alerte
        if ($request->filled('alerte')) {
            $query->whereColumn('quantite_stock', '<=', 'seuil_alerte');
        }

        $produits  = $query->orderBy('reference')->paginate(15);
        $entrepots = Entrepot::all();

        return view('stock.index', compact('produits', 'entrepots'));
    }

    // Historique des mouvements
    public function mouvements(Request $request)
    {
        $query = MouvementStock::with(['produit', 'entrepot', 'utilisateur']);

        // Filtre par type
        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date_mouvement', $request->date);
        }

        $mouvements = $query->orderByDesc('date_mouvement')->paginate(15);

        return view('stock.mouvements', compact('mouvements'));
    }

    // Enregistrer une entrée de stock
    public function entree(Request $request)
    {
        $request->validate([
            'id_produit'  => 'required|exists:produits,id_produit',
            'id_entrepot' => 'required|exists:entrepots,id_entrepot',
            'quantite'    => 'required|integer|min:1',
            'motif'       => 'nullable|max:255',
        ], [
            'id_produit.required'  => 'Veuillez sélectionner un produit.',
            'id_entrepot.required' => 'Veuillez sélectionner un entrepôt.',
            'quantite.required'    => 'La quantité est obligatoire.',
            'quantite.min'         => 'La quantité doit être supérieure à 0.',
        ]);

        DB::transaction(function () use ($request) {
            // Enregistrer le mouvement
            MouvementStock::create([
                'type_mouvement'  => 'entree',
                'quantite'        => $request->quantite,
                'date_mouvement'  => now(),
                'motif'           => $request->motif,
                'id_produit'      => $request->id_produit,
                'id_entrepot'     => $request->id_entrepot,
                'id_utilisateur'  => Auth::id(),
            ]);

            // Mettre à jour le stock global
            $produit = Produit::findOrFail($request->id_produit);
            $produit->increment('quantite_stock', $request->quantite);

            // Mettre à jour le stock par entrepôt
            StockEntrepot::updateOrCreate(
                [
                    'id_produit'  => $request->id_produit,
                    'id_entrepot' => $request->id_entrepot,
                ],
                [
                    'quantite' => DB::raw('quantite + ' . $request->quantite),
                ]
            );
        });

        return redirect()->route('stock.index')
                         ->with('success', 'Entrée de stock enregistrée avec succès.');
    }

    // Enregistrer une sortie de stock
    public function sortie(Request $request)
    {
        $request->validate([
            'id_produit'  => 'required|exists:produits,id_produit',
            'id_entrepot' => 'required|exists:entrepots,id_entrepot',
            'quantite'    => 'required|integer|min:1',
            'motif'       => 'nullable|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Enregistrer le mouvement
            MouvementStock::create([
                'type_mouvement' => 'sortie',
                'quantite'       => $request->quantite,
                'date_mouvement' => now(),
                'motif'          => $request->motif,
                'id_produit'     => $request->id_produit,
                'id_entrepot'    => $request->id_entrepot,
                'id_utilisateur' => Auth::id(),
            ]);

            // Mettre à jour le stock global
            $produit = Produit::findOrFail($request->id_produit);
            $produit->decrement('quantite_stock', $request->quantite);

            // Mettre à jour le stock par entrepôt
            StockEntrepot::where([
                'id_produit'  => $request->id_produit,
                'id_entrepot' => $request->id_entrepot,
            ])->decrement('quantite', $request->quantite);
        });

        return redirect()->route('stock.index')
                         ->with('success', 'Sortie de stock enregistrée avec succès.');
    }

    // Transfert entre entrepôts
    public function transfert(Request $request)
    {
        $request->validate([
            'id_produit'       => 'required|exists:produits,id_produit',
            'id_entrepot_from' => 'required|exists:entrepots,id_entrepot',
            'id_entrepot_to'   => 'required|exists:entrepots,id_entrepot|different:id_entrepot_from',
            'quantite'         => 'required|integer|min:1',
            'motif'            => 'nullable|max:255',
        ], [
            'id_entrepot_to.different' => 'L\'entrepôt de destination doit être différent.',
        ]);

        DB::transaction(function () use ($request) {
            // Enregistrer le mouvement
            MouvementStock::create([
                'type_mouvement' => 'transfert',
                'quantite'       => $request->quantite,
                'date_mouvement' => now(),
                'motif'          => $request->motif ?? 'Transfert entre entrepôts',
                'id_produit'     => $request->id_produit,
                'id_entrepot'    => $request->id_entrepot_from,
                'id_utilisateur' => Auth::id(),
            ]);

            // Décrémenter l'entrepôt source
            StockEntrepot::where([
                'id_produit'  => $request->id_produit,
                'id_entrepot' => $request->id_entrepot_from,
            ])->decrement('quantite', $request->quantite);

            // Incrémenter l'entrepôt destination
            StockEntrepot::updateOrCreate(
                [
                    'id_produit'  => $request->id_produit,
                    'id_entrepot' => $request->id_entrepot_to,
                ],
                [
                    'quantite' => DB::raw('quantite + ' . $request->quantite),
                ]
            );
        });

        return redirect()->route('stock.index')
                         ->with('success', 'Transfert effectué avec succès.');
    }
}