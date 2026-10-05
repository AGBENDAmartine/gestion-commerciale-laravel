@extends('layouts.app')

@section('titre', 'Gestion des clients')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-users text-warning"></i> Liste des clients</h5>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nouveau client
    </a>
</div>
<div class="d-flex gap-2">
    <a href="{{ route('clients.export.excel') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <a href="{{ route('clients.export.pdf') }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    
</div>

{{-- Statistiques clients --}}
@php
    $normal = $clients->where('categorie', 'normal')->count();
    $fidele = $clients->where('categorie', 'fidele')->count();
    $vip    = $clients->where('categorie', 'vip')->count();
@endphp
<br>
<br>
<br>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #6c757d, #868e96);">
            <div class="stat-number">{{ $clients->where('categorie', 'normal')->count() }}</div>
            <div class="stat-label"><i class="fas fa-user"></i> Clients Normaux</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #007bff, #0056b3);">
            <div class="stat-number">{{ $clients->where('categorie', 'fidele')->count() }}</div>
            <div class="stat-label"><i class="fas fa-star"></i> Clients Fidèles</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-number">{{ $clients->where('categorie', 'vip')->count() }}</div>
            <div class="stat-label"><i class="fas fa-crown"></i> Clients VIP</div>
        </div>
    </div>
</div>

<!-- Recherche et filtre -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('clients.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher par nom, téléphone, adresse..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="categorie" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <option value="normal"  {{ request('categorie') == 'normal'  ? 'selected' : '' }}>Normal</option>
                    <option value="fidele"  {{ request('categorie') == 'fidele'  ? 'selected' : '' }}>Fidèle</option>
                    <option value="vip"     {{ request('categorie') == 'vip'     ? 'selected' : '' }}>VIP</option>
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

<!-- Tableau des clients -->
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Catégorie</th>
                    <th>Remise</th>
                    <th>Date inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td><strong>{{ $client->nom }}</strong></td>
                    <td>{{ $client->telephone ?? '-' }}</td>
                    <td>{{ $client->adresse ?? '-' }}</td>
                    <td>
                        @if($client->categorie == 'vip')
                            <span class="badge" style="background:#f39c12; font-size:12px;">
                                <i class="fas fa-crown"></i> VIP
                            </span>
                        @elseif($client->categorie == 'fidele')
                            <span class="badge bg-primary" style="font-size:12px;">
                                <i class="fas fa-star"></i> Fidèle
                            </span>
                        @else
                            <span class="badge bg-secondary" style="font-size:12px;">
                                <i class="fas fa-user"></i> Normal
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($client->remise > 0)
                            <span class="badge bg-success">{{ $client->remise }}%</span>
                        @else
                            <span class="text-muted">0%</span>
                        @endif
                    </td>
                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                    <td>
                        <!-- Changer catégorie rapidement -->
                        <form action="{{ route('clients.categorie', $client->id_client) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <select name="categorie" class="form-select form-select-sm d-inline w-auto"
                                onchange="this.form.submit()" style="font-size:11px;">
                                <option value="normal" {{ $client->categorie == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="fidele" {{ $client->categorie == 'fidele' ? 'selected' : '' }}>Fidèle</option>
                                <option value="vip"    {{ $client->categorie == 'vip'    ? 'selected' : '' }}>VIP</option>
                            </select>
                        </form>
                        <a href="{{ route('clients.show', $client->id_client) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('clients.edit', $client->id_client) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('clients.destroy', $client->id_client) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce client ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                        Aucun client enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="card-footer">
        {{ $clients->links() }}
    </div>
    @endif
</div>

@endsection