<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    // Liste des utilisateurs
    public function index()
    {
        $utilisateurs = Utilisateur::orderBy('nom')->get();
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    // Formulaire création
    public function create()
    {
        return view('utilisateurs.create');
    }

    // Enregistrer un utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string|max:255',
            'email'      => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|min:6|confirmed',
            'role'       => 'required|in:administrateur,gestionnaire,vendeur,magasinier',
        ]);

        Utilisateur::create([
            'nom'          => $request->nom,
            'email'        => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role'         => $request->role,
        ]);

        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur créé avec succès !');
    }

    // Formulaire modification
    public function edit($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        return view('utilisateurs.edit', compact('utilisateur'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        $request->validate([
            'nom'   => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id . ',id_utilisateur',
            'role'  => 'required|in:administrateur,gestionnaire,vendeur,magasinier',
        ]);

        $utilisateur->nom   = $request->nom;
        $utilisateur->email = $request->email;
        $utilisateur->role  = $request->role;

        if ($request->filled('mot_de_passe')) {
            $request->validate([
                'mot_de_passe' => 'min:6|confirmed',
            ]);
            $utilisateur->mot_de_passe = Hash::make($request->mot_de_passe);
        }

        $utilisateur->save();

        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur modifié avec succès !');
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        // Empêcher la suppression de son propre compte
        if ($utilisateur->id_utilisateur === auth()->user()->id_utilisateur) {
            return redirect()->route('utilisateurs.index')
                             ->with('error', 'Vous ne pouvez pas supprimer votre propre compte !');
        }

        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')
                         ->with('success', 'Utilisateur supprimé avec succès !');
    }
}