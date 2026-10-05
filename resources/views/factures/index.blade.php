@extends('layouts.app')

@section('titre', 'Gestion des factures')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-file-invoice text-warning"></i> Liste des factures</h5>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('factures.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control"
                    placeholder="Numéro de facture..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="statut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="emise" {{ request('statut') == 'emise' ? 'selected' : '' }}>Émise</option>
                    <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control"
                    value="{{ request('date') }}">
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
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $facture)
                <tr>
                    <td><strong>{{ $facture->numero }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</td>
                    <td>{{ $facture->vente->client->nom }}</td>
                    <td>
                        <strong class="text-success">
                            {{ number_format($facture->montant_total, 0, ',', ' ') }} F
                        </strong>
                    </td>
                    <td>
                        <span class="badge bg-{{ $facture->statut == 'payee' ? 'success' : ($facture->statut == 'annulee' ? 'danger' : 'warning text-dark') }}">
                            {{ ucfirst($facture->statut) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('factures.show', $facture->id_facture) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('factures.imprimer', $facture->id_facture) }}"
                           class="btn btn-sm btn-secondary" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-file-invoice fa-2x mb-2 d-block"></i>
                        Aucune facture enregistrée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($factures->hasPages())
    <div class="card-footer">
        {{ $factures->links() }}
    </div>
    @endif
</div>

@endsection