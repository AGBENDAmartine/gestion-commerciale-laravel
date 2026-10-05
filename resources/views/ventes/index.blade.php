@extends('layouts.app')

@section('titre', 'Gestion des ventes')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-cash-register text-warning"></i> Liste des ventes</h5>
    <a href="{{ route('ventes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle vente
    </a>
</div>
<div class="d-flex gap-2">
    <a href="{{ route('ventes.export.excel') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <a href="{{ route('ventes.export.pdf') }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    <a href="{{ route('ventes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle vente
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('ventes.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="date" name="date" class="form-control"
                    value="{{ request('date') }}">
            </div>
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher par nom du client..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
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
                    <th>Client</th>
                    <th>Vendeur</th>
                    <th>Montant total</th>
                    <th>Facture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventes as $vente)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                    <td><strong>{{ $vente->client->nom }}</strong></td>
                    <td>{{ $vente->utilisateur->nom }}</td>
                    <td>
                        <strong class="text-success">
                            {{ number_format($vente->montant_total, 0, ',', ' ') }} F
                        </strong>
                    </td>
                    <td>
                        @if($vente->facture)
                            <span class="badge bg-{{ $vente->facture->statut == 'payee' ? 'success' : ($vente->facture->statut == 'annulee' ? 'danger' : 'warning text-dark') }}">
                                {{ $vente->facture->numero }}
                            </span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ventes.show', $vente->id_vente) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($vente->facture)
                        <a href="{{ route('factures.imprimer', $vente->facture->id_facture) }}"
                           class="btn btn-sm btn-secondary" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-cash-register fa-2x mb-2 d-block"></i>
                        Aucune vente enregistrée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventes->hasPages())
    <div class="card-footer">
        {{ $ventes->links() }}
    </div>
    @endif
</div>

@endsection