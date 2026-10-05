@extends('layouts.app')

@section('titre', 'Modifier un produit')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-edit text-warning"></i> Modifier un produit</h5>
    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('produits.update', $produit->id_produit) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Référence (non modifiable) -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-barcode"></i> Référence
                    </label>
                    <input type="text"
                        class="form-control bg-light"
                        value="{{ $produit->reference }}"
                        disabled>
                    <small class="text-muted">La référence ne peut pas être modifiée</small>
                </div>

                <!-- Marque -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tag"></i> Marque *
                    </label>
                    <input type="text" name="marque"
                        class="form-control @error('marque') is-invalid @enderror"
                        value="{{ old('marque', $produit->marque) }}"
                        required>
                    @error('marque')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-circle"></i> Type *
                    </label>
                    <select name="type"
                        class="form-select @error('type') is-invalid @enderror"
                        required>
                        <option value="pneu" {{ old('type', $produit->type) == 'pneu' ? 'selected' : '' }}>Pneu</option>
                        <option value="jante" {{ old('type', $produit->type) == 'jante' ? 'selected' : '' }}>Jante</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dimension -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-ruler"></i> Dimension *
                    </label>
                    <input type="text" name="dimension"
                        class="form-control @error('dimension') is-invalid @enderror"
                        value="{{ old('dimension', $produit->dimension) }}"
                        required>
                    @error('dimension')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prix achat -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-money-bill"></i> Prix d'achat (F) *
                    </label>
                    <input type="number" name="prix_achat"
                        class="form-control @error('prix_achat') is-invalid @enderror"
                        value="{{ old('prix_achat', $produit->prix_achat) }}"
                        min="0" step="1"
                        required>
                    @error('prix_achat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prix vente -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-money-bill-wave"></i> Prix de vente (F) *
                    </label>
                    <input type="number" name="prix_vente"
                        class="form-control @error('prix_vente') is-invalid @enderror"
                        value="{{ old('prix_vente', $produit->prix_vente) }}"
                        min="0" step="1"
                        required>
                    @error('prix_vente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seuil alerte -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-exclamation-triangle"></i> Seuil d'alerte *
                    </label>
                    <input type="number" name="seuil_alerte"
                        class="form-control @error('seuil_alerte') is-invalid @enderror"
                        value="{{ old('seuil_alerte', $produit->seuil_alerte) }}"
                        min="0"
                        required>
                    @error('seuil_alerte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock actuel (info) -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-cubes"></i> Stock actuel
                    </label>
                    <input type="text"
                        class="form-control bg-light"
                        value="{{ $produit->quantite_stock }} unités"
                        disabled>
                    <small class="text-muted">
                        Le stock se met à jour via les entrées/sorties
                    </small>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection