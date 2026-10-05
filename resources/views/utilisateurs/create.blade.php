@extends('layouts.app')

@section('titre', 'Nouvel utilisateur')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-user-plus text-warning"></i> Créer un utilisateur
    </h5>
    <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('utilisateurs.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nom complet *</label>
                    <input type="text" name="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom') }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email *</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mot de passe *</label>
                    <input type="password" name="mot_de_passe"
                        class="form-control @error('mot_de_passe') is-invalid @enderror"
                        required>
                    @error('mot_de_passe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmer le mot de passe *</label>
                    <input type="password" name="mot_de_passe_confirmation"
                        class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Rôle *</label>
                    <select name="role"
                        class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Choisir un rôle --</option>
                        <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>
                            Administrateur
                        </option>
                        <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>
                            Gestionnaire
                        </option>
                        <option value="vendeur" {{ old('role') == 'vendeur' ? 'selected' : '' }}>
                            Vendeur
                        </option>
                        <option value="magasinier" {{ old('role') == 'magasinier' ? 'selected' : '' }}>
                            Magasinier
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection