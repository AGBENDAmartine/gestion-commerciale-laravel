<?php

namespace App\Http\Controllers;

use App\Models\Entrepot;
use Illuminate\Http\Request;

class EntrepotController extends Controller
{
    // Liste des entrepôts
    public function index()
    {
        $entrepots = Entrepot::withCount('produits')
                             ->orderBy('nom')
                             ->get();

        return view('entrepots.index', compact('entrepots'));
    }

    // Formulaire d'ajout
    public function create()
    {
        return view('entrepots.create');
    }

    // Enregistrer un entrepôt
    public function store(Request $request)
    {
        $request->validate([
            'nom'     => 'required|max:150',
            'type'    => 'required|in:principal,exposition',
            'adresse' => 'nullable|max:255',
        ], [
            'nom.required'  => 'Le nom de l\'entrepôt est obligatoire.',
            'type.required' => 'Le type est obligatoire.',
        ]);

        Entrepot::create($request->all());

        return redirect()->route('entrepots.index')
                         ->with('success', 'Entrepôt ajouté avec succès.');
    }

    // Afficher un entrepôt et ses produits
    public function show(Entrepot $entrepot)
    {
        $entrepot->load('produits');

        return view('entrepots.show', compact('entrepot'));
    }

    // Formulaire de modification
    public function edit(Entrepot $entrepot)
    {
        return view('entrepots.edit', compact('entrepot'));
    }

    // Mettre à jour un entrepôt
    public function update(Request $request, Entrepot $entrepot)
    {
        $request->validate([
            'nom'     => 'required|max:150',
            'type'    => 'required|in:principal,exposition',
            'adresse' => 'nullable|max:255',
        ]);

        $entrepot->update($request->all());

        return redirect()->route('entrepots.index')
                         ->with('success', 'Entrepôt mis à jour avec succès.');
    }

    // Supprimer un entrepôt
    public function destroy(Entrepot $entrepot)
    {
        $entrepot->delete();

        return redirect()->route('entrepots.index')
                         ->with('success', 'Entrepôt supprimé avec succès.');
    }
}