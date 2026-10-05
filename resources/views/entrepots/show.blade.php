@extends('layouts.app')

@section('titre', 'Détail entrepôt')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-building text-warning"></i> Détail entrepôt</h5>
    <a href="{{ route('entrepots.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Informations entrepôt -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle text-warning"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Nom</td>
                        <td><strong>{{ $entrepot->nom }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Type</td>
                        <td>
                            <span class="badge bg-{{ $entrepot->type == 'principal' ? 'primary' : 'success' }}">
                                {{ ucfirst($entrepot->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Adresse</td>
                        <td>{{ $entrepot->adresse ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nb produits</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $entrepot->produits->count() }} produits
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('entrepots.edit', $entrepot->id_entrepot) }}"
                   class="btn btn-warning w-100">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Produits dans l'entrepôt -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-boxes text-warning"></i>
                Produits stockés dans cet entrepôt
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Marque</th>
                            <th>Type</th>
                            <th>Dimension</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entrepot->produits as $produit)
                        <tr>
                            <td><strong>{{ $produit->reference }}</strong></td>
                            <td>{{ $produit->marque }}</td>
                            <td>
                                <span class="badge bg-{{ $produit->type == 'pneu' ? 'primary' : 'success' }}">
                                    {{ ucfirst($produit->type) }}
                                </span>
                            </td>
                            <td>{{ $produit->dimension }}</td>
                            <td>
                                <span class="badge bg-{{ $produit->pivot->quantite > 0 ? 'success' : 'danger' }}">
                                    {{ $produit->pivot->quantite }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                Aucun produit dans cet entrepôt
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection