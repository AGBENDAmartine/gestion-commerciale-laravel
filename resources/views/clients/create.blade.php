@extends('layouts.app')

@section('titre', 'Ajouter un client')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-user-plus text-warning"></i> Ajouter un client</h5>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Nom -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-user"></i> Nom complet *
                    </label>
                    <input type="text" name="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom') }}"
                        placeholder="Ex: Jean Dupont"
                        required>
                    @error('nom')
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
                        placeholder="Ex: +228 90 00 00 00">
                    @error('telephone')
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
                        placeholder="Ex: Quartier Adidogomé, Lomé">
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remarques -->
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-comment"></i> Remarques
                    </label>
                    <textarea name="remarques"
                        class="form-control @error('remarques') is-invalid @enderror"
                        rows="3"
                        placeholder="Informations supplémentaires sur le client...">{{ old('remarques') }}</textarea>
                    @error('remarques')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection