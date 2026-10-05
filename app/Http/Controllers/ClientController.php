<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Liste des clients
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%')
                  ->orWhere('telephone', 'like', '%'.$request->search.'%')
                  ->orWhere('adresse', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $clients = $query->orderBy('nom')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    // Formulaire d'ajout
    public function create()
    {
        return view('clients.create');
    }

    // Enregistrer un client
    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'telephone' => 'nullable|max:30',
            'adresse'   => 'nullable|max:255',
            'remarques' => 'nullable',
        ], [
            'nom.required' => 'Le nom du client est obligatoire.',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Client enregistré avec succès.');
    }

    // Afficher un client et son historique
    public function show(Client $client)
    {
        $client->load([
            'ventes.facture',
            'ventes.lignes.produit',
        ]);

        return view('clients.show', compact('client'));
    }

    // Formulaire de modification
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Mettre à jour un client
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom'       => 'required|max:150',
            'telephone' => 'nullable|max:30',
            'adresse'   => 'nullable|max:255',
            'remarques' => 'nullable',
            'categorie' => 'nullable|in:normal,fidele,vip',
            'remise'    => 'nullable|numeric|min:0|max:100',
        ], [
            'nom.required' => 'Le nom du client est obligatoire.',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Client mis à jour avec succès.');
    }

    // Supprimer un client
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
                         ->with('success', 'Client supprimé avec succès.');
    }

    // Changer la catégorie (ancienne méthode)
    public function changerCategorie(Request $request, $id)
    {
        return $this->updateCategorie($request, $id);
    }

    // Mettre à jour la catégorie d'un client
    public function updateCategorie(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $remises = [
            'normal' => 0,
            'fidele' => 5,
            'vip'    => 10,
        ];

        $client->categorie = $request->categorie;
        $client->remise    = $remises[$request->categorie] ?? 0;
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Catégorie du client mise à jour avec succès !');
    }

    // Mettre à jour toutes les catégories
    public function mettreAJourCategories()
    {
        $clients = Client::all();
        foreach ($clients as $client) {
            $client->mettreAJourCategorie();
        }

        return redirect()->route('clients.index')
                         ->with('success', 'Catégories mises à jour avec succès.');
    }
}