@extends('layouts.app')

@section('titre', 'Détail fournisseur')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-truck text-warning"></i> Détail fournisseur</h5>
    <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Informations fournisseur -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle text-warning"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Nom</td>
                        <td><strong>{{ $fournisseur->nom }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Pays</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-globe"></i> {{ $fournisseur->pays }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Adresse</td>
                        <td>{{ $fournisseur->adresse ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Téléphone</td>
                        <td>{{ $fournisseur->telephone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Email</td>
                        <td>{{ $fournisseur->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nb achats</td>
                        <td>
                            <span class="badge bg-warning text-dark fs-6">
                                {{ $fournisseur->achats->count() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Total achats</td>
                        <td>
                            <strong class="text-success">
                                {{ number_format($fournisseur->achats->sum('montant_total'), 0, ',', ' ') }} F
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('fournisseurs.edit', $fournisseur->id_fournisseur) }}"
                   class="btn btn-warning w-100">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Historique des achats -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history text-warning"></i>
                Historique des achats
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Montant total</th>
                            <th>Enregistré par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fournisseur->achats as $achat)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $achat->type_achat == 'commande_fournisseur' ? 'primary' : 'success' }}">
                                    {{ $achat->type_achat == 'commande_fournisseur' ? 'Commande' : 'Achat port' }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ number_format($achat->montant_total, 0, ',', ' ') }} F</strong>
                            </td>
                            <td>{{ $achat->utilisateur->nom ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                                Aucun achat enregistré
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