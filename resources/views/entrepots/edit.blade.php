@extends('layouts.app')

@section('titre', 'Modifier un entrepôt')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-edit text-warning"></i> Modifier un entrepôt</h5>
    <a href="{{ route('entrepots.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('entrepots.update', $entrepot->id_entrepot) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Nom -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-building"></i> Nom de l'entrepôt *
                    </label>
                    <input type="text" name="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom', $entrepot->nom) }}"
                        required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tag"></i> Type *
                    </label>
                    <select name="type"
                        class="form-select @error('type') is-invalid @enderror"
                        required>
                        <option value="principal" {{ old('type', $entrepot->type) == 'principal' ? 'selected' : '' }}>
                            Principal (Stockage)
                        </option>
                        <option value="exposition" {{ old('type', $entrepot->type) == 'exposition' ? 'selected' : '' }}>
                            Exposition (Présentation clients)
                        </option>
                    </select>
                    @error('type')
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
                        value="{{ old('adresse', $entrepot->adresse) }}"
                        placeholder="Ex: Zone industrielle, Lomé">
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('entrepots.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection