<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    // Liste des fournisseurs
    public function index(Request $request)
    {
        $query = Fournisseur::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('pays', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $fournisseurs = $query->orderBy('nom')->paginate(15);

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    // Formulaire d'ajout
    public function create()
    {
        return view('fournisseurs.create');
    }

    // Enregistrer un fournisseur
    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'pays'      => 'required|max:100',
            'adresse'   => 'nullable|max:255',
            'telephone' => 'nullable|max:30',
            'email'     => 'nullable|email|max:150',
        ], [
            'nom.required'  => 'Le nom du fournisseur est obligatoire.',
            'pays.required' => 'Le pays est obligatoire.',
            'email.email'   => 'Format d\'email invalide.',
        ]);

        Fournisseur::create($request->all());

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur ajouté avec succès.');
    }

    // Afficher un fournisseur
    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load('achats');
        return view('fournisseurs.show', compact('fournisseur'));
    }

    // Formulaire de modification
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    // Mettre à jour un fournisseur
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'pays'      => 'required|max:100',
            'adresse'   => 'nullable|max:255',
            'telephone' => 'nullable|max:30',
            'email'     => 'nullable|email|max:150',
        ], [
            'nom.required'  => 'Le nom du fournisseur est obligatoire.',
            'pays.required' => 'Le pays est obligatoire.',
        ]);

        $fournisseur->update($request->all());

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    // Supprimer un fournisseur
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur supprimé avec succès.');
    }
}