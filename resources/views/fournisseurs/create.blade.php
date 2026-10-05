@extends('layouts.app')

@section('titre', 'Ajouter un fournisseur')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-plus-circle text-warning"></i> Ajouter un fournisseur</h5>
    <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('fournisseurs.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Nom -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-building"></i> Nom du fournisseur *
                    </label>
                    <input type="text" name="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom') }}"
                        placeholder="Ex: Continental GmbH"
                        required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pays -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-globe"></i> Pays *
                    </label>
                    <select name="pays"
                        class="form-select @error('pays') is-invalid @enderror"
                        required>
                        <option value="">-- Choisir le pays --</option>
                        <option value="Allemagne" {{ old('pays') == 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                        <option value="Italie" {{ old('pays') == 'Italie' ? 'selected' : '' }}>Italie</option>
                        <option value="Belgique" {{ old('pays') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                        <option value="France" {{ old('pays') == 'France' ? 'selected' : '' }}>France</option>
                        <option value="Chine" {{ old('pays') == 'Chine' ? 'selected' : '' }}>Chine</option>
                        <option value="Japon" {{ old('pays') == 'Japon' ? 'selected' : '' }}>Japon</option>
                        <option value="Togo" {{ old('pays') == 'Togo' ? 'selected' : '' }}>Togo (Port)</option>
                        <option value="Autre" {{ old('pays') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('pays')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-map-marker-alt"></i> Adresse
                    </label>
                    <input type="text" name="adresse"
                        class="form-control @error('adresse') is-invalid @enderror"
                        value="{{ old('adresse') }}"
                        placeholder="Ex: 123 Rue de la Paix, Berlin">
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-phone"></i> Téléphone
                    </label>
                    <input type="text" name="telephone"
                        class="form-control @error('telephone') is-invalid @enderror"
                        value="{{ old('telephone') }}"
                        placeholder="Ex: +49 30 1234567">
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Ex: contact@fournisseur.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection