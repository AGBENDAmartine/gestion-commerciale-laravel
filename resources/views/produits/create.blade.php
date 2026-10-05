@extends('layouts.app')

@section('titre', 'Ajouter un produit')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-plus-circle text-warning"></i> Ajouter un produit</h5>
    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('produits.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Référence -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-barcode"></i> Référence *
                    </label>
                    <input type="text" name="reference"
                        class="form-control @error('reference') is-invalid @enderror"
                        value="{{ old('reference') }}"
                        placeholder="Ex: CON-205-55-R16"
                        required>
                    @error('reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Marque -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tag"></i> Marque *
                    </label>
                    <input type="text" name="marque"
                        class="form-control @error('marque') is-invalid @enderror"
                        value="{{ old('marque') }}"
                        placeholder="Ex: Continental, Michelin..."
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
                        <option value="">-- Choisir le type --</option>
                        <option value="pneu" {{ old('type') == 'pneu' ? 'selected' : '' }}>Pneu</option>
                        <option value="jante" {{ old('type') == 'jante' ? 'selected' : '' }}>Jante</option>
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
                        value="{{ old('dimension') }}"
                        placeholder="Ex: 205/55 R16"
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
                        value="{{ old('prix_achat') }}"
                        placeholder="0"
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
                        value="{{ old('prix_vente') }}"
                        placeholder="0"
                        min="0" step="1"
                        required>
                    @error('prix_vente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Quantité en stock -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-cubes"></i> Quantité en stock *
                    </label>
                    <input type="number" name="quantite_stock"
                        class="form-control @error('quantite_stock') is-invalid @enderror"
                        value="{{ old('quantite_stock', 0) }}"
                        min="0"
                        required>
                    @error('quantite_stock')
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
                        value="{{ old('seuil_alerte', 5) }}"
                        min="0"
                        required>
                    <small class="text-muted">
                        Alerte quand le stock descend en dessous de ce seuil
                    </small>
                    @error('seuil_alerte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection