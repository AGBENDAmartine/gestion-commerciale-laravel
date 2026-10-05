@extends('layouts.app')

@section('titre', 'Modifier un utilisateur')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-user-edit text-warning"></i> Modifier un utilisateur
    </h5>
    <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('utilisateurs.update', $utilisateur->id_utilisateur) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nom complet *</label>
                    <input type="text" name="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom', $utilisateur->nom) }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email *</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $utilisateur->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Nouveau mot de passe
                        <small class="text-muted">(laisser vide pour ne pas changer)</small>
                    </label>
                    <input type="password" name="mot_de_passe"
                        class="form-control @error('mot_de_passe') is-invalid @enderror">
                    @error('mot_de_passe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmer le mot de passe</label>
                    <input type="password" name="mot_de_passe_confirmation"
                        class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Rôle *</label>
                    <select name="role"
                        class="form-select @error('role') is-invalid @enderror" required>
                        <option value="administrateur" {{ old('role', $utilisateur->role) == 'administrateur' ? 'selected' : '' }}>
                            Administrateur
                        </option>
                        <option value="gestionnaire" {{ old('role', $utilisateur->role) == 'gestionnaire' ? 'selected' : '' }}>
                            Gestionnaire
                        </option>
                        <option value="vendeur" {{ old('role', $utilisateur->role) == 'vendeur' ? 'selected' : '' }}>
                            Vendeur
                        </option>
                        <option value="magasinier" {{ old('role', $utilisateur->role) == 'magasinier' ? 'selected' : '' }}>
                            Magasinier
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Informations supplémentaires -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Compte créé le</label>
                    <input type="text" class="form-control bg-light"
                        value="{{ $utilisateur->created_at->format('d/m/Y à H:i') }}" disabled>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection