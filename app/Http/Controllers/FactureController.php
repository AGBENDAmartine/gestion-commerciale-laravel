<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    // Liste des factures
    public function index(Request $request)
    {
        $query = Facture::with(['vente.client']);

        // Recherche par numéro
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('numero', 'like', "%$search%");
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date_facture', $request->date);
        }

        $factures = $query->orderByDesc('date_facture')->paginate(15);

        return view('factures.index', compact('factures'));
    }

    // Afficher une facture
    public function show(Facture $facture)
    {
        $facture->load([
            'vente.client',
            'vente.utilisateur',
            'vente.lignes.produit',
        ]);

        return view('factures.show', compact('facture'));
    }

    // Imprimer une facture
    public function imprimer(Facture $facture)
    {
        $facture->load([
            'vente.client',
            'vente.utilisateur',
            'vente.lignes.produit',
        ]);

        return view('factures.imprimer', compact('facture'));
    }

    // Changer le statut d'une facture
    public function changerStatut(Request $request, Facture $facture)
    {
        $request->validate([
            'statut' => 'required|in:emise,payee,annulee',
        ]);

        $facture->update(['statut' => $request->statut]);

        return redirect()->route('factures.show', $facture->id_facture)
                         ->with('success', 'Statut de la facture mis à jour.');
    }
}