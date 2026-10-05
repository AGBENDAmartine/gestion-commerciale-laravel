@extends('layouts.app')

@section('titre', 'Gestion du stock')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-warehouse text-warning"></i> État du stock</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.mouvements') }}" class="btn btn-outline-primary">
            <i class="fas fa-exchange-alt"></i> Mouvements
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('stock.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher par référence, marque, dimension..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="alerte" class="form-select">
                    <option value="">Tous les produits</option>
                    <option value="1" {{ request('alerte') == '1' ? 'selected' : '' }}>
                        En alerte de stock
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Formulaires entrée/sortie/transfert -->
<div class="row mb-4">
    <!-- Entrée stock -->
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-arrow-circle-down"></i> Entrée de stock
            </div>
            <div class="card-body">
                <form action="{{ route('stock.entree') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <select name="id_produit" class="form-select form-select-sm" required>
                            <option value="">-- Produit --</option>
                            @foreach($produits as $produit)
                            <option value="{{ $produit->id_produit }}">
                                {{ $produit->reference }} - {{ $produit->marque }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="id_entrepot" class="form-select form-select-sm" required>
                            <option value="">-- Entrepôt --</option>
                            @foreach($entrepots as $entrepot)
                            <option value="{{ $entrepot->id_entrepot }}">
                                {{ $entrepot->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="number" name="quantite"
                            class="form-control form-control-sm"
                            placeholder="Quantité" min="1" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="motif"
                            class="form-control form-control-sm"
                            placeholder="Motif (optionnel)">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-plus"></i> Enregistrer l'entrée
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sortie stock -->
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-arrow-circle-up"></i> Sortie de stock
            </div>
            <div class="card-body">
                <form action="{{ route('stock.sortie') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <select name="id_produit" class="form-select form-select-sm" required>
                            <option value="">-- Produit --</option>
                            @foreach($produits as $produit)
                            <option value="{{ $produit->id_produit }}">
                                {{ $produit->reference }} - {{ $produit->marque }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="id_entrepot" class="form-select form-select-sm" required>
                            <option value="">-- Entrepôt --</option>
                            @foreach($entrepots as $entrepot)
                            <option value="{{ $entrepot->id_entrepot }}">
                                {{ $entrepot->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="number" name="quantite"
                            class="form-control form-control-sm"
                            placeholder="Quantité" min="1" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="motif"
                            class="form-control form-control-sm"
                            placeholder="Motif (optionnel)">
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-minus"></i> Enregistrer la sortie
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfert stock -->
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-exchange-alt"></i> Transfert entre entrepôts
            </div>
            <div class="card-body">
                <form action="{{ route('stock.transfert') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <select name="id_produit" class="form-select form-select-sm" required>
                            <option value="">-- Produit --</option>
                            @foreach($produits as $produit)
                            <option value="{{ $produit->id_produit }}">
                                {{ $produit->reference }} - {{ $produit->marque }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="id_entrepot_from" class="form-select form-select-sm" required>
                            <option value="">-- Entrepôt source --</option>
                            @foreach($entrepots as $entrepot)
                            <option value="{{ $entrepot->id_entrepot }}">
                                {{ $entrepot->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="id_entrepot_to" class="form-select form-select-sm" required>
                            <option value="">-- Entrepôt destination --</option>
                            @foreach($entrepots as $entrepot)
                            <option value="{{ $entrepot->id_entrepot }}">
                                {{ $entrepot->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="number" name="quantite"
                            class="form-control form-control-sm"
                            placeholder="Quantité" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-exchange-alt"></i> Transférer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tableau du stock -->
{{-- Alerte globale stock --}}
@php
    $ruptures = $produits->filter(fn($p) => $p->quantite_stock == 0);
    $faibles  = $produits->filter(fn($p) => $p->quantite_stock > 0 && $p->enRuptureStock());
@endphp

@if($ruptures->count() > 0)
<div class="alert mb-3" style="background:#f8d7da; border:2px solid #dc3545; border-radius:10px; padding:15px 20px;">
    <div class="d-flex align-items-center gap-3">
        <i class="fas fa-times-circle fa-2x" style="color:#dc3545;"></i>
        <div>
            <strong style="color:#721c24; font-size:15px;">
                🔴 {{ $ruptures->count() }} produit(s) en rupture totale de stock !
            </strong>
            <ul class="mb-0 mt-1" style="color:#721c24;">
                @foreach($ruptures as $p)
                <li><strong>{{ $p->reference }}</strong> — {{ $p->marque }} {{ $p->dimension }} : 0 unité</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if($faibles->count() > 0)
<div class="alert mb-3" style="background:#fff3cd; border:2px solid #ffc107; border-radius:10px; padding:15px 20px;">
    <div class="d-flex align-items-center gap-3">
        <i class="fas fa-exclamation-triangle fa-2x" style="color:#e0a800;"></i>
        <div>
            <strong style="color:#856404; font-size:15px;">
                🟡 {{ $faibles->count() }} produit(s) avec stock faible !
            </strong>
            <ul class="mb-0 mt-1" style="color:#856404;">
                @foreach($faibles as $p)
                <li><strong>{{ $p->reference }}</strong> — {{ $p->marque }} {{ $p->dimension }} : {{ $p->quantite_stock }} unité(s) — Seuil : {{ $p->seuil_alerte }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if($ruptures->count() === 0 && $faibles->count() === 0)
<div class="alert mb-3" style="background:#d4edda; border:2px solid #28a745; border-radius:10px; padding:15px 20px;">
    <div class="d-flex align-items-center gap-3">
        <i class="fas fa-check-circle fa-2x" style="color:#28a745;"></i>
        <div>
            <strong style="color:#155724; font-size:15px;">
                ✅ Tous les stocks sont disponibles !
            </strong>
            <p class="mb-0 mt-1" style="color:#155724;">
                Aucun produit en rupture de stock ni en alerte de stock faible.
            </p>
        </div>
    </div>
</div>
@endif

{{-- Tableau stock --}}
<div class="card">
    <div class="card-header">
        <i class="fas fa-list text-warning"></i> État du stock par produit
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Référence</th>
                    <th>Marque</th>
                    <th>Type</th>
                    <th>Dimension</th>
                    <th>Stock actuel</th>
                    <th>Seuil alerte</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                <tr class="{{ $produit->enRuptureStock() ? 'table-danger' : '' }}">
                    <td><strong>{{ $produit->reference }}</strong></td>
                    <td>{{ $produit->marque }}</td>
                    <td>
                        <span class="badge bg-{{ $produit->type == 'pneu' ? 'primary' : 'success' }}">
                            {{ ucfirst($produit->type) }}
                        </span>
                    </td>
                    <td>{{ $produit->dimension }}</td>
                    <td>
                        <strong class="{{ $produit->enRuptureStock() ? 'text-danger' : 'text-success' }}">
                            {{ $produit->quantite_stock }}
                        </strong>
                    </td>
                    <td>{{ $produit->seuil_alerte }}</td>
                    <td>
                        @if($produit->quantite_stock == 0)
                            <span class="badge bg-danger">Rupture totale</span>
                        @elseif($produit->enRuptureStock())
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-exclamation-triangle"></i> Alerte
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> OK
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-warehouse fa-2x mb-2 d-block"></i>
                        Aucun produit en stock
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produits->hasPages())
    <div class="card-footer">
        {{ $produits->links() }}
    </div>
    @endif
</div>

@endsection