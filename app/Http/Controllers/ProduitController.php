<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // Liste des produits
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

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $produits = $query->orderBy('reference')->paginate(15);

        return view('produits.index', compact('produits'));
    }

    // Formulaire d'ajout
    public function create()
    {
        return view('produits.create');
    }

    // Enregistrer un produit
    public function store(Request $request)
    {
        $request->validate([
            'reference'      => 'required|unique:produits|max:100',
            'marque'         => 'required|max:100',
            'type'           => 'required|in:pneu,jante',
            'dimension'      => 'required|max:50',
            'prix_achat'     => 'required|numeric|min:0',
            'prix_vente'     => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte'   => 'required|integer|min:0',
        ], [
            'reference.required'      => 'La référence est obligatoire.',
            'reference.unique'        => 'Cette référence existe déjà.',
            'marque.required'         => 'La marque est obligatoire.',
            'type.required'           => 'Le type est obligatoire.',
            'dimension.required'      => 'La dimension est obligatoire.',
            'prix_achat.required'     => 'Le prix d\'achat est obligatoire.',
            'prix_vente.required'     => 'Le prix de vente est obligatoire.',
            'quantite_stock.required' => 'La quantité est obligatoire.',
            'seuil_alerte.required'   => 'Le seuil d\'alerte est obligatoire.',
        ]);

        Produit::create($request->all());

        return redirect()->route('produits.index')
                         ->with('success', 'Produit ajouté avec succès.');
    }

    // Formulaire de modification
    public function edit(Produit $produit)
    {
        return view('produits.edit', compact('produit'));
    }

    // Mettre à jour un produit
    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'marque'       => 'required|max:100',
            'type'         => 'required|in:pneu,jante',
            'dimension'    => 'required|max:50',
            'prix_achat'   => 'required|numeric|min:0',
            'prix_vente'   => 'required|numeric|min:0',
            'seuil_alerte' => 'required|integer|min:0',
        ]);

        $produit->update($request->all());

        return redirect()->route('produits.index')
                         ->with('success', 'Produit mis à jour avec succès.');
    }

    // Supprimer un produit
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')
                         ->with('success', 'Produit supprimé avec succès.');
    }

    // Recherche AJAX
    public function recherche(Request $request)
    {
        $produits = Produit::where('reference', 'like', '%' . $request->q . '%')
            ->orWhere('dimension', 'like', '%' . $request->q . '%')
            ->orWhere('marque', 'like', '%' . $request->q . '%')
            ->limit(10)
            ->get(['id_produit', 'reference', 'marque', 'dimension', 'prix_vente', 'quantite_stock']);

        return response()->json($produits);
    }
}