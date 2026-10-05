@extends('layouts.app')

@section('titre', 'Tableau de bord')

@section('contenu')

{{-- DASHBOARD GESTIONNAIRE --}}
@if(auth()->user()->role === 'gestionnaire')
<div class="row">
    <div class="col-12 mb-3">
        <h6 class="text-muted">
            <i class="fas fa-tachometer-alt"></i>
            Bienvenue, <strong>{{ auth()->user()->nom }}</strong> — Gestionnaire
        </h6>
    </div>

    {{-- 4 cartes stats sur la même ligne --}}
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-number">{{ number_format($stats['total_ventes_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-calendar-day"></i> Ventes aujourd'hui</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-number">{{ number_format($stats['total_ventes_mois'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-calendar-alt"></i> Ventes ce mois</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="stat-number">{{ $stats['nb_clients'] }}</div>
            <div class="stat-label"><i class="fas fa-users"></i> Total clients</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card" style="background: {{ $stats['produits_alerte'] > 0 ? 'linear-gradient(135deg, #e74c3c, #c0392b)' : 'linear-gradient(135deg, #2ecc71, #27ae60)' }};">
            <div class="stat-number">{{ $stats['produits_alerte'] }}</div>
            <div class="stat-label">
                @if($stats['produits_alerte'] > 0)
                    <i class="fas fa-exclamation-triangle"></i> Alertes stock
                @else
                    <i class="fas fa-check-circle"></i> Aucune rupture
                @endif
            </div>
        </div>
    </div>

    {{-- Dernières ventes --}}
    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cash-register text-warning"></i> Dernières ventes
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Vendeur</th>
                            <th>Date</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes_recentes as $vente)
                        <tr>
                            <td>{{ $vente->client->nom }}</td>
                            <td>{{ $vente->utilisateur->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Aucune vente enregistrée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Alertes stock --}}
    <div class="col-md-4 mt-3">
        <div class="card">
            <div class="card-header">
                @if($stats['produits_alerte'] > 0)
                    <i class="fas fa-exclamation-triangle text-danger"></i> Alertes stock
                @else
                    <i class="fas fa-check-circle text-success"></i> Stock en bon état
                @endif
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($produits_alerte as $produit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">{{ $produit->reference }}</small><br>
                            <strong>{{ $produit->marque }}</strong> {{ $produit->dimension }}
                        </div>
                        <span class="badge bg-danger">{{ $produit->quantite_stock }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-3" style="color:#27ae60;">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                        <strong>Tous les stocks sont disponibles !</strong>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Top produits --}}
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-trophy text-warning"></i> Top produits les plus vendus
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top_produits as $i => $produit)
                        <tr>
                            <td><span class="badge bg-warning text-dark">{{ $i + 1 }}</span></td>
                            <td>{{ $produit->reference }}</td>
                            <td>{{ $produit->marque }}</td>
                            <td>{{ $produit->dimension }}</td>
                            <td><strong>{{ $produit->total_vendu }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Aucune donnée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- DASHBOARD ADMINISTRATEUR --}}
@if(auth()->user()->role === 'administrateur')
<div class="row">
    <div class="col-12 mb-3">
        <h6 class="text-muted">
            Bienvenue, <strong>{{ auth()->user()->nom }}</strong> — Administrateur
        </h6>
    

    </div>
</div>
@endif

{{-- DASHBOARD VENDEUR --}}
@if(auth()->user()->role === 'vendeur')
<div class="row">
    <div class="col-12 mb-3">
        <h6 class="text-muted">
            Bienvenue, <strong>{{ auth()->user()->nom }}</strong> — Vendeur
        </h6>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-number">{{ $stats['nb_ventes_jour'] }}</div>
            <div class="stat-label"><i class="fas fa-cash-register"></i> Ventes aujourd'hui</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-number">{{ number_format($stats['total_ventes_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-label"><i class="fas fa-money-bill"></i> CA aujourd'hui</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="stat-number">{{ $stats['nb_clients'] }}</div>
            <div class="stat-label"><i class="fas fa-users"></i> Total clients</div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="col-md-6 mt-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt text-warning"></i> Actions rapides
            </div>
            <div class="card-body">
                <a href="{{ route('ventes.create') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-plus"></i> Nouvelle vente
                </a>
                <a href="{{ route('clients.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-user-plus"></i> Nouveau client
                </a>
                <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-file-invoice"></i> Voir les factures
                </a>
            </div>
        </div>
    </div>

    {{-- Dernières ventes --}}
    <div class="col-md-6 mt-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history text-warning"></i> Mes dernières ventes
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes_recentes as $vente)
                        <tr>
                            <td>{{ $vente->client->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                            <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Aucune vente</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- DASHBOARD MAGASINIER --}}
@if(auth()->user()->role === 'magasinier')
<div class="row">
    <div class="col-12 mb-3">
        <h6 class="text-muted">
            Bienvenue, <strong>{{ auth()->user()->nom }}</strong> — Magasinier
        </h6>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="stat-number">{{ $stats['nb_produits'] }}</div>
            <div class="stat-label"><i class="fas fa-box"></i> Total produits</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: {{ $stats['produits_alerte'] > 0 ? 'linear-gradient(135deg, #e74c3c, #c0392b)' : 'linear-gradient(135deg, #2ecc71, #27ae60)' }};">
            <div class="stat-number">{{ $stats['produits_alerte'] }}</div>
            <div class="stat-label">
                @if($stats['produits_alerte'] > 0)
                    <i class="fas fa-exclamation-triangle"></i> Alertes stock
                @else
                    <i class="fas fa-check-circle"></i> Aucune rupture
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-number">{{ $stats['nb_produits'] - $stats['produits_alerte'] }}</div>
            <div class="stat-label"><i class="fas fa-check-circle"></i> Produits OK</div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="col-md-4 mt-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt text-warning"></i> Actions rapides
            </div>
            <div class="card-body">
                <a href="{{ route('stock.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-warehouse"></i> Voir le stock
                </a>
                <a href="{{ route('stock.mouvements') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-exchange-alt"></i> Mouvements
                </a>
                <a href="{{ route('entrepots.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-building"></i> Entrepôts
                </a>
            </div>
        </div>
    </div>

    {{-- Alertes stock --}}
    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header">
                @if($stats['produits_alerte'] > 0)
                    <i class="fas fa-exclamation-triangle text-danger"></i> Produits en alerte
                @else
                    <i class="fas fa-check-circle text-success"></i> Stock en bon état
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Marque</th>
                            <th>Dimension</th>
                            <th>Stock</th>
                            <th>Seuil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits_alerte as $produit)
                        <tr>
                            <td>{{ $produit->reference }}</td>
                            <td>{{ $produit->marque }}</td>
                            <td>{{ $produit->dimension }}</td>
                            <td><span class="badge bg-danger">{{ $produit->quantite_stock }}</span></td>
                            <td>{{ $produit->seuil_alerte }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-3" style="color:#27ae60;">
                                <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                <strong>Tous les stocks sont disponibles !</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection