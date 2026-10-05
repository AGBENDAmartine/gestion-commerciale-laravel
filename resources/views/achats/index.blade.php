 @extends('layouts.app')

@section('titre', 'Gestion des achats')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-shopping-cart text-warning"></i> Liste des achats</h5>
    <a href="{{ route('achats.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel achat
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('achats.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="date" name="date" class="form-control"
                    value="{{ request('date') }}">
            </div>
            <div class="col-md-4">
                <select name="type_achat" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="commande_fournisseur" {{ request('type_achat') == 'commande_fournisseur' ? 'selected' : '' }}>
                        Commande fournisseur
                    </option>
                    <option value="achat_port" {{ request('type_achat') == 'achat_port' ? 'selected' : '' }}>
                        Achat au port
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Fournisseur</th>
                    <th>Montant total</th>
                    <th>Enregistré par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achats as $achat)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $achat->type_achat == 'commande_fournisseur' ? 'primary' : 'success' }}">
                            {{ $achat->type_achat == 'commande_fournisseur' ? 'Commande fournisseur' : 'Achat au port' }}
                        </span>
                    </td>
                    <td>{{ $achat->fournisseur->nom ?? 'Achat direct' }}</td>
                    <td><strong>{{ number_format($achat->montant_total, 0, ',', ' ') }} F</strong></td>
                    <td>{{ $achat->utilisateur->nom }}</td>
                    <td>
                        <a href="{{ route('achats.show', $achat->id_achat) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                        Aucun achat enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($achats->hasPages())
    <div class="card-footer">
        {{ $achats->links() }}
    </div>
    @endif
</div>

@endsection