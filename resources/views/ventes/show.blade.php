@extends('layouts.app')

@section('titre', 'Détail vente')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-cash-register text-warning"></i> Détail de la vente</h5>
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Informations vente -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle text-warning"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Date</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Client</td>
                        <td>
                            <a href="{{ route('clients.show', $vente->client->id_client) }}">
                                <strong>{{ $vente->client->nom }}</strong>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Téléphone</td>
                        <td>{{ $vente->client->telephone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Vendeur</td>
                        <td>{{ $vente->utilisateur->nom }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Montant</td>
                        <td>
                            <strong class="text-success fs-5">
                                {{ number_format($vente->montant_total, 0, ',', ' ') }} F
                            </strong>
                        </td>
                    </tr>
                    @if($vente->facture)
                    <tr>
                        <td class="fw-bold text-muted">Facture</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $vente->facture->numero }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Statut</td>
                        <td>
                            <span class="badge bg-{{ $vente->facture->statut == 'payee' ? 'success' : ($vente->facture->statut == 'annulee' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($vente->facture->statut) }}
                            </span>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            @if($vente->facture)
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('factures.show', $vente->facture->id_facture) }}"
                   class="btn btn-primary flex-fill">
                    <i class="fas fa-file-invoice"></i> Facture
                </a>
                <a href="{{ route('factures.imprimer', $vente->facture->id_facture) }}"
                   class="btn btn-secondary flex-fill" target="_blank">
                    <i class="fas fa-print"></i> Imprimer
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Produits vendus -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-box text-warning"></i> Produits vendus
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Marque</th>
                            <th>Dimension</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vente->lignes as $ligne)
                        <tr>
                            <td><strong>{{ $ligne->produit->reference }}</strong></td>
                            <td>{{ $ligne->produit->marque }}</td>
                            <td>{{ $ligne->produit->dimension }}</td>
                            <td>{{ $ligne->quantite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                            <td>
                                <strong class="text-success">
                                    {{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} F
                                </strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total :</td>
                            <td>
                                <strong class="text-success fs-5">
                                    {{ number_format($vente->montant_total, 0, ',', ' ') }} F
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection