@extends('layouts.app')

@section('titre', 'Gestion des produits')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-box text-warning"></i> Liste des produits</h5>
    <a href="{{ route('produits.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau produit
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('produits.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher par référence, marque, dimension..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="pneu" {{ request('type') == 'pneu' ? 'selected' : '' }}>Pneus</option>
                    <option value="jante" {{ request('type') == 'jante' ? 'selected' : '' }}>Jantes</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des produits -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Référence</th>
                    <th>Marque</th>
                    <th>Type</th>
                    <th>Dimension</th>
                    <th>Prix achat</th>
                    <th>Prix vente</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                <tr>
                    <td><strong>{{ $produit->reference }}</strong></td>
                    <td>{{ $produit->marque }}</td>
                    <td>
                        <span class="badge {{ $produit->type == 'pneu' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($produit->type) }}
                        </span>
                    </td>
                    <td>{{ $produit->dimension }}</td>
                    <td>{{ number_format($produit->prix_achat, 0, ',', ' ') }} F</td>
                    <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} F</td>
                    <td>
                        @if($produit->quantite_stock <= $produit->seuil_alerte)
                            <span class="badge bg-danger">{{ $produit->quantite_stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $produit->quantite_stock }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('produits.edit', $produit->id_produit) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('produits.destroy', $produit->id_produit) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                        Aucun produit enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produits->hasPages())
    <div class="card-footer">
        {{ $produits->links() }}
    </div>
    @endif
</div>

@endsection