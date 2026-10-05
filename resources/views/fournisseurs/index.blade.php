@extends('layouts.app')

@section('titre', 'Gestion des fournisseurs')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-truck text-warning"></i> Liste des fournisseurs</h5>
    <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau fournisseur
    </a>
</div>

<!-- Recherche -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('fournisseurs.index') }}" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control"
                    placeholder="Rechercher par nom, pays, email..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Rechercher
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
                    <th>Nom</th>
                    <th>Pays</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fournisseurs as $fournisseur)
                <tr>
                    <td><strong>{{ $fournisseur->nom }}</strong></td>
                    <td>
                        <span class="badge bg-info text-dark">
                            <i class="fas fa-globe"></i> {{ $fournisseur->pays }}
                        </span>
                    </td>
                    <td>{{ $fournisseur->telephone ?? '-' }}</td>
                    <td>{{ $fournisseur->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('fournisseurs.show', $fournisseur->id_fournisseur) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('fournisseurs.edit', $fournisseur->id_fournisseur) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('fournisseurs.destroy', $fournisseur->id_fournisseur) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce fournisseur ?')">
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
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-2x mb-2 d-block"></i>
                        Aucun fournisseur enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fournisseurs->hasPages())
    <div class="card-footer">
        {{ $fournisseurs->links() }}
    </div>
    @endif
</div>

@endsection