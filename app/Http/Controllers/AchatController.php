<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\LigneAchat;
use App\Models\Produit;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{
    // Liste des achats
    public function index(Request $request)
    {
        $query = Achat::with(['fournisseur', 'utilisateur']);

        // Recherche par date
        if ($request->filled('date')) {
            $query->whereDate('date_achat', $request->date);
        }

        // Filtre par type
        if ($request->filled('type_achat')) {
            $query->where('type_achat', $request->type_achat);
        }

        $achats = $query->orderByDesc('date_achat')->paginate(15);

        return view('achats.index', compact('achats'));
    }

    // Formulaire d'ajout
    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $produits     = Produit::orderBy('reference')->get();

        return view('achats.create', compact('fournisseurs', 'produits'));
    }

    // Enregistrer un achat
    public function store(Request $request)
    {
        $request->validate([
            'type_achat'              => 'required|in:commande_fournisseur,achat_port',
            'id_fournisseur'          => 'nullable|exists:fournisseurs,id_fournisseur',
            'produits'                => 'required|array|min:1',
            'produits.*.id_produit'   => 'required|exists:produits,id_produit',
            'produits.*.quantite'     => 'required|integer|min:1',
            'produits.*.prix'         => 'required|numeric|min:0',
        ], [
            'type_achat.required' => 'Le type d\'achat est obligatoire.',
            'produits.required'   => 'Veuillez ajouter au moins un produit.',
        ]);

        DB::transaction(function () use ($request) {
            $montant_total = 0;

            // Créer l'achat
            $achat = Achat::create([
                'date_achat'     => today(),
                'montant_total'  => 0,
                'type_achat'     => $request->type_achat,
                'id_fournisseur' => $request->id_fournisseur,
                'id_utilisateur' => Auth::id(),
            ]);

            // Ajouter les lignes d'achat
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['id_produit']);

                LigneAchat::create([
                    'id_achat'      => $achat->id_achat,
                    'id_produit'    => $item['id_produit'],
                    'quantite'      => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                ]);

                // Incrémenter le stock
                $produit->increment('quantite_stock', $item['quantite']);

                $montant_total += $item['quantite'] * $item['prix'];
            }

            // Mettre à jour le montant total
            $achat->update(['montant_total' => $montant_total]);
        });

        return redirect()->route('achats.index')
                         ->with('success', 'Achat enregistré avec succès.');
    }

    // Détail d'un achat
    public function show(Achat $achat)
    {
        $achat->load([
            'fournisseur',
            'utilisateur',
            'lignes.produit',
        ]);

        return view('achats.show', compact('achat'));
    }
}