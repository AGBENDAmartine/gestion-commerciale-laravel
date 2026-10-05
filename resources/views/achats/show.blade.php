@extends('layouts.app')

@section('titre', 'Détail achat')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-shopping-cart text-warning"></i> Détail de l'achat</h5>
    <a href="{{ route('achats.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Informations achat -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle text-warning"></i> Informations
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Date</td>
                        <td>{{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Type</td>
                        <td>
                            <span class="badge bg-{{ $achat->type_achat == 'commande_fournisseur' ? 'primary' : 'success' }}">
                                {{ $achat->type_achat == 'commande_fournisseur' ? 'Commande fournisseur' : 'Achat au port' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Fournisseur</td>
                        <td>{{ $achat->fournisseur->nom ?? 'Achat direct' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Enregistré par</td>
                        <td>{{ $achat->utilisateur->nom }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Montant total</td>
                        <td>
                            <strong class="text-success fs-5">
                                {{ number_format($achat->montant_total, 0, ',', ' ') }} F
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Produits achetés -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-box text-warning"></i> Produits achetés
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
                        @foreach($achat->lignes as $ligne)
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
                                    {{ number_format($achat->montant_total, 0, ',', ' ') }} F
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