@extends('layouts.app')

@section('titre', 'Fiche client')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-user text-warning"></i> Fiche client</h5>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Informations client -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user text-warning"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Nom</td>
                        <td><strong>{{ $client->nom }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Téléphone</td>
                        <td>{{ $client->telephone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Adresse</td>
                        <td>{{ $client->adresse ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Client depuis</td>
                        <td>{{ $client->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Total achats</td>
                        <td>
                            <span class="badge bg-warning text-dark fs-6">
                                {{ $client->ventes->count() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Total dépensé</td>
                        <td>
                            <strong class="text-success">
                                {{ number_format($client->ventes->sum('montant_total'), 0, ',', ' ') }} F
                            </strong>
                        </td>
                    </tr>
                </table>

                @if($client->remarques)
                <hr>
                <p class="text-muted mb-1"><i class="fas fa-comment"></i> Remarques :</p>
                <p class="mb-0">{{ $client->remarques }}</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('clients.edit', $client->id_client) }}"
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
                @forelse($client->ventes as $vente)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>Vente du {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</strong>
                            @if($vente->facture)
                                <span class="badge bg-secondary ms-2">
                                    {{ $vente->facture->numero }}
                                </span>
                                <span class="badge bg-{{ $vente->facture->statut == 'payee' ? 'success' : ($vente->facture->statut == 'annulee' ? 'danger' : 'warning') }} ms-1">
                                    {{ ucfirst($vente->facture->statut) }}
                                </span>
                            @endif
                        </div>
                        <strong class="text-success">
                            {{ number_format($vente->montant_total, 0, ',', ' ') }} F
                        </strong>
                    </div>

                    <!-- Produits achetés -->
                    <div class="mt-2">
                        @foreach($vente->lignes as $ligne)
                        <div class="d-flex justify-content-between text-muted small">
                            <span>
                                <i class="fas fa-circle fa-xs"></i>
                                {{ $ligne->produit->marque }}
                                {{ $ligne->produit->dimension }}
                                ({{ $ligne->produit->reference }})
                                x{{ $ligne->quantite }}
                            </span>
                            <span>{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} F</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Lien vers la facture -->
                    @if($vente->facture)
                    <div class="mt-2">
                        <a href="{{ route('factures.show', $vente->facture->id_facture) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-invoice"></i> Voir la facture
                        </a>
                        <a href="{{ route('factures.imprimer', $vente->facture->id_facture) }}"
                           class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-shopping-bag fa-2x mb-2 d-block"></i>
                    Aucun achat enregistré pour ce client
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection