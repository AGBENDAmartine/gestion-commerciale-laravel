@extends('layouts.app')

@section('titre', 'Mouvements de stock')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-exchange-alt text-warning"></i> Historique des mouvements</h5>
    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour au stock
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('stock.mouvements') }}" class="row g-3">
            <div class="col-md-4">
                <select name="type_mouvement" class="form-select">
                    <option value="">Tous les mouvements</option>
                    <option value="entree" {{ request('type_mouvement') == 'entree' ? 'selected' : '' }}>
                        Entrées
                    </option>
                    <option value="sortie" {{ request('type_mouvement') == 'sortie' ? 'selected' : '' }}>
                        Sorties
                    </option>
                    <option value="transfert" {{ request('type_mouvement') == 'transfert' ? 'selected' : '' }}>
                        Transferts
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control"
                    value="{{ request('date') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des mouvements -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Produit</th>
                    <th>Entrepôt</th>
                    <th>Quantité</th>
                    <th>Motif</th>
                    <th>Enregistré par</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mouvement)
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        @if($mouvement->type_mouvement == 'entree')
                            <span class="badge bg-success">
                                <i class="fas fa-arrow-down"></i> Entrée
                            </span>
                        @elseif($mouvement->type_mouvement == 'sortie')
                            <span class="badge bg-danger">
                                <i class="fas fa-arrow-up"></i> Sortie
                            </span>
                        @else
                            <span class="badge bg-primary">
                                <i class="fas fa-exchange-alt"></i> Transfert
                            </span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $mouvement->produit->reference }}</strong><br>
                        <small class="text-muted">
                            {{ $mouvement->produit->marque }} {{ $mouvement->produit->dimension }}
                        </small>
                    </td>
                    <td>{{ $mouvement->entrepot->nom }}</td>
                    <td>
                        <strong class="{{ $mouvement->type_mouvement == 'entree' ? 'text-success' : 'text-danger' }}">
                            {{ $mouvement->type_mouvement == 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                        </strong>
                    </td>
                    <td>{{ $mouvement->motif ?? '-' }}</td>
                    <td>{{ $mouvement->utilisateur->nom }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-exchange-alt fa-2x mb-2 d-block"></i>
                        Aucun mouvement enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mouvements->hasPages())
    <div class="card-footer">
        {{ $mouvements->links() }}
    </div>
    @endif
</div>

@endsection