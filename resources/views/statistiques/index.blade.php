@extends('layouts.app')

@section('titre', 'Statistiques et rapports')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-chart-bar text-warning"></i> Statistiques et rapports</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('statistiques.export.excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('statistiques.export.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Bilan financier -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="stat-number">{{ number_format($bilan['total_achats'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-shopping-cart"></i> Total achats (année)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-number">{{ number_format($bilan['total_ventes'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-cash-register"></i> Total ventes (année)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-number">{{ number_format($bilan['benefice'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-chart-line"></i> Bénéfice (année)</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chiffre d'affaires par mois -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar text-warning"></i>
                Chiffre d'affaires mensuel — {{ now()->year }}
            </div>
            <div class="card-body">
                @php
                    $mois_noms = [
                        1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
                        5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Aoû',
                        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
                    ];
                    $max = $ca_par_mois->max('total') ?: 1;
                @endphp

                @if($ca_par_mois->count() > 0)
                    @foreach($ca_par_mois as $ca)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold">{{ $mois_noms[$ca->mois] }}</span>
                            <span class="text-success fw-bold">
                                {{ number_format($ca->total, 0, ',', ' ') }} F
                            </span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-warning"
                                style="width: {{ ($ca->total / $max) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                        Aucune donnée disponible
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Produits en alerte -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-exclamation-triangle text-danger"></i>
                Produits en rupture
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($produits_alerte as $produit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">{{ $produit->reference }}</small><br>
                            <strong>{{ $produit->marque }}</strong>
                            {{ $produit->dimension }}
                        </div>
                        <span class="badge bg-danger rounded-pill">
                            {{ $produit->quantite_stock }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">
                        <i class="fas fa-check-circle text-success"></i>
                        Aucune alerte
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Top 10 produits -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-trophy text-warning"></i>
                Top 10 produits les plus vendus
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Marque</th>
                            <th>Dimension</th>
                            <th>Quantité vendue</th>
                            <th>Recettes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top_produits as $i => $produit)
                        <tr>
                            <td>
                                @if($i == 0)
                                    <span class="badge bg-warning text-dark">🥇</span>
                                @elseif($i == 1)
                                    <span class="badge bg-secondary">🥈</span>
                                @elseif($i == 2)
                                    <span class="badge bg-danger">🥉</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td><strong>{{ $produit->reference }}</strong></td>
                            <td>{{ $produit->marque }}</td>
                            <td>{{ $produit->dimension }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $produit->total_vendu }} unités
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ number_format($produit->recettes, 0, ',', ' ') }} F
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucune donnée disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ventes de la semaine -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-week text-warning"></i>
                Ventes cette semaine
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Jour</th>
                            <th>Nb ventes</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes_semaine as $jour)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($jour->jour)->format('d/m') }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $jour->nb_ventes }}</span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ number_format($jour->total, 0, ',', ' ') }} F
                                </strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Aucune vente cette semaine
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