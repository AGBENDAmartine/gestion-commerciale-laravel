<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MotDePasseController extends Controller
{
    // Afficher le formulaire
    public function index()
    {
        return view('mot_de_passe.index');
    }

    // Modifier le mot de passe
    public function update(Request $request)
    {
        $request->validate([
            'ancien_mot_de_passe'  => 'required',
            'nouveau_mot_de_passe' => 'required|min:6|confirmed',
        ], [
            'ancien_mot_de_passe.required'  => 'L\'ancien mot de passe est obligatoire.',
            'nouveau_mot_de_passe.required' => 'Le nouveau mot de passe est obligatoire.',
            'nouveau_mot_de_passe.min'      => 'Le nouveau mot de passe doit contenir au moins 6 caractères.',
            'nouveau_mot_de_passe.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $utilisateur = auth()->user();

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->ancien_mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'ancien_mot_de_passe' => 'L\'ancien mot de passe est incorrect.'
            ]);
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if ($request->ancien_mot_de_passe === $request->nouveau_mot_de_passe) {
            return back()->withErrors([
                'nouveau_mot_de_passe' => 'Le nouveau mot de passe doit être différent de l\'ancien.'
            ]);
        }

        // Mettre à jour le mot de passe
        $utilisateur->mot_de_passe = Hash::make($request->nouveau_mot_de_passe);
        $utilisateur->save();

        return redirect()->route('mot_de_passe.index')
            ->with('success', 'Votre mot de passe a été modifié avec succès !');
    }
}