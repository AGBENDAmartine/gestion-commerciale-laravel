@extends('layouts.app')

@section('titre', 'Détail facture')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-file-invoice text-warning"></i> Détail facture</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('factures.imprimer', $facture->id_facture) }}"
           class="btn btn-secondary" target="_blank">
            <i class="fas fa-print"></i> Imprimer
        </a>
        <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <!-- En-tête facture -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="text-warning fw-bold">YAONABA & FRERE</h4>
                <p class="text-muted mb-1">Vente de pneus et jantes</p>
                <p class="text-muted mb-1">Lomé, Togo</p>
            </div>
            <div class="col-md-6 text-end">
                <h4 class="fw-bold">FACTURE</h4>
                <p class="mb-1">
                    <strong>N° :</strong>
                    <span class="text-warning">{{ $facture->numero }}</span>
                </p>
                <p class="mb-1">
                    <strong>Date :</strong>
                    {{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}
                </p>
                <span class="badge bg-{{ $facture->statut == 'payee' ? 'success' : ($facture->statut == 'annulee' ? 'danger' : 'warning text-dark') }} fs-6">
                    {{ ucfirst($facture->statut) }}
                </span>
            </div>
        </div>

        <hr>

        <!-- Informations client -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-muted">FACTURÉ À :</h6>
                <p class="mb-1"><strong>{{ $facture->vente->client->nom }}</strong></p>
                <p class="mb-1 text-muted">{{ $facture->vente->client->telephone ?? '' }}</p>
                <p class="mb-1 text-muted">{{ $facture->vente->client->adresse ?? '' }}</p>
            </div>
            <div class="col-md-6 text-end">
                <h6 class="fw-bold text-muted">VENDEUR :</h6>
                <p class="mb-1">{{ $facture->vente->utilisateur->nom }}</p>
            </div>
        </div>

        <!-- Tableau des produits -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-end">Prix unitaire</th>
                    <th class="text-end">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->vente->lignes as $i => $ligne)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $ligne->produit->reference }}</td>
                    <td>{{ $ligne->produit->marque }} {{ $ligne->produit->dimension }}</td>
                    <td class="text-center">{{ $ligne->quantite }}</td>
                    <td class="text-end">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                    <td class="text-end">
                        <strong>{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} F</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-warning">
                    <td colspan="5" class="text-end fw-bold fs-5">TOTAL :</td>
                    <td class="text-end fw-bold fs-5">
                        {{ number_format($facture->montant_total, 0, ',', ' ') }} F CFA
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Changer statut -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold">Changer le statut</h6>
                        <form action="{{ route('factures.statut', $facture->id_facture) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <select name="statut" class="form-select">
                                <option value="emise" {{ $facture->statut == 'emise' ? 'selected' : '' }}>Émise</option>
                                <option value="payee" {{ $facture->statut == 'payee' ? 'selected' : '' }}>Payée</option>
                                <option value="annulee" {{ $facture->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection