@extends('layouts.app')

@section('titre', 'Paramètres')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-cog text-warning"></i> Paramètres de la plateforme
    </h5>
</div>

<div class="row">

  

    <!-- Sauvegarde -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-download text-warning"></i> Sauvegarde de la base de données
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Créez une sauvegarde complète de la base de données de la plateforme.
                    Le fichier sera sauvegardé au format <strong>.sql</strong>.
                </p>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Il est recommandé d'effectuer une sauvegarde régulière des données,
                    notamment avant toute mise à jour ou modification importante.
                </div>

                <form action="{{ route('parametres.sauvegarder') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                        onclick="return confirm('Lancer la sauvegarde de la base de données ?')">
                        <i class="fas fa-download"></i> Lancer la sauvegarde
                    </button>
                </form>

                <!-- Liste des sauvegardes -->
                @php
                    $backupPath = storage_path('app/backups');
                    $fichiers = [];
                    if (file_exists($backupPath)) {
                        $fichiers = array_diff(scandir($backupPath), ['.', '..']);
                        rsort($fichiers);
                    }
                @endphp

                @if(count($fichiers) > 0)
                <hr>
                <h6 class="fw-bold mt-3">
                    <i class="fas fa-history"></i> Sauvegardes disponibles
                </h6>
                <div class="list-group mt-2">
                    @foreach(array_slice($fichiers, 0, 5) as $fichier)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-file-archive text-success"></i>
                            {{ $fichier }}
                        </span>
                        <span class="badge bg-success">SQL</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Restauration -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-upload text-warning"></i> Restauration de la base de données
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Restaurez la base de données à partir d'un fichier de sauvegarde
                    <strong>.sql</strong> précédemment créé.
                </p>

                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention !</strong> La restauration remplacera toutes les données
                    actuelles par celles du fichier de sauvegarde. Cette action est irréversible.
                </div>

                <form action="{{ route('parametres.restaurer') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Sélectionner le fichier de sauvegarde (.sql)
                        </label>
                        <input type="file" name="fichier_backup"
                            class="form-control @error('fichier_backup') is-invalid @enderror"
                            accept=".sql">
                        @error('fichier_backup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger w-100"
                        onclick="return confirm('Attention ! Cette action remplacera toutes les données actuelles. Continuer ?')">
                        <i class="fas fa-upload"></i> Restaurer la base de données
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection