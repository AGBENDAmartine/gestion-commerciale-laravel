@extends('layouts.app')

@section('titre', 'Modifier un client')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-user-edit text-warning"></i> Modifier un client</h5>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clients.update', $client->id_client) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nom -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user"></i> Nom complet *
                            </label>
                            <input type="text" name="nom"
                                class="form-control @error('nom') is-invalid @enderror"
                                value="{{ old('nom', $client->nom) }}"
                                required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone"></i> Téléphone
                            </label>
                            <input type="text" name="telephone"
                                class="form-control @error('telephone') is-invalid @enderror"
                                value="{{ old('telephone', $client->telephone) }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt"></i> Adresse
                            </label>
                            <input type="text" name="adresse"
                                class="form-control @error('adresse') is-invalid @enderror"
                                value="{{ old('adresse', $client->adresse) }}">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remarques -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment"></i> Remarques
                            </label>
                            <textarea name="remarques"
                                class="form-control @error('remarques') is-invalid @enderror"
                                rows="3"
                                placeholder="Notes sur le client...">{{ old('remarques', $client->remarques) }}</textarea>
                            @error('remarques')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Section fidélité -->
                    <h6 class="fw-bold text-warning mb-3">
                        <i class="fas fa-star"></i> Fidélité et remise
                    </h6>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Ce client a effectué <strong>{{ $client->ventes->count() }} achat(s)</strong>
                        pour un total de
                        <strong>{{ number_format($client->ventes->sum('montant_total'), 0, ',', ' ') }} F</strong>.
                        Vous pouvez lui attribuer manuellement une catégorie et une remise.
                    </div>

                    <div class="row">
                        <!-- Catégorie -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-star"></i> Catégorie client
                            </label>
                            <select name="categorie"
                                class="form-select @error('categorie') is-invalid @enderror"
                                onchange="suggererRemise(this.value)">
                                <option value="normal" {{ old('categorie', $client->categorie) == 'normal' ? 'selected' : '' }}>
                                    ⭐ Normal
                                </option>
                                <option value="fidele" {{ old('categorie', $client->categorie) == 'fidele' ? 'selected' : '' }}>
                                    ⭐⭐ Fidèle
                                </option>
                                <option value="vip" {{ old('categorie', $client->categorie) == 'vip' ? 'selected' : '' }}>
                                    ⭐⭐⭐ VIP
                                </option>
                            </select>
                            @error('categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remise -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-percent"></i> Remise (%)
                            </label>
                            <div class="input-group">
                                <input type="number" name="remise" id="remise"
                                    class="form-control @error('remise') is-invalid @enderror"
                                    value="{{ old('remise', $client->remise) }}"
                                    min="0" max="100" step="0.5">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">
                                Remise appliquée automatiquement lors des ventes
                            </small>
                            @error('remise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Résumé fidélité -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-star text-warning"></i> Catégories disponibles
            </div>
            <div class="card-body">
                <div class="mb-3 p-3 rounded" style="background:#f8f9fa;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-secondary">⭐ Normal</span>
                    </div>
                    <small class="text-muted">
                        Client occasionnel, pas de remise.
                    </small>
                </div>

                <div class="mb-3 p-3 rounded" style="background:#fff3cd;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark">⭐⭐ Fidèle</span>
                    </div>
                    <small class="text-muted">
                        Client régulier, remise suggérée : <strong>5%</strong>.
                    </small>
                </div>

                <div class="mb-3 p-3 rounded" style="background:#f8d7da;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-danger">⭐⭐⭐ VIP</span>
                    </div>
                    <small class="text-muted">
                        Meilleur client, remise suggérée : <strong>10%</strong>.
                    </small>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Conseil :</strong> La remise est libre.
                    Vous pouvez mettre le pourcentage que vous voulez
                    selon votre relation avec le client.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Suggérer une remise selon la catégorie choisie
    function suggererRemise(categorie) {
        const remise = document.getElementById('remise');
        if (categorie === 'vip') {
            remise.value = 10;
        } else if (categorie === 'fidele') {
            remise.value = 5;
        } else {
            remise.value = 0;
        }
    }
</script>
@endpush