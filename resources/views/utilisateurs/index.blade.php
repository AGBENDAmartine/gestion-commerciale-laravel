@extends('layouts.app')

@section('titre', 'Gestion des utilisateurs')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-users text-warning"></i> Gestion des utilisateurs
    </h5>
    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nouvel utilisateur
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateurs as $utilisateur)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $utilisateur->nom }}</strong></td>
                    <td>{{ $utilisateur->email }}</td>
                    <td>
                        @php
                            $colors = [
                                'administrateur' => 'danger',
                                'gestionnaire'   => 'primary',
                                'vendeur'        => 'success',
                                'magasinier'     => 'warning',
                            ];
                        @endphp
                        <span class="badge bg-{{ $colors[$utilisateur->role] ?? 'secondary' }}">
                            {{ ucfirst($utilisateur->role) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('utilisateurs.edit', $utilisateur->id_utilisateur) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @if($utilisateur->id_utilisateur !== auth()->user()->id_utilisateur)
                        <form action="{{ route('utilisateurs.destroy', $utilisateur->id_utilisateur) }}"
                              method="POST" style="display:inline;"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Aucun utilisateur enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection