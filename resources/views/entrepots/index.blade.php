@extends('layouts.app')

@section('titre', 'Gestion des entrepôts')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-building text-warning"></i> Liste des entrepôts</h5>
    <a href="{{ route('entrepots.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel entrepôt
    </a>
</div>

<div class="row">
    @forelse($entrepots as $entrepot)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-{{ $entrepot->type == 'principal' ? 'warehouse' : 'store' }} text-warning"></i>
                    <strong>{{ $entrepot->nom }}</strong>
                </span>
                <span class="badge bg-{{ $entrepot->type == 'principal' ? 'primary' : 'success' }}">
                    {{ ucfirst($entrepot->type) }}
                </span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-bold text-muted">Type</td>
                        <td>{{ ucfirst($entrepot->type) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Adresse</td>
                        <td>{{ $entrepot->adresse ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Nb produits</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $entrepot->produits_count }} produits
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('entrepots.show', $entrepot->id_entrepot) }}"
                   class="btn btn-sm btn-info text-white flex-fill">
                    <i class="fas fa-eye"></i> Voir
                </a>
                <a href="{{ route('entrepots.edit', $entrepot->id_entrepot) }}"
                   class="btn btn-sm btn-warning flex-fill">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('entrepots.destroy', $entrepot->id_entrepot) }}"
                      method="POST" class="flex-fill"
                      onsubmit="return confirm('Supprimer cet entrepôt ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-building fa-3x mb-3 d-block"></i>
                Aucun entrepôt enregistré
            </div>
        </div>
    </div>
    @endforelse
</div>

@endsection