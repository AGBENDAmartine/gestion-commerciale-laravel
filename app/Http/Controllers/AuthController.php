<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher la page de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|min:6',
        ], [
            'email.required'        => 'L\'email est obligatoire.',
            'email.email'           => 'Format d\'email invalide.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min'      => 'Le mot de passe doit avoir au moins 6 caractères.',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ])->withInput();
        }

        // Mettre à jour la dernière connexion
        $utilisateur->update(['derniere_connexion' => now()]);

        // Connecter l'utilisateur
        Auth::login($utilisateur);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}